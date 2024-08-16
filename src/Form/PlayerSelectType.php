<?php

namespace App\Form;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerSelectType extends AbstractType
{
    private PlayerRepository $playerRepository;

    /**
     * Constructor
     */
    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $players = $this->playerRepository->getAllSortedByName();

        $builder
            ->add(
                'player',
                ChoiceType::class,
                [
                    'label' => 'Välj bland befintliga spelare',
                    'placeholder' => 'Välj en spelare',
                    'choices' => $players,
                    'choice_value' => 'name',
                    'choice_label' => function (Player $player): string|null {
                        return $player->getName();
                    },
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => $options['submit_label'],
                    'attr' => ['class' => 'button']
                ]
            )
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'form'],
            'submit_label' => 'Välj',
        ]);
    }
}
