<?php

namespace App\Form;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class PlayerSelectTypeTest extends TypeTestCase
{
    private $playerRepository;

    protected function setUp(): void
    {
        // mock playerrepo
        $this->playerRepository = $this->createStub(PlayerRepository::class);
        parent::setUp();
    }



    /**
     * preload form type
     */
    protected function getExtensions()
    {
        $formType = new PlayerSelectType($this->playerRepository);

        return [
            new PreloadedExtension([$formType], []),
        ];
    }



    /**
     * submit default
     */
    public function testSubmitValidData()
    {
        // mock players and connect to repo
        $player1 = $this->createStub(Player::class);
        $player1->method("getName")->willReturn('John Doe');
        $player2 = $this->createStub(Player::class);
        $player2->method("getName")->willReturn('Jane Doe');
        $this->playerRepository->method("getAllSortedByName")->willReturn([$player1, $player2]);

        $formData = [
            'player' => 'John Doe',
            'save' => null,
        ];
        $form = $this->factory->create(PlayerSelectType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($player1, $form->get('player')->getData());
        $this->assertEquals(
            'Välj bland befintliga spelare',
            $form->get('player')->getConfig()->getOption('label')
        );
        $this->assertEquals(
            'Välj en spelare',
            $form->get('player')->getConfig()->getOption('placeholder')
        );
    }



    /**
     * submit custom
     */
    public function testCustomSubmitLabel()
    {
        $form = $this->factory->create(PlayerSelectType::class, null, ['submit_label' => 'Select Player']);
        $this->assertEquals('Select Player', $form->get('save')->getConfig()->getOption('label'));
    }
}
