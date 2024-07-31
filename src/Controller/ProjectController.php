<?php

namespace App\Controller;

use App\Card\CardDeck;
use App\Card\CardSvg;
use App\Entity\Board;
use App\Entity\Player;
use App\Entity\Round;
use App\Entity\Score;
use App\Form\ConfirmDeleteType;
use App\Form\ConfirmType;
use App\Form\PlayerSelectType;
use App\Form\PlayerType;
use App\PokerSquares\AmericanScores;
use App\PokerSquares\Gameboard;
use App\PokerSquares\GameManager;
use App\PokerSquares\PokerSquareRules;
use App\PokerSquares\PokerSquaresGame;
use App\Repository\BoardRepository;
use App\Repository\PlayerRepository;
use App\Repository\RoundRepository;
use App\Repository\ScoreRepository;
use App\Service\InitCpuPlayerService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
            "localTimeZone" => "Europe/Stockholm",
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
        $rounds = $roundRepository->getTopRounds(10);
        $this->data["pageTitle"] = "Topplista";
        $this->data["rounds"] = $rounds;

        return $this->render("proj/game/multiple_rounds.html.twig", $this->data);
    }



    #[Route("/proj/game/round", name: "proj_show_rounds", methods: ["GET"])]
    public function showRounds(RoundRepository $roundRepository): Response
    {
        $rounds = $roundRepository->getLatestRounds();

        $this->data["pageTitle"] = "Rundor";
        $this->data["rounds"] = $rounds;

        return $this->render("proj/game/multiple_rounds.html.twig", $this->data);
    }



    #[Route("/proj/game/round/{id}", name: "proj_show_round", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function showRound(
        int $id,
        RoundRepository $roundRepository
    ): Response {
        $this->data["pageTitle"] = "Runda med id $id";

        $round = $roundRepository->find($id) ?? null;
        if (!$round) {
            $this->addFlash("warning", "Det finns ingen runda med id '$id'!");
            return $this->redirectToRoute("proj_show_rounds");
        }

        $this->data["game"] = [
            "round" => $round,
            "board" => $round->getBoard()->getData(),
            "handScores" => $round->getScore()->getHands()
        ];

        return $this->render("proj/game/single_round.html.twig", $this->data);
    }



    #[Route("/proj/game/round/delete/{id}", name: "proj_delete_round", requirements: ["id" => "\d+"], methods: ["GET", "POST"])]
    public function deleteRound(
        int $id,
        roundRepository $roundRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $round = $roundRepository->find($id) ?? null;
        if (!$round) {
            $this->addFlash("warning", "Det finns ingen runda med id '$id'!");
            return $this->redirectToRoute("proj_show_rounds");
        }

        $form = $this->createForm(ConfirmDeleteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->remove($round);
                $entityManager->flush();
                $this->addFlash(
                    "notice",
                    "Runda med id '$id' har tagits bort!"
                );
                return $this->redirectToRoute("proj_show_rounds");
            } catch (Exception $e) {
                $this->addFlash("warning", $e->getMessage());
                return $this->redirectToRoute("proj_delete_round", ['id' => $id]);
            }
        }

        $this->data["pageTitle"] = "Ta bort runda";
        $this->data["round"] = $round;
        $this->data["form"] = $form;

        return $this->render('proj/game/delete_round.html.twig', $this->data);
    }



    #[Route("/proj/game/player", name: "proj_show_players", methods: ["GET"])]
    public function showPlayers(PlayerRepository $playerRepository): Response
    {
        $players = $playerRepository->getAllSortedByName();

        $this->data["pageTitle"] = "Spelare";
        $this->data["players"] = $players;

        return $this->render("proj/game/all_players.html.twig", $this->data);
    }



    #[Route("/proj/game/player/{id}", name: "proj_show_player", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function showPlayer(
        int $id,
        PlayerRepository $playerRepository
    ): Response {
        $player = $playerRepository->find($id) ?? null;
        if (!$player) {
            $this->addFlash("warning", "Det finns ingen spelare med id '$id'!");
            return $this->redirectToRoute("proj_show_players");
        }

        $this->data["pageTitle"] = "Spelarprofil";
        $this->data["player"] = $player;

        return $this->render("proj/game/single_player.html.twig", $this->data);
    }



    #[Route("/proj/game/player/{id}/rounds", name: "proj_show_player_rounds", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function showPlayerRounds(
        int $id,
        PlayerRepository $playerRepository
    ): Response {
        $player = $playerRepository->find($id) ?? null;
        if (!$player) {
            $this->addFlash("warning", "Det finns ingen spelare med id '$playerId'!");
            return $this->redirectToRoute("proj_show_players");
        }

        $rounds = $player->getRounds();
        $this->data["pageTitle"] = "{$player->getName()}s Rundor";
        $this->data["rounds"] = $rounds;
        return $this->render("proj/game/multiple_rounds.html.twig", $this->data);
    }




    #[Route("/proj/game/player/create", name: "proj_create_player", methods: ["GET", "POST"])]
    public function createPlayer(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(PlayerType::class, new Player());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $player = $form->getData();
                $entityManager->persist($player);
                $entityManager->flush();
                $this->addFlash("notice", "Spelaren '{$player->getName()}' har lagts till!");
                return $this->redirectToRoute("proj_show_players");
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash("warning", "Det finns redan en spelare med namn '{$player->getName()}'!");
                return $this->redirectToRoute("proj_create_player");
            } catch (Exception $e) {
                $this->addFlash("warning", $e->getMessage());
                return $this->redirectToRoute("proj_create_player");
            }
        }

        $this->data["pageTitle"] = "Skapa ny spelare";
        $this->data["form"] = $form;

        return $this->render('proj/game/create_player.html.twig', $this->data);
    }



    #[Route("/proj/game/player/edit/{id}", name: "proj_edit_player", requirements: ["id" => "\d+"], methods: ["GET", "POST"])]
    public function editPlayer(
        int $id,
        PlayerRepository $playerRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $player = $playerRepository->find($id) ?? null;
        if (!$player) {
            $this->addFlash("warning", "Det finns ingen spelare med id '$id'!");
            return $this->redirectToRoute("proj_show_players");
        }

        $form = $this->createForm(
            PlayerType::class,
            $player,
            [
                "name_label" => "Namn:",
                "submit_label" => "Spara",
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $player = $form->getData();
                $entityManager->flush();
                $this->addFlash("notice", "Ändringarna är sparade!");
                return $this->redirectToRoute("proj_show_player", ['id' => $id]);
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash("warning", "Det finns en annan spelare med namnet '{$player->getName()}'!");
                return $this->redirectToRoute("proj_edit_player", ['id' => $id]);
            } catch (Exception $e) {
                $this->addFlash("warning", $e->getMessage());
                return $this->redirectToRoute("proj_edit_player", ['id' => $id]);
            }
        }

        $this->data["pageTitle"] = "Redigera spelare";
        $this->data["form"] = $form;

        return $this->render('proj/game/edit_player.html.twig', $this->data);
    }



    #[Route("/proj/game/player/delete/{id}", name: "proj_delete_player", requirements: ["id" => "\d+"], methods: ["GET", "POST"])]
    public function deletePlayer(
        int $id,
        PlayerRepository $playerRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $player = $playerRepository->find($id) ?? null;
        if (!$player) {
            $this->addFlash("warning", "Det finns ingen spelare med id '$id'!");
            return $this->redirectToRoute("proj_show_players");
        }

        if ($player->getType() === "cpu") {
            $this->addFlash(
                "notice",
                "En dator-spelare kan inte raderas. Du kan däremot redigera dess namn, eller ta bort en runda."
            );
            $this->data["form"] = null;
        } else {
            $form = $this->createForm(ConfirmDeleteType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $playerName = $player->getName();
                    $roundCount = count($player->getRounds());
                    $entityManager->remove($player);
                    $entityManager->flush();
                    $this->addFlash(
                        "notice",
                        "Spelaren '$playerName' samt relaterade rundor ($roundCount) har tagits bort!"
                    );
                    return $this->redirectToRoute("proj_show_players");
                } catch (Exception $e) {
                    $this->addFlash("warning", $e->getMessage());
                    return $this->redirectToRoute("proj_delete_player", ['id' => $id]);
                }
            }

            $this->data["form"] = $form;
        }

        $this->data["pageTitle"] = "Ta bort spelare";
        $this->data["player"] = $player;

        return $this->render('proj/game/delete_player.html.twig', $this->data);
    }




    #[Route("/proj/game/init", name: "proj_game_init", methods: ["GET", "POST"])]
    public function gameInit(
        InitCpuPlayerService $icps,
        Request $request,
        PlayerRepository $playerRepository,
        EntityManagerInterface $entityManager,
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
            } catch (Exception $e) {
                $this->addFlash("warning", $e->getMessage());
            } finally {
                return $this->redirectToRoute("proj_game_init");
            }
        }

        // get players from session, and verify their existance
        $players = $session->get("players") ?? [];
        foreach ($players as $index => $player) {
            if (!$playerRepository->find($player->getId())) {
                unset($players[$index]);
            }
        }

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
            $gb = new GameBoard();
            $slots = array_keys($gb->getBoardView());
            for ($i=0; $i < 22; $i++) { 
                $game->process($slots[$i]);
            }

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
        $game->process($slotId);

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



    // #[Route("/proj/game/save", name: "proj_game_save", methods: ["POST"])]
    // public function saveGame(
    //     SessionInterface $session,
    //     PlayerRepository $playerRepository,
    //     EntityManagerInterface $entityManager
    // ): Response {
    //     $game = $session->get("game");
    //     $roundData = $game->getRoundData();

    //     // Player entity has been detached from entity manager,
    //     // and needs to be fetched again
    //     $playerId = $roundData["player"]->getId();
    //     $player = $playerRepository->find($playerId);

    //     $round = new Round();
    //     $round->setRoundData(
    //         $player,
    //         $roundData["board"],
    //         $roundData["score"],
    //         $roundData["start"],
    //         $roundData["finish"],
    //         $roundData["duration"]
    //     );

    //     $entityManager->persist($round);
    //     $entityManager->flush();

    //     $this->addFlash("notice", "Din runda sparades!");

    //     return $this->redirectToRoute("proj_highscore");
    // }





    // #[Route("/proj/game/singleplayer/init", name: "proj_singleplayer_init", methods: ["GET", "POST"])]
    // public function singleplayerInit(
    //     Request $request,
    //     PlayerRepository $playerRepository,
    //     EntityManagerInterface $entityManager,
    //     SessionInterface $session
    // ): Response {
    //     $player = null;

    //     $playerSelectForm = $this->createForm(PlayerSelectType::class, null, ["submit_label" => "Starta spelet"]);
    //     $playerSelectForm->handleRequest($request);
    //     if ($playerSelectForm->isSubmitted() && $playerSelectForm->isValid()) {
    //         $data = $playerSelectForm->getData();
    //         $player = $data["player"] ?? null;
    //     }

    //     $playerForm = $this->createForm(
    //         PlayerType::class,
    //         new Player(),
    //         [
    //             "name_label" => "Lägg till ny spelare:",
    //             "submit_label" => "Spara",
    //         ]
    //     );
    //     $playerForm->handleRequest($request);
    //     if ($playerForm->isSubmitted() && $playerForm->isValid()) {
    //         try {
    //             $player = $playerForm->getData();
    //             $entityManager->persist($player);
    //             $entityManager->flush();
    //             $this->addFlash("notice", "Spelaren '{$player->getName()}' har lagts till!");
    //         } catch (UniqueConstraintViolationException $e) {
    //             $this->addFlash("warning", "Det finns redan en spelare med namn '{$player->getName()}'!");
    //         } catch (Exception $e) {
    //             $this->addFlash("warning", $e->getMessage());
    //         } finally {
    //             return $this->redirectToRoute("proj_singleplayer_init");
    //         }
    //     }

    //     if ($player) {
    //         $game = new PokerSquaresGame(
    //             new PokerSquareRules(),
    //             new AmericanScores(),
    //             new Score(),
    //             new Gameboard(),
    //             $player,
    //             new CardDeck(CardSvg::class)
    //         );
    
    //         // FILL GAMEBOARD (24 cards) FOR TESTING
    //         $gb = new GameBoard();
    //         $slots = array_keys($gb->getBoardView());
    //         for ($i=0; $i < 24; $i++) { 
    //             $game->process($slots[$i]);
    //         }
    
    //         $session->set("game", $game);
    
    //         return $this->redirectToRoute("proj_singleplayer_play");
    //     }
    
    //     $this->data["pageTitle"] = "Välj spelare";
    //     $this->data["playerSelectForm"] = $playerSelectForm;
    //     $this->data["playerForm"] = $playerForm;
    //     return $this->render("proj/game/singleplayer_init.html.twig", $this->data);
    // }



    // #[Route("/proj/game/init", name: "proj_game_init_view", methods: ["GET"])]
    // public function gameInitView(): Response
    // {
    //     $this->data["pageTitle"] = "Hantera spelare";
    //     return $this->render("proj/game/init.html.twig", $this->data);
    // }



    // #[Route("/proj/game/init", name: "proj_game_init", methods: ["POST"])]
    // public function gameInit(
    //     Request $request,
    //     PlayerRepository $playerRepository,
    //     EntityManagerInterface $entityManager,
    //     SessionInterface $session
    // ): Response {
    //     $playerName = $request->request->get("player");

    //     // check if the player already exists
    //     $player = $playerRepository->findOneBy(['name' => $playerName]);
    //     if (!$player) {
    //         $player = new Player();
    //         $player->setName($playerName);
    //         $entityManager->persist($player);
    //         $entityManager->flush();
    //     }

    //     $game = new PokerSquaresGame(
    //         new PokerSquareRules(),
    //         new AmericanScores(),
    //         new Score(),
    //         new Gameboard(),
    //         $player,
    //         new CardDeck(CardSvg::class)
    //     );

    //     // FILL GAMEBOARD FOR TESTING
    //     $gb = new GameBoard();
    //     $slots = array_keys($gb->getBoardView());
    //     for ($i=0; $i < 24; $i++) { 
    //         $game->process($slots[$i]);
    //     }


    //     $session->set("game", $game);

    //     return $this->redirectToRoute("proj_singleplayer");
    // }



    // #[Route("/proj/game/singleplayer/play", name: "proj_singleplayer_play", methods: ["GET"])]
    // public function singleplayerPlay(SessionInterface $session): Response
    // {
    //     $this->data["pageTitle"] = "Singleplayer";
    //     $game = $session->get("game");
    //     $this->data = array_merge($this->data, $game->getState());

    //     if ($game->gameIsOver()) {
    //         $this->data["pageTitle"] = "Bra jobbat {$this->data['player']}!";
    //         return $this->render("proj/game/end_of_game.html.twig", $this->data);
    //     }

    //     return $this->render("proj/game/singleplayer.html.twig", $this->data);
    // }



    // #[Route("/proj/game/multiplayer", name: "proj_multiplayer", methods: ["GET"])]
    // public function multiplayer(SessionInterface $session): Response
    // {
    //     $this->data["pageTitle"] = "Multiplayer";
    //     return $this->render("proj/game/multiplayer.html.twig", $this->data);
    // }



    // #[Route("/proj/game/place-card", name: "proj_place_card", methods: ["POST"])]
    // public function placeCard(
    //     Request $request,
    //     SessionInterface $session
    // ): Response {
    //     $slotId = $request->request->get("slot_id");
    //     $game = $session->get("game");
    //     $game->process($slotId);
    //     $session->set("game", $game);

    //     return $this->redirectToRoute("proj_singleplayer_play");
    // }
}
