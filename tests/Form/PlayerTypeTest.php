<?php

namespace App\Form;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Test cases for Player Form.
 */
class PlayerTypeTest extends TypeTestCase
{
    /**
     * preload form type
     */
    protected function getExtensions()
    {
        $formType = new PlayerType();

        return [
            new PreloadedExtension([$formType], []),
        ];
    }



    /**
     * submit default
     */
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Anonymous',
            'save' => null,
        ];
        $form = $this->factory->create(PlayerType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals("Anonymous", $form->get('name')->getData());
        $this->assertEquals(
            'Ange ett namn:',
            $form->get('name')->getConfig()->getOption('label')
        );
        $this->assertEquals(
            ['placeholder' => 'Ange ett spelarnamn'],
            $form->get('name')->getConfig()->getOption('attr')
        );
        $this->assertEquals(
            'Skapa',
            $form->get('save')->getConfig()->getOption('label')
        );
        $this->assertEquals(
            ['class' => 'button'],
            $form->get('save')->getConfig()->getOption('attr')
        );
    }



    /**
     * submit custom
     */
    public function testCustomSubmitLabel()
    {
        $form = $this->factory->create(
            PlayerType::class,
            null,
            [
                'name_label' => 'Choose a name:',
                'submit_label' => 'Create',
            ]
        );
        $this->assertEquals(
            'Choose a name:',
            $form->get('name')->getConfig()->getOption('label')
        );
        $this->assertEquals(
            'Create',
            $form->get('save')->getConfig()->getOption('label')
        );
    }
}
