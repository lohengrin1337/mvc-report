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



    /**
     * Modify $num to fit deck count if neccessary
     * Draw a number of cards from deck
     * Get string representation of the cards
     * Get deck count
     * set data["cardDraw"] and data["deckCount"]
     * 
     * @param CardDeck $deck - a CardDeck instance
     * @param int $num - a number of cards to draw
     */
    private function drawCards(CardDeck $deck, int $num = 1): void
    {
        if (!($num <= $deck->getCount())) {
            $num = $deck->getCount();
            $this->addFlash(
                'warning',
                "Det fanns {$num} kort kvar i leken!"
            );
        }

        $cardDraw = $deck->draw($num);
        $stringRepresentation = [];
        foreach ($cardDraw as $card) {
            $stringRepresentation[] = $card->getAsString();
        }

        $this->data["cardDraw"] = $stringRepresentation;
        $this->data["deckCount"] = $deck->getCount();
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
        $this->data["pageTitle"] = "Dra ett kort";

        $deck = $session->get("card_deck") ?? null;
        if ($deck) {
            $this->drawCards($deck);
            $session->set("card_deck", $deck);
        }

        return $this->render("card/deck_draw.html.twig", $this->data);
    }



    #[Route("/card/deck/draw/{num<\d+>}", name: "card_deck_draw_num", methods: ["GET"])]
    public function deckDrawNum(
        int $num,
        SessionInterface $session
        ): Response
    {
        $this->data["pageTitle"] = "Dra {$num} kort";

        $deck = $session->get("card_deck") ?? null;
        if ($deck) {
            $this->drawCards($deck, $num);
            $session->set("card_deck", $deck);
        }

        return $this->render("card/deck_draw.html.twig", $this->data);
    }
}
