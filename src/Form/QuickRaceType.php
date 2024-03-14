<?php

namespace App\Form;

use App\Entity\RaceEvent;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuickRaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('track', ChoiceType::class, [
                'choices' => $options['track_names'],
            ])
            ->add('carGroup', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'Free for all' => 'FreeForAll',
                    'GT3' => 'gt3',
                    'GT4' => 'gt4',
                    'GTC' => 'gtc',
                    'TCX' => 'tcx'
                ]
            ])
            ->add('qualyTime', IntegerType::class, ['mapped' => false])
            ->add('raceTime', IntegerType::class, ['mapped' => false])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RaceEvent::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'track_names' => [],
        ]);
    }
}
