<?php

namespace App\Controller\Project;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * test cases for MainController
 */
class MainControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    /**
     * set up client
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
    }



    /**
     * create and verify instance
     */
    public function testCreateInstance(): void
    {
        $mainController = new MainController();
        $this->assertInstanceOf(MainController::class, $mainController);
    }



    // /**
    //  * render landingpage and verify title
    //  */
    // public function testRenderStart(): void
    // {
    //     // do request
    //     $this->client->request("GET", "/proj");

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains("h1", "Poker Squares");
    // }



    // /**
    //  * render about page and verify title
    //  */
    // public function testRenderAbout(): void
    // {
    //     // do request
    //     $this->client->request("GET", "/proj/about");

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains("h1", "Om Projektet");
    // }



    // /**
    //  * render database page and verify title
    //  */
    // public function testRenderDatabase(): void
    // {
    //     // do request
    //     $this->client->request("GET", "/proj/about/database");

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains("h1", "Databasen");
    // }



    // /**
    //  * render game page and verify title
    //  */
    // public function testRenderGame(): void
    // {
    //     // do request
    //     $this->client->request("GET", "/proj/game");

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains("h1", "Poker Squares");
    // }



    // /**
    //  * render rules page and verify title
    //  */
    // public function testRenderRules(): void
    // {
    //     // do request
    //     $this->client->request("GET", "/proj/game/rules");

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains("h1", "Regler");
    // }



    // /**
    //  * render api page and verify title
    //  */
    // public function testRenderApi(): void
    // {
    //     // do request
    //     $this->client->request("GET", "/proj/api");

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains("h1", "API");
    // }
}
