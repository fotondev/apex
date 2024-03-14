<?php

namespace App\Form;

use App\Entity\Settings;
use App\Entity\Track;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SettingsType extends AbstractType
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $settings = $options['data'];
        $builder
            ->add('carGroup', ChoiceType::class, [
                'choices' => [
                    'Free for all' => 'FreeForAll',
                    'GT3' => 'gt3',
                    'GT4' => 'gt4',
                    'GTC' => 'gtc',
                    'TCX' => 'tcx'
                ],
                'data' => $settings->getCarGroup() ?? 'FreeForAll',
            ])
            ->add('maxCarSlots', IntegerType::class, ['data' => $settings->getMaxCarSlots() ?? 24])
            ->add('password', PasswordType::class, [
                'disabled' => true,
                'data' => $settings->getPassword() ?? ''
            ])
            ->add('overridePassword', CheckboxType::class, ['mapped' => false, 'required' => false]);

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($settings) {
                $form = $event->getForm();
                $data = $event->getData();
                $slotsValue = $data->getMaxCarSlots();
                $trackId = $form->getParent()->get('track')->getData();
                $track = $this->em->getRepository(Track::class)->find($trackId);

                if ($slotsValue > $track->getUniquePitboxes()) {
                    $form->get('maxCarSlots')->addError(new FormError('The value exceeds the maximum car slots.'));
                }
            });
//
//
//                $form->add('serverName', HiddenType::class, [
//                    'data' => $settings->getServerName() ?? '',
//                ]);
//                $form->add('adminPassword', HiddenType::class, [
//                    'data' => $settings->getAdminPassword() ?? '',
//                ]);
//                $form->add('trackMedalsRequirement', HiddenType::class, [
//                    'data' => $settings->getTrackMedalsRequirement() ?? '',
//                ]);
//                $form->add('safetyRatingRequirement', HiddenType::class, [
//                    'data' => $settings->getSafetyRatingRequirement() ?? '',
//                ]);
//                $form->add('racecraftRatingRequirement', HiddenType::class, [
//                    'data' => $settings->getRacecraftRatingRequirement() ?? '',
//                ]);
//                $form->add('spectatorPassword', HiddenType::class, [
//                    'data' => $settings->getSpectatorPassword() ?? '',
//                ]);
//                $form->add('dumpLeaderboards', HiddenType::class, [
//                    'data' => $settings->isDumpLeaderboards() ? '1' : '0',
//                ]);
//                $form->add('isRaceLocked', HiddenType::class, [
//                    'data' => $settings->isIsRaceLocked() ? '1' : '0',
//                ]);
//                $form->add('randomizeTrackWhenEmpty', HiddenType::class, [
//                    'data' => $settings->isRandomizeTrackWhenEmpty() ? '1' : '0',
//                ]);
//                $form->add('centralEntryListPath', HiddenType::class, [
//                    'data' => $settings->getCentralEntryListPath() ? '1' : '0',
//                ]);
//                $form->add('allowAutoDQ', HiddenType::class, [
//                    'data' => $settings->isAllowAutoDQ() ? '1' : '0',
//                ]);
//                $form->add('shortFormationLap', HiddenType::class, [
//                    'data' => $settings->isShortFormationLap() ? '1' : '0',
//                ]);
//                $form->add('dumpEntryList', HiddenType::class, [
//                    'data' => $settings->isDumpEntryList() ? '1' : '0',
//                ]);
//                $form->add('formationLapType', HiddenType::class, [
//                    'data' => $settings->getFormationLapType() ?? '',
//                ]);
//            }
//        );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
            'settings' => null,
        ]);
    }
}
