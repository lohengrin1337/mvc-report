<?php

namespace App\Controller;

use App\Card\CardDeck;
use App\Card\CardHand;
use App\Card\CardGraphic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller class with routes for card game '21'
 */
class GameController extends AbstractController
{
    /**
     * @var array<string,mixed> $data - template data
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



    #[Route("/game", name: "game_start", methods: ["GET"])]
    public function gameStart(): Response
    {
        $this->data["pageTitle"] = "Kortspelet 21";

        return $this->render("game/start.html.twig", $this->data);
    }



    #[Route("/game/init", name: "game_init", methods: ["POST"])]
    public function gameInit(): Response
    {
        // init the game...
        // save to session


        return $this->redirectToRoute("game_play");
    }



    #[Route("/game/play", name: "game_play", methods: ["GET"])]
    public function gamePlay(): Response
    {
        $this->data["pageTitle"] = "Kortspelet 21 [SPELET RULLAR]";

        // get game from session if exists, else init

        return $this->render("game/play.html.twig", $this->data);
    }
}