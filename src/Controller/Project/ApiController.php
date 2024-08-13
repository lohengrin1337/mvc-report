<?php

namespace App\Controller\Project;

use \DateTimeZone;
use App\Controller\JsonResponseTrait;
use App\Repository\PlayerRepository;
use App\Repository\RoundRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for project API
 */
class ApiController extends AbstractController
{
    use JsonResponseTrait;

    private const LOCAL_TIME_ZONE = "Europe/Stockholm";

    #[Route("/proj/api/players", name: "proj_api_players", methods: ["GET"])]
    public function players(PlayerRepository $playerRepo): JsonResponse
    {
        $players = $playerRepo->getAllSortedByName();

        $data = array_map(function($player) {
            return [
                "id" => $player->getId(),
                "name" => $player->getName(),
                "type" => $player->getType(),
                "level" => $player->getLevel(),
                "rounds" => count($player->getRounds()),
            ];
        }, $players);

        $this->setResponse($data);

        return $this->response;
    }



    #[Route("/proj/api/highscore", name: "proj_api_highscore", methods: ["GET"])]
    public function highscore(RoundRepository $roundRepo): JsonResponse
    {
        // get top ten rounds
        $rounds = $roundRepo->getTopRounds(10);

        $data = array_map(function($round) {
            return [
                "id" => $round->getId(),
                "player" => $round->getPlayer()->getName(),
                "score" => $round->getScore()->getTotal(),
                "board" => $round->getBoard()->getData(),
                "start" => $round->getStart()
                    ->setTimezone(new DateTimeZone(self::LOCAL_TIME_ZONE))
                    ->format("Y-m-d H:i"),
                "finish" => $round->getFinish()
                    ->setTimezone(new DateTimeZone(self::LOCAL_TIME_ZONE))
                    ->format("Y-m-d H:i"),
                "duration" => $round->getDuration()
                    ->format("H:i:s"),
            ];
        }, $rounds);

        $this->setResponse($data);

        return $this->response;
    }



    #[Route("/proj/api/rounds", name: "proj_api_rounds", methods: ["GET"])]
    public function rounds(RoundRepository $roundRepo): JsonResponse
    {
        // get all rounds, ordered by date - latest first
        $rounds = $roundRepo->getLatestRounds();

        $data = array_map(function($round) {
            return [
                "id" => $round->getId(),
                "player" => $round->getPlayer()->getName(),
                "score" => $round->getScore()->getTotal(),
                "board" => $round->getBoard()->getData(),
                "start" => $round->getStart()
                    ->setTimezone(new DateTimeZone(self::LOCAL_TIME_ZONE))
                    ->format("Y-m-d H:i"),
                "finish" => $round->getFinish()
                    ->setTimezone(new DateTimeZone(self::LOCAL_TIME_ZONE))
                    ->format("Y-m-d H:i"),
                "duration" => $round->getDuration()
                    ->format("H:i:s"),
            ];
        }, $rounds);

        $this->setResponse($data);

        return $this->response;
    }
}
