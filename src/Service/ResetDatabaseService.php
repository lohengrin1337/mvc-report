<?php

namespace App\Service;

use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handle removal of all players and rounds
 */
class ResetDatabaseService
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var PlayerRepository $playerRepo;
     */
    private PlayerRepository $playerRepo;

    /**
     * Constructor
     * Add entity manager and player repository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PlayerRepository $playerRepo
    ){
        $this->entityManager = $entityManager;
        $this->playerRepo = $playerRepo;
    }


    /**
     * Reset database by removing all players and related rounds
     *
     * @return array response
     */
    public function reset(): array
    {
        // get all players
        $players = $playerRepo->findAll();

        if (!$players) {
            $response = ["message" => "There were no players to remove"];
        } else {
            try {
                $playerCount = $count($players);
                $roundCount = 0;

                // remove each player, and related rounds by cascade
                foreach ($players as $player) {
                    $roundCount += count($player->getRounds());
                    $em->remove($player);
                }

                $em->flush();

                $response = [
                    "message" => "$playerCount players, and $roundCount rounds were successfully removed"
                ];
            } catch (\Exception $e) {
                $response = [
                    "message" => "Something went wrong",
                    "error" => $e->getMessage(),
                ];
            }
        }

        return $response;
    }
}