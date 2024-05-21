<?php

// namespace App\Controller;

// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// /**
//  * Controller for poker squares - routes of project
//  */
// class PokerSquaresController extends AbstractController
// {
//     /**
//      * @var array<mixed> $data - template data
//      */
//     private $data;

//     /**
//      * Constructor
//      */
//     public function __construct()
//     {
//         $this->data = [
//             "siteTitle" => "MVC",
//             "pageTitle" => "",
//         ];
//     }



//     #[Route("/proj/game", name: "proj_game_start", methods: ["GET"])]
//     public function gameStart(): Response
//     {
//         $this->data["pageTitle"] = "Poker Squares";
//         return $this->render("proj/game_start.html.twig", $this->data);
//     }



//     #[Route("/proj/game/rules", name: "proj_rules", methods: ["GET"])]
//     public function gameRules(): Response
//     {
//         $this->data["pageTitle"] = "Regler";
//         return $this->render("proj/rules.html.twig", $this->data);
//     }



//     #[Route("/proj/game/highscore", name: "proj_highscore", methods: ["GET"])]
//     public function highscore(): Response
//     {
//         $this->data["pageTitle"] = "Topplista";
//         return $this->render("proj/highscore.html.twig", $this->data);
//     }
// }
