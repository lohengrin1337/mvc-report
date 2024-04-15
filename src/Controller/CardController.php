<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;



/**
 * Controller class with routes for card assignment
 */
class CardController extends AbstractController
{
    /**
     * @var array $data - template data
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
            "deck" => null,
        ];
    }



    #[Route("/card", name: "card_start", methods: ["GET"])]
    public function start(): Response
    {
        $this->data["pageTitle"] = "Kortlek";

        return $this->render("card/start.html.twig", $this->data);
    }



    #[Route("/card/deck", name: "card_deck", methods: ["GET"])]
    public function deck(): Response
    {
        $this->data["pageTitle"] = "Visa Kortlek";

        return $this->render("card/deck.html.twig", $this->data);
    }



    #[Route("/card/deck/shuffle", name: "card_deck_shuffle", methods: ["GET"])]
    public function deckShuffle(): Response
    {
        $this->data["pageTitle"] = "Visa blandad Kortlek";

        return $this->render("card/deck_shuffle.html.twig", $this->data);
    }



    #[Route("/card/deck/draw", name: "card_deck_draw", methods: ["GET"])]
    public function deckDraw(): Response
    {
        $this->data["pageTitle"] = "Dra kort";

        return $this->render("card/deck_draw.html.twig", $this->data);
    }
}
