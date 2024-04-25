<?php

namespace App\Controller;

use App\Card\CardDeck;
use App\Card\CardGame21;
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



    #[Route("/game/doc", name: "game_doc", methods: ["GET"])]
    public function gameDoc(): Response
    {
        $this->data["pageTitle"] = "Dokumentation";

        return $this->render("game/doc.html.twig", $this->data);
    }



    #[Route("/game/init", name: "game_init", methods: ["GET", "POST"])] // fine ??
    public function gameInit(SessionInterface $session): Response
    {
        $game = new CardGame21(
            new CardDeck(CardGraphic::class),   // deck
            new CardHand(),                     // player hand
            new CardHand()                      // bank hand
        );

        $session->set("game", $game);

        return $this->redirectToRoute("game_play");
    }



    #[Route("/game/play", name: "game_play", methods: ["GET"])]
    public function gamePlay(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Kortspelet 21 [SPEL PÅGÅR]";

        // get game from session if exists, else init
        $game = $session->get("game") ?? null;
        if (!$game) {
            return $this->redirectToRoute("game_init"); // working??
        }

        // get the state of the game
        $this->data = array_merge($this->data, $game->getState());

        if ($this->data["gameOver"]) {
            $this->data["pageTitle"] = "Kortspelet 21 [SPEL AVSLUTAT]";
            return $this->render("game/game_over.html.twig", $this->data);
        }

        if ($this->data["lastCardIsAce"]) {
            $this->data["pageTitle"] = "Kortspelet 21 [VÄLJ VÄRDE PÅ ESS]";
            $this->data["playerSum"] -= 1; // ace rank is 1 by default
            return $this->render("game/ace.html.twig", $this->data);
        }

        return $this->render("game/play.html.twig", $this->data);
    }



    #[Route("/game/play/draw", name: "game_draw", methods: ["POST"])]
    public function gameDraw(SessionInterface $session): Response
    {
        $game = $session->get("game") ?? null;
        if (!$game) {
            return $this->redirectToRoute("game_init");
        }

        $game->draw();
        $session->set("game", $game);

        return $this->redirectToRoute("game_play");
    }



    #[Route("/game/play/stop", name: "game_stop", methods: ["POST"])]
    public function gameStop(SessionInterface $session): Response
    {
        $game = $session->get("game") ?? null;
        if (!$game) {
            return $this->redirectToRoute("game_init");
        }

        $game->playBank();
        $session->set("game", $game);

        return $this->redirectToRoute("game_play");
    }



    #[Route("/game/play/ace", name: "game_set_ace", methods: ["POST"])]
    public function gameSetAce(
        Request $request,
        SessionInterface $session
    ): Response
    {
        $aceRank = $request->request->get("ace") ?? null;
        $game = $session->get("game") ?? null;
        if (!$game || !$aceRank) {
            return $this->redirectToRoute("game_init");
        }

        $game->setAceRank((int) $aceRank);
        $session->set("game", $game);

        return $this->redirectToRoute("game_play");
    }
}