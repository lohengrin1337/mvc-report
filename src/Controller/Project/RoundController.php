<?php

namespace App\Controller\Project;

use App\Entity\Board;
use App\Entity\Player;
use App\Entity\Round;
use App\Entity\Score;
use App\Form\ConfirmDeleteType;
use App\Repository\RoundRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for routes about rounds
 */
class RoundController extends AbstractController
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



    #[Route("/proj/game/highscore", name: "proj_highscore", methods: ["GET"])]
    public function highscore(RoundRepository $roundRepository): Response
    {
        $rounds = $roundRepository->getTopRounds(10);
        $this->data["pageTitle"] = "Topplista";
        $this->data["rounds"] = $rounds;

        return $this->render("proj/game/multiple_rounds.html.twig", $this->data);
    }



    #[Route("/proj/game/round", name: "proj_show_rounds", methods: ["GET"])]
    public function showRounds(RoundRepository $roundRepository): Response
    {
        $rounds = $roundRepository->getLatestRounds();

        $this->data["pageTitle"] = "Rundor";
        $this->data["rounds"] = $rounds;

        return $this->render("proj/game/multiple_rounds.html.twig", $this->data);
    }



    #[Route("/proj/game/round/{id}", name: "proj_show_round", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function showRound(
        int $id,
        RoundRepository $roundRepository
    ): Response {
        $this->data["pageTitle"] = "Runda med id $id";

        $round = $roundRepository->find($id) ?? null;
        if (!$round) {
            $this->addFlash("warning", "Det finns ingen runda med id '$id'!");
            return $this->redirectToRoute("proj_show_rounds");
        }

        $this->data["game"] = [
            "round" => $round,
            "board" => $round->getBoard()?->getData(),
            "handScores" => $round->getScore()?->getHands()
        ];

        return $this->render("proj/game/single_round.html.twig", $this->data);
    }



    #[Route("/proj/game/round/delete/{id}", name: "proj_delete_round", requirements: ["id" => "\d+"], methods: ["GET", "POST"])]
    public function deleteRound(
        int $id,
        roundRepository $roundRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $round = $roundRepository->find($id) ?? null;
        if (!$round) {
            $this->addFlash("warning", "Det finns ingen runda med id '$id'!");
            return $this->redirectToRoute("proj_show_rounds");
        }

        $form = $this->createForm(ConfirmDeleteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->remove($round);
                $entityManager->flush();
                $this->addFlash(
                    "notice",
                    "Runda med id '$id' har tagits bort!"
                );
                return $this->redirectToRoute("proj_show_rounds");
            } catch (\Exception $e) {
                $this->addFlash("warning", $e->getMessage());
                return $this->redirectToRoute("proj_delete_round", ['id' => $id]);
            }
        }

        $this->data["pageTitle"] = "Ta bort runda";
        $this->data["round"] = $round;
        $this->data["form"] = $form;

        return $this->render('proj/game/delete_round.html.twig', $this->data);
    }
}
