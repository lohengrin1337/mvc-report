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
     * @var array $data
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

        // $die = new Dice();
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

        // $die = new Dice();
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
                $hand->add(new DiceGraphic);
            } else {
                $hand->add(new Dice);
            }
        }

        $hand->roll();

        $this->data["diceCount"] = $hand->getDiceCount();
        $this->data["diceStringValues"] = $hand->getStringValues();
        $this->data["diceSum"] = $hand->getSum();
        $this->data["diceAvg"] = $hand->getAvg();

        // $this->data["dicehand"] = $hand;

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
    ): Response
    {
        $diceAmount = $request->request->get("num_dice");

        $hand = new DiceHand();
        for ($i = 0; $i < $diceAmount; $i++) {
            $hand->add(new DiceGraphic);
        }

        $hand->roll();

        $session->set("pig_dicehand", $hand);
        $session->set("pig_dice_amount", $diceAmount);
        $session->set("pig_round", 0);
        $session->set("pig_total", 0);

        return $this->redirectToRoute("pig_play");
    }



    #[Route("/game/pig/play", name: "pig_play", methods: ["GET"])]
    public function play(SessionInterface $session): Response
    {
        $this->data["pageTitle"] = "Kasta Gris [PLAY]";
        $this->data["diceAmount"] = $session->get("pig_dice_amount");
        $this->data["roundSum"] = $session->get("pig_round");
        $this->data["totalSum"] = $session->get("pig_total");

        return $this->render("pig/play.html.twig", $this->data);

    }



    #[Route("/game/pig/roll", name: "pig_roll", methods: ["POST"])]
    public function roll(): Response
    {
        // deal with roll data

        return $this->render("pig/play.html.twig", $this->data);

    }



    #[Route("/game/pig/save", name: "pig_save", methods: ["POST"])]
    public function save(): Response
    {
        // deal with save data

        return $this->render("pig/play.html.twig", $this->data);

    }
}