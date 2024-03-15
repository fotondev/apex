<?php

namespace App\Form;

use App\Entity\RaceEvent;
use App\Utils\Race;
use App\Utils\WeatherScenarioFactory;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class QuickRaceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tracks = $options['tracks'];
        $raceEvent = $options['data'];

        $timeset = [];

        foreach ($raceEvent->getSessions() as $session) {
            match ($session->getSessionType()) {
                'Q' => $timeset['qualyTime'] = $session->getSessionDurationMinutes(),
                'R' => $timeset['raceTime'] = $session->getSessionDurationMinutes(),
                default => null,
            };
        }

        $builder
            ->add('track', ChoiceType::class, [
                'choices' => $tracks,
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
            ->add('qualyTime', IntegerType::class, [
                'mapped' => false,
                'data' => $timeset['qualyTime'],
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 1, 'max' => 1440])
                ],

            ])
            ->add('raceTime', IntegerType::class, [
                'mapped' => false,
                'data' => $timeset['raceTime'],
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 1, 'max' => 1440])
                ],
            ])
            ->add('start', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-danger mt-3'
                ]
            ]);

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $raceEvent = $event->getData();
                $form = $event->getForm();
                $scenarioId = rand(1, 7);
                $scenario = WeatherScenarioFactory::create($scenarioId);

                $raceEvent->setCloudLevel($scenario['cloudLevel']);
                $raceEvent->setRain($scenario['rain']);
                $raceEvent->setWeatherRandomness($scenario['weatherRandomness']);


                foreach ($raceEvent->getSessions() as $session) {
                    match ($session->getSessionType()) {
                        'Q' => $session->setSessionDurationMinutes($form['qualyTime']->getData()),
                        'R' => $session->setSessionDurationMinutes($form['raceTime']->getData()),
                        default => null,
                    };
                }

                $raceEvent->setType(Race::QUICK_RACE);
            }

        );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RaceEvent::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'track_names' => [],
            'tracks' => null
        ]);
    }
}
