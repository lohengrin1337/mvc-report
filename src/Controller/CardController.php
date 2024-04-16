<?php

namespace App\Controller;

use App\Card\CardDeck;
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
            "cardDraw" => [],
            "allCards" => [],
            "deckCount" => [],
        ];
    }



    #[Route("/card", name: "card_start", methods: ["GET"])]
    public function start(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Kortlek";

        // init a new deck of cards, and save to session
        $session->set("card_deck", new CardDeck());

        return $this->render("card/start.html.twig", $this->data);
    }



    #[Route("/card/deck", name: "card_deck", methods: ["GET"])]
    public function deck(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Visa Kortlek";

        $deck = $session->get("card_deck") ?? null;
        if ($deck) {
            $deck->sort();
            $session->set("card_deck", $deck);
            $this->data["allCards"] = $deck->getAsString();
        }

        return $this->render("card/deck.html.twig", $this->data);
    }



    #[Route("/card/deck/shuffle", name: "card_deck_shuffle", methods: ["GET"])]
    public function deckShuffle(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Visa blandad Kortlek";

        $deck = $session->get("card_deck") ?? null;
        if ($deck) {
            $deck->shuffle();
            $session->set("card_deck", $deck);
            $this->data["allCards"] = $deck->getAsString();
        }

        return $this->render("card/deck_shuffle.html.twig", $this->data);
    }



    #[Route("/card/deck/draw", name: "card_deck_draw", methods: ["GET"])]
    public function deckDraw(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Dra kort";

        $deck = $session->get("card_deck") ?? null;
        if ($deck) {
            $cardDraw = $deck->draw();
            $stringRepresentation = [$cardDraw[0]->getAsString()];
            $this->data["cardDraw"] = $stringRepresentation;
            $this->data["deckCount"] = $deck->getCount();
            $session->set("card_deck", $deck);
        }

        return $this->render("card/deck_draw.html.twig", $this->data);
    }
}
