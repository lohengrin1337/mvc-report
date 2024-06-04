<?php

namespace App\Controller;

use App\Card\CardDeck;
use App\Card\CardSvg;
use App\Entity\Board;
use App\Entity\Player;
use App\Entity\Round;
use App\Entity\Score;
use App\PokerSquares\AmericanScores;
use App\PokerSquares\Gameboard;
use App\PokerSquares\PokerSquareRules;
use App\PokerSquares\PokerSquaresGame;
use App\Repository\BoardRepository;
use App\Repository\PlayerRepository;
use App\Repository\RoundRepository;
use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for landingpage and about pages of project
 */
class ProjectController extends AbstractController
{
    /**
     * @var array<mixed> $data - template data
     */
    private $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [
            "siteTitle" => "MVC",
            "pageTitle" => "",
        ];
    }



    #[Route("/proj", name: "proj_start", methods: ["GET"])]
    public function index(): Response
    {
        $this->data["pageTitle"] = "Projekt";
        return $this->render("proj/index.html.twig", $this->data);
    }



    #[Route("/proj/about", name: "proj_about", methods: ["GET"])]
    public function about(): Response
    {
        $this->data["pageTitle"] = "Om Projektet";
        return $this->render("proj/about/index.html.twig", $this->data);
    }



    #[Route("/proj/about/database", name: "proj_database", methods: ["GET"])]
    public function database(): Response
    {
        $this->data["pageTitle"] = "Databasen";
        return $this->render("proj/about/database.html.twig", $this->data);
    }



    #[Route("/proj/game", name: "proj_game_start", methods: ["GET"])]
    public function gameStart(): Response
    {
        $this->data["pageTitle"] = "Poker Squares";
        return $this->render("proj/game/index.html.twig", $this->data);
    }



    #[Route("/proj/game/rules", name: "proj_rules", methods: ["GET"])]
    public function gameRules(): Response
    {
        $this->data["pageTitle"] = "Regler";
        return $this->render("proj/game/rules.html.twig", $this->data);
    }



    #[Route("/proj/game/highscore", name: "proj_highscore", methods: ["GET"])]
    public function highscore(RoundRepository $roundRepository): Response
    {
        $this->data["pageTitle"] = "Topplista";

        $rounds = $roundRepository->getTopTenRounds();

        $this->data["rounds"] = [];
        foreach ($rounds as $round) {
            $player = $round->getPlayer();
            $board = $round->getBoard();
            $score = $round->getScore();
            $this->data["rounds"][] = [
                "player" => $player->getName(),
                "score" => $score->getTotal(),
                "duration" => $round->getDuration()->format('i:s'),
                "roundId" => $round->getId(),
            ];
        }

        return $this->render("proj/game/highscore.html.twig", $this->data);
    }



    #[Route("/proj/game/round/{id}", name: "proj_round", methods: ["GET"])]
    public function round(
        int $id,
        RoundRepository $roundRepository
    ): Response {
        $this->data["pageTitle"] = "Runda med id $id";

        $round = $roundRepository->find($id) ?? null;
        if (!$round) {
            $this->addFlash("warning", "Det finns ingen runda med id '$id'!");
            return $this->redirectToRoute("proj_highscore");
        }

        $player = $round->getPlayer();
        $board = $round->getBoard();
        $score = $round->getScore();

        $this->data = array_merge(
            $this->data,
            [
                "player" => $player->getName(),
                "board" => $board->getData(),
                "handScores" => $score->getHands(),
                "totalScore" => $score->getTotal(),
                "start" => $round->getStart()->format("Y-m-d H:i:s"),
                "finish" => $round->getFinish()->format("Y-m-d H:i:s"),
                "duration" => $round->getDuration()->format("i:s"),
            ]
        );


        return $this->render("proj/game/round.html.twig", $this->data);
    }



    #[Route("/proj/game/init", name: "proj_game_init_view", methods: ["GET"])]
    public function gameInitView(): Response
    {
        $this->data["pageTitle"] = "Hantera spelare";
        return $this->render("proj/game/init.html.twig", $this->data);
    }



    #[Route("/proj/game/init", name: "proj_game_init", methods: ["POST"])]
    public function gameInit(
        Request $request,
        PlayerRepository $playerRepository,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ): Response {
        $playerName = $request->request->get("player");

        // check if the player already exists
        $player = $playerRepository->findOneBy(['name' => $playerName]);
        if (!$player) {
            $player = new Player();
            $player->setName($playerName);
            $entityManager->persist($player);
            $entityManager->flush();
        }

        $game = new PokerSquaresGame(
            new PokerSquareRules(),
            new AmericanScores(),
            new Score(),
            new Gameboard(),
            $player,
            new CardDeck(CardSvg::class)
        );

        // FILL GAMEBOARD FOR TESTING
        $gb = new GameBoard();
        $slots = array_keys($gb->getBoardView());
        for ($i=0; $i < 24; $i++) { 
            $game->process($slots[$i]);
        }


        $session->set("game", $game);

        return $this->redirectToRoute("proj_singleplayer");
    }



    #[Route("/proj/game/singleplayer", name: "proj_singleplayer", methods: ["GET"])]
    public function singleplayer(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Singleplayer";
        $game = $session->get("game");
        $this->data = array_merge($this->data, $game->getState());

        if ($game->gameIsOver()) {
            $this->data["pageTitle"] = "Bra jobbat {$this->data['player']}!";
            return $this->render("proj/game/end_of_game.html.twig", $this->data);
        }

        return $this->render("proj/game/singleplayer.html.twig", $this->data);
    }



    #[Route("/proj/game/multiplayer", name: "proj_multiplayer", methods: ["GET"])]
    public function multiplayer(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Multiplayer";
        return $this->render("proj/game/multiplayer.html.twig", $this->data);
    }



    #[Route("/proj/game/place-card", name: "proj_place_card", methods: ["POST"])]
    public function placeCard(
        Request $request,
        SessionInterface $session
    ): Response {
        $slotId = $request->request->get("slot_id");
        $game = $session->get("game");
        $game->process($slotId);
        $session->set("game", $game);

        return $this->redirectToRoute("proj_singleplayer");
    }



    #[Route("/proj/game/save", name: "proj_game_save", methods: ["POST"])]
    public function saveGame(
        SessionInterface $session,
        PlayerRepository $playerRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $game = $session->get("game");
        $roundData = $game->getRoundData();

        // Player entity has been detached from entity manager,
        // and needs to be fetched again
        $playerId = $roundData["player"]->getId();
        $player = $playerRepository->find($playerId);

        $round = new Round();
        $round->setRoundData(
            $player,
            $roundData["board"],
            $roundData["score"],
            $roundData["start"],
            $roundData["finish"],
            $roundData["duration"]
        );

        $entityManager->persist($round);
        $entityManager->flush();

        $this->addFlash("notice", "Din runda sparades!");

        return $this->redirectToRoute("proj_highscore");
    }
}
