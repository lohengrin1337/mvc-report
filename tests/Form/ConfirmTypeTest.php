<?php

namespace App\Form;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Test cases for Confirm Form.
 */
class ConfirmTypeTest extends TypeTestCase
{
    /**
     * preload form type
     *
     * @return PreloadedExtension[]
     */
    protected function getExtensions(): array
    {
        $type = new ConfirmType();

        return [
            new PreloadedExtension([$type], []),
        ];
    }


    /**
     * submit default
     */
    public function testSubmitValidData(): void
    {
        $formData = [];
        $form = $this->factory->create(ConfirmType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals("Bekräfta", $form->get("confirm")->getConfig()->getOption("label"));
        $this->assertEquals(
            ["class" => "button margin-b"],
            $form->get("confirm")->getConfig()->getOption("attr")
        );
    }



    /**
     * submit custom
     */
    public function testSubmitCustomOptions(): void
    {
        $formData = [];
        $customOptions = [
            "label" => "Confirm",
        ];

        $form = $this->factory->create(ConfirmType::class, null, $customOptions);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(
            "Confirm",
            $form->get("confirm")->getConfig()->getOption("label")
        );
    }
}
