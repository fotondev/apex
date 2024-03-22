<?php

namespace App\Form;

use App\Entity\Driver;
use App\Enums\DriverCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $attributes = [
            'label_attr' => [
                'class' => 'col-sm-6 col-form-label'
            ],
            'attr' => [
                'class' => 'col-sm-5'
            ],
            'row_attr' => [
                'class' => 'form-group row mt-3',
            ],
            'required' => true,
        ];

        $builder
            ->add('firstName', TextType::class, $attributes)
            ->add('lastName', TextType::class, $attributes)
            ->add('shortName', TextType::class, $attributes)
            ->add('driverCategory', ChoiceType::class, array_merge($attributes, [
                    'choices' => [
                        'Bronze' => DriverCategory::Bronze->value,
                        'Silver' => DriverCategory::Silver->value,
                        'Gold' => DriverCategory::Gold->value,
                        'Platinum' => DriverCategory::Platinum->value
                    ]])
            )
            ->add('id', HiddenType::class, ['disabled' => true])
            ->add('playerId', IntegerType::class, $attributes);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Driver::class,
        ]);
    }
}
