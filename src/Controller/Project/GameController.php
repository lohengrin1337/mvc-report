<?php

namespace App\Controller\Project;

use App\Card\CardDeck;
use App\Card\CardSvg;
use App\Entity\Board;
use App\Entity\Player;
use App\Entity\Round;
use App\Entity\Score;
use App\Exception\InvalidSlotException;
use App\Form\ConfirmDeleteType;
use App\Form\ConfirmType;
use App\Form\PlayerSelectType;
use App\Form\PlayerType;
use App\PokerSquares\AmericanScores;
use App\PokerSquares\Gameboard;
use App\PokerSquares\GameManager;
use App\PokerSquares\PokerSquareRules;
use App\PokerSquares\PokerSquaresGame;
use App\Repository\PlayerRepository;
use App\Service\InitCpuPlayerService;
use App\Service\SelectedPlayersService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for Poker Squares Game
 */
class GameController extends AbstractController
{
    /**
     * @var array<mixed> $data - template data
     */
    private array $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [
            "siteTitle" => "MVC",
            "localTimeZone" => "Europe/Stockholm",
            "pageTitle" => "",
        ];
    }

    #[Route("/proj/game/init", name: "proj_game_init", methods: ["GET", "POST"])]
    public function gameInit(
        InitCpuPlayerService $icps,
        Request $request,
        PlayerRepository $playerRepository,
        EntityManagerInterface $entityManager,
        SelectedPlayersService $sps,
        SessionInterface $session
    ): Response {
        // add missing cpu players
        try {
            $icps->addMissingPlayers();
        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash(
                "warning",
                "En spelare har ett namn som blockerar en dator-spelare att skapas."
            );
        }

        // create new players
        $playerForm = $this->createForm(
            PlayerType::class,
            new Player(),
            [
                "name_label" => "Skapa ny spelare",
                "submit_label" => "Skapa",
            ]
        );
        $playerForm->handleRequest($request);
        if ($playerForm->isSubmitted() && $playerForm->isValid()) {
            try {
                $player = $playerForm->getData();
                $entityManager->persist($player);
                $entityManager->flush();
                $this->addFlash("notice", "Spelaren '{$player->getName()}' har lagts till!");
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash("warning", "Det finns redan en spelare med namn '{$player->getName()}'!");
            } catch (\Exception $e) {
                $this->addFlash("warning", $e->getMessage());
            } finally {
                return $this->redirectToRoute("proj_game_init");
            }
        }

        // get players from session, verify their existance, and correct if neccessary
        $players = $sps->getSelectedPlayers($session);

        // select player
        $playerSelectForm = $this->createForm(PlayerSelectType::class, null, ["submit_label" => "Lägg till"]);
        $playerSelectForm->handleRequest($request);
        if ($playerSelectForm->isSubmitted() && $playerSelectForm->isValid()) {
            $data = $playerSelectForm->getData();
            $players[] = $data["player"];
            $session->set("players", $players);
        }

        // deselect all players
        $playerDeselectBtn = $this->createForm(
            ConfirmDeleteType::class,
            null,
            [
                "label" => "Ta bort valda spelare",
                "btn-attr" => ["class" => "button margin-b"],
            ]
        );
        $playerDeselectBtn->handleRequest($request);
        if ($playerDeselectBtn->isSubmitted() && $playerDeselectBtn->isValid()) {
            $players = [];
            $session->set("players", $players);
        }

        // start game
        $startBtn = $this->createForm(ConfirmType::class, null, ["label" => "Starta spelet"]);
        $startBtn->handleRequest($request);
        if ($startBtn->isSubmitted() && $startBtn->isValid()) {
            $games = [];
            $deck = new CardDeck(CardSvg::class);
            foreach ($players as $player) {
                $game = new PokerSquaresGame(
                    new PokerSquareRules(),
                    new AmericanScores(),
                    new Score(),
                    new Gameboard(),
                    $player,
                    clone $deck     // same deck for all players, but unique instances
                );

                // FILL GAMEBOARD FOR TESTING
                // $gb = new GameBoard();
                // $slots = array_keys($gb->getBoardView());
                // for ($i=0; $i < 22; $i++) {
                //     $game->process($slots[$i]);
                // }

                $games[] = $game;
            }

            $gameManager = new GameManager($games);
            $session->set("gameManager", $gameManager);

            return $this->redirectToRoute("proj_game_play");
        }

        $this->data["pageTitle"] = "Välj spelare";
        $this->data["playerForm"] = $playerForm;
        $this->data["playerSelectForm"] = $playerSelectForm;
        $this->data["players"] = $players;
        $this->data["playerDeselectBtn"] = $playerDeselectBtn;
        $this->data["startBtn"] = $startBtn;
        return $this->render("proj/game/game_setup.html.twig", $this->data);
    }



    #[Route("/proj/game/play", name: "proj_game_play", methods: ["GET"])]
    public function gamePlay(SessionInterface $session): Response
    {
        $gameManager = $session->get("gameManager") ?? null;
        if (!$gameManager) {
            $this->addFlash("warning", "Speldata saknades!");
            $this->redirectToRoute("proj_game_init");
        }

        // handle end of game
        if ($gameManager->allGamesAreOver()) {
            $this->data["pageTitle"] = "Resultat";
            $this->data["conclusion"] = $gameManager->getConclusion();
            $this->data["gameStates"] = $gameManager->getAllGameStates();
            return $this->render("proj/game/end_of_game.html.twig", $this->data);
        }

        $game = $gameManager->getCurrentGame();

        // handle cpu players action
        if ($game->playerIsCpu()) {
            $game->cpuPlay();
        }

        // show gameplay
        $gameState = $game->getState();
        $this->data["pageTitle"] = "Pokersquares";
        $this->data["game"] = $gameState;
        return $this->render("proj/game/gameplay.html.twig", $this->data);
    }



    #[Route("/proj/game/place-card", name: "proj_place_card", methods: ["POST"])]
    public function placeCard(
        Request $request,
        SessionInterface $session
    ): Response {
        $slotId = $request->request->get("slot_id");
        $gameManager = $session->get("gameManager");
        $game = $gameManager->getCurrentGame();

        try {
            $game->process($slotId);
        } catch (InvalidSlotException $e) {
            $this->addFlash("warning", "Du var lite snabb, och klickade på samma ruta två gånger");
        }

        return $this->redirectToRoute("proj_game_play");
    }



    #[Route("/proj/game/save", name: "proj_game_save", methods: ["POST"])]
    public function saveGame(
        Request $request,
        SessionInterface $session,
        PlayerRepository $playerRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $gameIndex = $request->request->get("game_index");
        $gameManager = $session->get("gameManager");
        $game = $gameManager->getGameByIndex($gameIndex);
        $roundData = $game->getRoundData();

        // Player entity has been detached from entity manager,
        // and needs to be fetched again
        $playerId = $roundData["player"]->getId();
        $player = $playerRepository->find($playerId);

        if(!$player) {
            $playerName = $roundData["player"]->getName();
            $this->addFlash(
                "warning",
                "Spelaren '$playerName' är raderad. " .
                "Rundan kan inte sparas!"
            );
            return $this->redirectToRoute("proj_game_play");
        }

        $round = new Round();
        $round->setRoundData(
            $player,
            $roundData["board"],
            $roundData["score"],
            $roundData["start"],
            $roundData["finish"],
            $roundData["duration"]
        );

        try {
            $entityManager->persist($round);
            $entityManager->flush();
            $this->addFlash("notice", "{$player->getName()}'s runda sparades!");
        } catch (UniqueConstraintViolationException) {
            $this->addFlash("warning", "{$player->getName()}'s runda har redan sparats!");
        }

        return $this->redirectToRoute("proj_game_play");
    }
}
