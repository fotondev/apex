<?php

namespace App\Form;


use App\Entity\RaceSession;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RaceSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hourOfDay')
            ->add('dayOfWeekend')
            ->add('timeMultiplier')
            ->add('sessionType', HiddenType::class)
            ->add('sessionDurationMinutes');

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RaceSession::class,
        ]);
    }
}
