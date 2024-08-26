<?php

namespace App\Controller\Project;

use DateTimeZone;
use App\Controller\Report\JsonResponseTrait;
use App\Form\ConfirmType;
use App\Repository\PlayerRepository;
use App\Repository\RoundRepository;
use App\Service\ResetDatabaseService;
use App\Service\SelectedPlayersService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for project API
 */
class ApiController extends AbstractController
{
    use JsonResponseTrait;

    private const LOCAL_TIME_ZONE = "Europe/Stockholm";

    #[Route("/proj/api/rounds", name: "proj_api_rounds", methods: ["GET"])]
    public function rounds(RoundRepository $roundRepo): JsonResponse
    {
        // get all rounds, ordered by date - latest first
        $rounds = $roundRepo->getLatestRounds();

        $data = array_map(function ($round) {
            return [
                "id" => $round->getId(),
                "player" => $round->getPlayer()?->getName(),
                "score" => $round->getScore()?->getTotal(),
                "board" => $round->getBoard()?->getData(),
                "start" => $round->getStart() // @phpstan-ignore-line
                    ?->setTimezone(new DateTimeZone(self::LOCAL_TIME_ZONE))
                    ->format("Y-m-d H:i"),
                "finish" => $round->getFinish() // @phpstan-ignore-line
                    ?->setTimezone(new DateTimeZone(self::LOCAL_TIME_ZONE))
                    ->format("Y-m-d H:i"),
                "duration" => $round->getDuration()
                    ?->format("H:i:s"),
            ];
        }, $rounds);

        // return json response with rounds (if any)
        $this->setResponse($data);
        return $this->response;
    }



    #[Route("/proj/api/highscore", name: "proj_api_highscore", methods: ["GET"])]
    public function highscore(RoundRepository $roundRepo): JsonResponse
    {
        // get top ten rounds
        $rounds = $roundRepo->getTopRounds(10);

        $data = array_map(function ($round) {
            return [
                "id" => $round->getId(),
                "player" => $round->getPlayer()?->getName(),
                "score" => $round->getScore()?->getTotal(),
                "board" => $round->getBoard()?->getData(),
                "start" => $round->getStart() // @phpstan-ignore-line
                    ?->setTimezone(new DateTimeZone(self::LOCAL_TIME_ZONE))
                    ->format("Y-m-d H:i"),
                "finish" => $round->getFinish() // @phpstan-ignore-line
                    ?->setTimezone(new DateTimeZone(self::LOCAL_TIME_ZONE))
                    ->format("Y-m-d H:i"),
                "duration" => $round->getDuration()
                    ?->format("H:i:s"),
            ];
        }, $rounds);

        // return json response with rounds (if any)
        $this->setResponse($data);
        return $this->response;
    }



    #[Route("/proj/api/players", name: "proj_api_players", methods: ["GET"])]
    public function players(PlayerRepository $playerRepo): JsonResponse
    {
        // get players from database
        $players = $playerRepo->getAllSortedByName();

        $data = array_map(function ($player) {
            return [
                "id" => $player->getId(),
                "name" => $player->getName(),
                "type" => $player->getType(),
                "level" => $player->getLevel(),
                "rounds" => count($player->getRounds()),
            ];
        }, $players);

        // return json response with players (if any)
        $this->setResponse($data);
        return $this->response;
    }



    #[Route("/proj/api/selected-players", name: "proj_api_selected_players", methods: ["GET"])]
    public function showSelectedPlayers(
        SelectedPlayersService $sps,
        SessionInterface $session,
        PlayerRepository $playerRepo
    ): JsonResponse {
        // get selected players from session that exists in db
        $players = $sps->getSelectedPlayers($session);

        $data = array_map(function ($player) {
            return [
                "id" => $player->getId(),
                "name" => $player->getName(),
                "type" => $player->getType(),
                "level" => $player->getLevel(),
                "rounds" => $player->getRounds()->count(),
            ];
        }, $players);

        // return json response with players (if any)
        $this->setResponse($data);
        return $this->response;
    }



    #[Route("/proj/api/game", name: "proj_api_game", methods: ["POST"])]
    public function showGameState(
        Request $request,
        SessionInterface $session
    ): JsonResponse {
        // get auth from request
        $auth = $this->createForm(ConfirmType::class)
            ->handleRequest($request)
            ->getData()["auth"] ?? null;

        // verify auth
        $response = ["error" => "Bad request - auth failed"];
        if ($auth === "p@ssw0rd") {
            // get current games from session
            $gameManager = $session->get("gameManager") ?? null;

            $response = [];
            if ($gameManager) {
                $response = $gameManager->getAllGameStates();
            }
        }

        // return json response with game states (if any)
        $this->setResponse($response);
        return $this->response;
    }



    #[Route("/proj/api/reset", name: "proj_api_reset", methods: ["POST"])]
    public function resetDatabase(
        Request $request,
        ResetDatabaseService $rds
    ): JsonResponse {
        // get auth from request
        $auth = $this->createForm(ConfirmType::class)
            ->handleRequest($request)
            ->getData()["auth"] ?? null;

        // verify auth
        $response = ["error" => "Bad request - auth failed"];
        if ($auth === "p@ssw0rd") {
            // remove players and rounds, and set response message
            $response = $rds->reset();
        }

        // return json response with message
        $this->setResponse($response);
        return $this->response;
    }
}
