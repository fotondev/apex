<?php

namespace App\Form;

use App\Entity\Entry;
use App\Entity\RaceEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('raceNumber', IntegerType::class, [
                'label_attr' => [
                    'class' => 'col-sm-6 col-form-label'
                ],
                'attr' => [
                    'class' => 'col-sm-5'
                ],
                'row_attr' => [
                    'class' => 'form-group row mt-3',
                ],
            ])
            ->add('id', HiddenType::class, ['disabled' => true])
            ->add('drivers', CollectionType::class, [
                'entry_type' => DriverType::class,
                'label' => false,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entry::class,
            'label' => false,
            'allow_extra_fields' => true
        ]);
    }

}
