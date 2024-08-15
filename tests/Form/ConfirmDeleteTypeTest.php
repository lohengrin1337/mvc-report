<?php

namespace App\Form;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Test cases for ConfirmDelete Form.
 */
class ConfirmDeleteTypeTest extends TypeTestCase
{
    /**
     * preload form type
     */
    protected function getExtensions()
    {
        $type = new ConfirmDeleteType();

        return [
            new PreloadedExtension([$type], []),
        ];
    }


    /**
     * submit default
     */
    public function testSubmitValidData()
    {
        $formData = [];
        $form = $this->factory->create(ConfirmDeleteType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals("Radera", $form->get("confirm")->getConfig()->getOption("label"));
        $this->assertEquals(
            ["class" => "button btn-delete"],
            $form->get("confirm")->getConfig()->getOption("attr")
        );
    }



    /**
     * submit custom
     */
    public function testSubmitCustomOptions()
    {
        $formData = [];
        $customOptions = [
            "label" => "Delete Permanently",
            "btn-attr" => ["class" => "button btn-danger"],
        ];

        $form = $this->factory->create(ConfirmDeleteType::class, null, $customOptions);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(
            "Delete Permanently",
            $form->get("confirm")->getConfig()->getOption("label")
        );
        $this->assertEquals(
            ["class" => "button btn-danger"],
            $form->get("confirm")->getConfig()->getOption("attr")
        );
    }
}
