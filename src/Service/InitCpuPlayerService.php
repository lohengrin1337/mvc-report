<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class InitCpuPlayerService
{
    /**
     * @var array CPU_PLAYERS - name and level
     */
    public const CPU_PLAYERS = [
        "CPU LÄTT" => 1,
        "CPU MEDEL" => 2,
        "CPU SVÅR" => 3,
    ];

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
    ) {
        $this->entityManager = $entityManager;
        $this->playerRepo = $playerRepo;
    }


    /**
     * Add cpu players to database if they are missing
     *
     * @return void
     */
    public function addMissingPlayers(): void
    {
        foreach (self::CPU_PLAYERS as $name => $level) {
            if (!$this->playerRepo->getPlayerByLevel($level)) {
                $cpuPlayer = new Player();
                $cpuPlayer->setName($name);
                $cpuPlayer->setType("cpu");
                $cpuPlayer->setLevel($level);
                $this->entityManager->persist($cpuPlayer);
            }
        }

        $this->entityManager->flush();
    }
}
