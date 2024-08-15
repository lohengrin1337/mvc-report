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
     * @var InitCpuPlayerService $initService;
     */
    private InitCpuPlayerService $initService;

    /**
     * Constructor
     * Add entity manager and player repository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PlayerRepository $playerRepo,
        InitCpuPlayerService $initService
    ) {
        $this->entityManager = $entityManager;
        $this->playerRepo = $playerRepo;
        $this->initService = $initService;
    }


    /**
     * Reset database by removing all players and related rounds
     *
     * @return array response
     */
    public function reset(): array
    {
        // get all players
        $players = $this->playerRepo->findAll();

        try {
            $playerCount = count($players);
            $roundCount = 0;

            // remove each player, and related rounds by cascade
            foreach ($players as $player) {
                $roundCount += count($player->getRounds());
                $this->entityManager->remove($player);
            }

            $this->entityManager->flush();

            // recreate the cpu players
            $this->initService->addMissingPlayers();

            $response = [
                "message" => "$playerCount players and $roundCount rounds were " .
                "successfully removed, and cpu players were recreated"
            ];
        } catch (\Exception $e) {
            $response = [
                "message" => "Something went wrong",
                "error" => $e->getMessage(),
            ];
        }

        return $response;
    }
}
