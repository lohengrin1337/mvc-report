<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Makes sure that players stored in session are still existing
 */
class SelectedPlayersService
{
    /**
     * @var PlayerRepository $playerRepo;
     */
    private PlayerRepository $playerRepo;

    /**
     * Constructor
     * Add player repository
     */
    public function __construct(
        PlayerRepository $playerRepo,
    ) {
        $this->playerRepo = $playerRepo;
    }


    /**
     * Get all selected players from session,
     * and return those who are existing in database.
     * Update session
     *
     * @return Player[] players
     */
    public function getSelectedPlayers(SessionInterface $session): array
    {
        // get players from session
        $sessionPlayers = $session->get("players") ?? [];

        // get actual players from database
        $existingPlayers = array_filter(array_map(function ($player) {
            return $this->playerRepo->find($player->getId());
        }, $sessionPlayers));

        // update session
        $session->set("players", $existingPlayers);

        return $existingPlayers;
    }
}
