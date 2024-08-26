<?php

namespace App\Controller\Project;

use App\Entity\Board;
use App\Entity\Player;
use App\Entity\Round;
use App\Entity\Score;
use App\Form\ConfirmDeleteType;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for routes about players
 */
class PlayerController extends AbstractController
{
    /**
     * @var array<mixed> $data - template data
     */
    private array $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [
            "siteTitle" => "MVC",
            "localTimeZone" => "Europe/Stockholm",
            "pageTitle" => "",
        ];
    }



    #[Route("/proj/game/player", name: "proj_show_players", methods: ["GET"])]
    public function showPlayers(PlayerRepository $playerRepository): Response
    {
        $players = $playerRepository->getAllSortedByName();

        $this->data["pageTitle"] = "Spelare";
        $this->data["players"] = $players;

        return $this->render("proj/game/player/all_players.html.twig", $this->data);
    }



    #[Route("/proj/game/player/{id}", name: "proj_show_player", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function showPlayer(
        int $id,
        PlayerRepository $playerRepository
    ): Response {
        $player = $playerRepository->find($id) ?? null;
        if (!$player) {
            $this->addFlash("warning", "Det finns ingen spelare med id '$id'!");
            return $this->redirectToRoute("proj_show_players");
        }

        $this->data["pageTitle"] = "Spelarprofil";
        $this->data["player"] = $player;

        return $this->render("proj/game/player/single_player.html.twig", $this->data);
    }



    #[Route("/proj/game/player/{id}/rounds", name: "proj_show_player_rounds", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function showPlayerRounds(
        int $id,
        PlayerRepository $playerRepository
    ): Response {
        $player = $playerRepository->find($id) ?? null;
        if (!$player) {
            $this->addFlash("warning", "Det finns ingen spelare med id '$id'!");
            return $this->redirectToRoute("proj_show_players");
        }

        $rounds = $player->getRounds();
        $this->data["pageTitle"] = "{$player->getName()}'s Rundor";
        $this->data["rounds"] = $rounds;
        return $this->render("proj/game/round/multiple_rounds.html.twig", $this->data);
    }




    #[Route("/proj/game/player/create", name: "proj_create_player", methods: ["GET", "POST"])]
    public function createPlayer(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(PlayerType::class, new Player());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $player = $form->getData();
                $entityManager->persist($player);
                $entityManager->flush();
                $this->addFlash("notice", "Spelaren '{$player->getName()}' har lagts till!");
                return $this->redirectToRoute("proj_show_players");
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash("warning", "Det finns redan en spelare med namn '{$player->getName()}'!");
                return $this->redirectToRoute("proj_create_player");
            } catch (\Exception $e) {
                $this->addFlash("warning", $e->getMessage());
                return $this->redirectToRoute("proj_create_player");
            }
        }

        $this->data["pageTitle"] = "Skapa ny spelare";
        $this->data["form"] = $form;

        return $this->render('proj/game/player/create_player.html.twig', $this->data);
    }



    #[Route("/proj/game/player/edit/{id}", name: "proj_edit_player", requirements: ["id" => "\d+"], methods: ["GET", "POST"])]
    public function editPlayer(
        int $id,
        PlayerRepository $playerRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $player = $playerRepository->find($id) ?? null;
        if (!$player) {
            $this->addFlash("warning", "Det finns ingen spelare med id '$id'!");
            return $this->redirectToRoute("proj_show_players");
        }

        $form = $this->createForm(
            PlayerType::class,
            $player,
            [
                "name_label" => "Namn:",
                "submit_label" => "Spara",
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $player = $form->getData();
                $entityManager->flush();
                $this->addFlash("notice", "Ändringarna är sparade!");
                return $this->redirectToRoute("proj_show_player", ['id' => $id]);
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash("warning", "Det finns en annan spelare med namnet '{$player->getName()}'!");
                return $this->redirectToRoute("proj_edit_player", ['id' => $id]);
            } catch (\Exception $e) {
                $this->addFlash("warning", $e->getMessage());
                return $this->redirectToRoute("proj_edit_player", ['id' => $id]);
            }
        }

        $this->data["pageTitle"] = "Redigera spelare";
        $this->data["form"] = $form;

        return $this->render('proj/game/player/edit_player.html.twig', $this->data);
    }



    #[Route("/proj/game/player/delete/{id}", name: "proj_delete_player", requirements: ["id" => "\d+"], methods: ["GET", "POST"])]
    public function deletePlayer(
        int $id,
        PlayerRepository $playerRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $player = $playerRepository->find($id) ?? null;
        if (!$player) {
            $this->addFlash("warning", "Det finns ingen spelare med id '$id'!");
            return $this->redirectToRoute("proj_show_players");
        }

        if ($player->getType() === "cpu") {
            $this->addFlash(
                "notice",
                "En dator-spelare kan inte raderas. Du kan däremot redigera dess namn, eller ta bort en runda."
            );
            $this->data["form"] = null;
        } else {
            $form = $this->createForm(ConfirmDeleteType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $playerName = $player->getName();
                    $roundCount = count($player->getRounds());
                    $entityManager->remove($player);
                    $entityManager->flush();
                    $this->addFlash(
                        "notice",
                        "Spelaren '$playerName' samt relaterade rundor ($roundCount) har tagits bort!"
                    );
                    return $this->redirectToRoute("proj_show_players");
                } catch (\Exception $e) {
                    $this->addFlash("warning", $e->getMessage());
                    return $this->redirectToRoute("proj_delete_player", ['id' => $id]);
                }
            }

            $this->data["form"] = $form;
        }

        $this->data["pageTitle"] = "Ta bort spelare";
        $this->data["player"] = $player;

        return $this->render('proj/game/player/delete_player.html.twig', $this->data);
    }
}
