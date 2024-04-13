<?php

namespace App\Controller;

use App\Dice\Dice;
use App\Dice\DiceGraphic;
use App\Dice\DiceHand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DiceGameController extends AbstractController
{
    /**
     * $var int FAIL_VALUE - the value you don't want to roll in 'pig'
     */
    public const FAIL_VALUE = 1;



    /**
     * @var array $data - data for templates
     */
    private array $data = [];



    /**
     * constructor
     */
    public function __construct()
    {
        $this->data = [
            "siteTitle" => "MVC",
            "pageTitle" => "",
        ];
    }



    #[Route("game/pig", name: "pig_start", methods: ["GET"])]
    public function home(): Response
    {
        $this->data["pageTitle"] = "Kasta Gris - Start";

        return $this->render("pig/home.html.twig", $this->data);
    }



    #[Route("game/pig/test/roll", name: "test_roll_dice", methods: ["GET"])]
    public function testRollDice(): Response
    {
        $this->data["pageTitle"] = "Kasta Gris - Testa tärning";

        $die = new DiceGraphic();
        $this->data["dice"][] = $die->roll();
        $this->data["diceStrings"][] = $die->getAsString();

        return $this->render("pig/test/roll.html.twig", $this->data);
    }



    #[Route("game/pig/test/roll/{num<\d+>}", name: "test_roll_multiple_dice", methods: ["GET"])]
    public function testRollMultipleDice(int $num): Response
    {
        $this->data["pageTitle"] = "Kasta Gris - Testa tärning";

        if ($num > 99) {
            throw new \Exception("Max antal tärningar är 99!");
        }

        $die = new DiceGraphic();
        $this->data["dice"] = [];
        $this->data["diceStrings"] = [];

        for ($i = 0; $i < $num; $i++) {
            $this->data["dice"][] = $die->roll();
            $this->data["diceStrings"][] = $die->getAsString();
        }

        return $this->render("pig/test/roll.html.twig", $this->data);
    }



    #[Route("game/pig/test/dicehand/{num<\d+>}", name: "test_dicehand", methods: ["GET"])]
    public function testDiceHand(int $num): Response
    {
        $this->data["pageTitle"] = "Kasta Gris - Testa tärningshand";

        if ($num > 99) {
            throw new \Exception("Max antal tärningar är 99!");
        }

        $hand = new DiceHand();
        for ($i = 0; $i < $num; $i++) {
            if ($i % 2 === 1) {
                $hand->add(new DiceGraphic());
            } else {
                $hand->add(new Dice());
            }
        }

        $hand->roll();

        $this->data["diceCount"] = $hand->getDiceCount();
        $this->data["diceStringValues"] = $hand->getStringValues();
        $this->data["diceSum"] = $hand->getSum();
        $this->data["diceAvg"] = $hand->getAvg();

        return $this->render("pig/test/dicehand.html.twig", $this->data);
    }



    #[Route("/game/pig/init", name: "pig_init_get", methods: ["GET"])]
    public function init(): Response
    {
        $this->data["pageTitle"] = "Kasta Gris [START]";

        return $this->render("pig/init.html.twig", $this->data);
    }



    #[Route("/game/pig/init", name: "pig_init_post", methods: ["POST"])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $diceAmount = $request->request->get("num_dice");

        $hand = new DiceHand();
        for ($i = 0; $i < $diceAmount; $i++) {
            $hand->add(new DiceGraphic());
        }

        $session->set("pig_dicehand", $hand);
        $session->set("pig_round", 0);
        $session->set("pig_total", 0);

        return $this->redirectToRoute("pig_play");
    }



    #[Route("/game/pig/play", name: "pig_play", methods: ["GET"])]
    public function play(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Kasta Gris [PLAY]";

        $dicehand = $session->get("pig_dicehand");

        $this->data["diceCount"] = $dicehand->getDiceCount();
        $this->data["diceValues"] = $dicehand->getStringValues();
        $this->data["roundSum"] = $session->get("pig_round");
        $this->data["totalSum"] = $session->get("pig_total");

        return $this->render("pig/play.html.twig", $this->data);
    }



    #[Route("/game/pig/roll", name: "pig_roll", methods: ["POST"])]
    public function roll(SessionInterface $session): Response
    {
        $dicehand = $session->get("pig_dicehand");
        $dicehand->roll();
        $diceValues = $dicehand->getValues();

        if (in_array(self::FAIL_VALUE, $diceValues)) {
            $session->set("pig_round", 0);

            // flash message
            $this->addFlash(
                'warning',
                'Du slog en 1:a, och förlorar därmed rundans poäng!'
            );
        } else {
            $currentRoundSum = $session->get("pig_round");
            $diceSum = $dicehand->getSum();
            $session->set("pig_round", $currentRoundSum + $diceSum);
        }

        $session->set("pig_dicehand", $dicehand);

        return $this->redirectToRoute("pig_play");
    }



    #[Route("/game/pig/save", name: "pig_save", methods: ["POST"])]
    public function save(SessionInterface $session): Response
    {
        // save new total score
        $session->set(
            "pig_total",
            $session->get("pig_total") +
            $session->get("pig_round")
        );

        // zero round score
        $session->set("pig_round", 0);

        // reset dicehand
        $dicehand = $session->get("pig_dicehand");
        $dicehand->reset();
        $session->set("pig_dicehand", $dicehand);

        // flash message
        $this->addFlash(
            'notice',
            'Rundan sparades till totalen!'
        );

        return $this->redirectToRoute("pig_play");
    }
}
