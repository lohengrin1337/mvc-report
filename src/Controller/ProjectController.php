<?php

namespace App\Controller;

use App\Card\CardDeck;
// use App\PokerSquares\PokerSquares;
use App\Card\CardHand;
use App\Card\CardGraphic;
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
    public function highscore(): Response
    {
        $this->data["pageTitle"] = "Topplista";
        return $this->render("proj/game/highscore.html.twig", $this->data);
    }



    // #[Route("/proj/game/init", name: "proj_game_init_view", methods: ["GET"])]
    // public function gameInitView(): Response
    // {
    //     $this->data["pageTitle"] = "Välj speltyp";
    //     return $this->render("proj/game/init.html.twig", $this->data);
    // }



    #[Route("/proj/game/test-init", name: "proj_test_init", methods: ["GET"])]
    public function testInit(SessionInterface $session): Response
    {
        $gameboard = []; // new Gameboard()
        $deck = new CardDeck(CardGraphic::class);
        $card = $deck->draw();
        $session->set("gameboard", $gameboard);
        $session->set("deck", $deck);
        $session->set("card", $card);

        return $this->redirectToRoute("proj_singleplayer");
    }



    #[Route("/proj/game/singleplayer", name: "proj_singleplayer", methods: ["GET"])]
    public function singleplayer(SessionInterface $session): Response
    {
        $gameboard = $session->get("gameboard");
        $deck = $session->get("deck");
        $card = $session->get("card");

        $this->data["pageTitle"] = "Singleplayer";
        $this->data["cardBack"] = $deck->getCardBack();
        $this->data["card"] = $card->getAsString();
        $this->data["gameboard"] = $gameboard; // $gameboard->getState()

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
        $gameboard = $session->get("gameboard");
        $deck = $session->get("deck");
        $card = $session->get("card");

        $gameboard[$slotId] = $card->getAsString(); // $gameboard->placeCard($card, slotId)

        $card = $deck->draw();
        $session->set("gameboard", $gameboard);
        $session->set("deck", $deck);
        $session->set("card", $card);

        return $this->redirectToRoute("proj_singleplayer");
    }
}
