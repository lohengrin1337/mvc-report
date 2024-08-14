<?php

namespace App\Service;

use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * Makes sure that players stored in session are still existing
 */
class SelectedPlayersService
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var PlayerRepository $playerRepo;
     */
    private PlayerRepository $playerRepo;

    // /**
    //  * @var SessionInterface $session;
    //  */
    // private SessionInterface $session;

    /**
     * Constructor
     * Add entity manager, player repository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PlayerRepository $playerRepo,
        // SessionInterface $session
    ){
        $this->entityManager = $entityManager;
        $this->playerRepo = $playerRepo;
        // $this->session = $session;
    }


    /**
     * Get all selected players from session that are existing in database
     *
     * @return array players
     */
    public function getSelectedPlayers(SessionInterface $session): array
    {
        // get players from session
        $players = $session->get("players") ?? [];

        foreach ($players as $index => $player) {
            // verify their existance
            if (!$this->playerRepo->find($player->getId())) {
                // remove player
                unset($players[$index]);
            }
            // update session
            $session->set("players", $players);
        }

        return $players;
    }
}