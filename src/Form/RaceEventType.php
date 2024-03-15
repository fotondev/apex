<?php

namespace App\Form;

use App\Entity\RaceEvent;
use App\Entity\Track;
use App\Form\Transformers\RaceEventTransformer;
use App\Utils\Race;
use App\Utils\WeatherScenarioFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;

class RaceEventType extends AbstractType
{

//    public function __construct(private readonly RaceEventTransformer $transformer)
//    {
//    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tracks = $options['tracks'];
        $raceEvent = $options['data'];

        $builder
            ->add('track', ChoiceType::class, [
                'choices' => $tracks
            ])
            ->add('preRaceWaitingTimeSeconds', IntegerType::class)
            ->add('sessionOverTimeSeconds', IntegerType::class)
            ->add('ambientTemp', IntegerType::class)
            ->add('weatherScenario', ChoiceType::class, [
                'mapped' => false,
                'choices' => WeatherScenarioFactory::getScenarios(),
            ])
            ->add('sessions', CollectionType::class, [
                'entry_type' => RaceSessionType::class,
                'label' => false,
                'entry_options' => [
                    'label' => false
                ]
            ])
            ->add('serverOptions', SettingsType::class, [
                'data' => $raceEvent->getSettings(),
                'label' => false,
                'mapped' => false,
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ]);
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $raceEvent = $event->getData();
                $form = $event->getForm();
                $scenarioId = $form->get('weatherScenario')->getData();
                $scenario = WeatherScenarioFactory::create($scenarioId);

                $raceEvent->setCloudLevel($scenario['cloudLevel']);
                $raceEvent->setRain($scenario['rain']);
                $raceEvent->setWeatherRandomness($scenario['weatherRandomness']);

                $raceEvent->setType(Race::RACE_WEEKEND);
            }

        );

//        $builder->addModelTransformer($this->transformer);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RaceEvent::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'tracks' => [],
            'weather_scenarios' => [],
            'settings' => null
        ]);
    }
}
