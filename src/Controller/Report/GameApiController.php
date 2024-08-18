<?php

namespace App\Controller\Report;

use App\Card\CardDeck;
use App\Card\CardHand;
use App\Card\Card;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controller class for api routes
 */
class GameApiController extends AbstractController
{
    use JsonResponseTrait;

    #[Route("/api/game", name: "api_game", methods: ["GET"])]
    public function apiGame(SessionInterface $session): JsonResponse
    {
        $game = $session->get("game") ?? null;
        if (!$game) {
            $this->setResponse(["Error" => "No game in session"]);
            return $this->response;
        }

        $gameState = $game->getState();

        $data = [
            "deckCount" => $gameState["deckCount"],
            "playerSum" => $gameState["playerSum"],
            "bankSum" => $gameState["bankSum"],
            "gameOver" => $gameState["gameOver"],
            "winner" => $gameState["winner"],
        ];

        $this->setResponse($data);

        return $this->response;
    }
}
