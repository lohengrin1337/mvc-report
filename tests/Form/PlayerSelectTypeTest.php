<?php

namespace App\Form;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test cases for Player Select Form.
 */
class PlayerSelectTypeTest extends TypeTestCase
{
    /** @var mixed */
    private $playerRepoStub;

    protected function setUp(): void
    {
        // mock playerrepo
        $this->playerRepoStub = $this->createStub(PlayerRepository::class);
        parent::setUp();
    }



    /**
     * preload form type
     *
     * @return PreloadedExtension[]
     */
    protected function getExtensions(): array
    {
        $formType = new PlayerSelectType($this->playerRepoStub);

        return [
            new PreloadedExtension([$formType], []),
        ];
    }



    /**
     * submit default, and verify player
     */
    public function testSubmitValidData(): void
    {
        // mock players and connect to repo
        $player1 = $this->createStub(Player::class);
        $player1->method("getName")->willReturn('John Doe');
        $player2 = $this->createStub(Player::class);
        $player2->method("getName")->willReturn('Jane Doe');
        $this->playerRepoStub->method("getAllSortedByName")
            ->willReturn([$player1, $player2]);

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
    public function testCustomSubmitLabel(): void
    {
        $form = $this->factory->create(PlayerSelectType::class, null, ['submit_label' => 'Select Player']);
        $this->assertEquals('Select Player', $form->get('save')->getConfig()->getOption('label'));
    }
}
