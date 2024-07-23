<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmDeleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            "confirm",
            SubmitType::class,
            [
                "label" => $options["label"],
                "attr" => $options["btn-attr"],
                // "attr" => ["class" => "button"],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "label" => "Radera",
            "btn-attr" => ["class" => "button btn-delete"],
        ]);
    }
}
