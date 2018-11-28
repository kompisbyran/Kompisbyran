<?php

namespace AppBundle\Form;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Enum\ExtraPersonTypes;
use AppBundle\Enum\FriendTypes;
use AppBundle\Enum\MatchingProfileRequestTypes;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * @FormType
 */
class ConnectionRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', 'entity', [
                    'label' => 'connection_request.form.city',
                    'class' => 'AppBundle:City',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                    },
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => false,
                ]
            )
            ->add('municipality', 'entity', [
                    'label' => 'connection_request.form.municipality',
                    'class' => 'AppBundle:Municipality',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->where('m.activeStartMunicipality = true')
                            ->orderBy('m.name', 'ASC');
                    },
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => false,
                ]
            )
            ->add('type', 'choice', [
                'label' => 'Typ',
                'choices' => [
                    'user.form.fikatype.fikafriend' => FriendTypes::FRIEND,
                    'Startkompis' => FriendTypes::START,
                ],
                'choices_as_values' => true,
            ])
            ->add('availableWeekday', 'checkbox', [
                'required' => false,
                'label' => 'connection_request.form.available.weekday',
            ])
            ->add('availableWeekend', 'checkbox', [
                'required' => false,
                'label' => 'connection_request.form.available.weekend',
            ])
            ->add('availableDay', 'checkbox', [
                'required' => false,
                'label' => 'connection_request.form.available.daytime',
            ])
            ->add('availableEvening', 'checkbox', [
                'required' => false,
                'label' => 'connection_request.form.available.evening',
            ])
            ->add('extraPerson', 'choice', [
                'expanded' => true,
                'label' => 'connection_request.form.extra_person',
                'choices' => [
                    false => 'no',
                    true => 'yes',
                ],
                'choice_value' => function ($currentChoiceKey) {
                    return $currentChoiceKey ? 'true' : 'false';
                },
            ])
            ->add('extraPersonGender', 'choice', [
                'label' => 'connection_request.form.extra_person_gender',
                'empty_data' => null,
                'required' => false,
                'choices' => [
                    'M' => 'user.form.gender.m',
                    'F' => 'user.form.gender.f',
                    'X' => 'user.form.gender.x',
                ]
            ])
            ->add('extraPersonType', 'choice', [
                'label' => 'connection_request.form.extra_person_type',
                'empty_data' => null,
                'required' => false,
                'choices' => ExtraPersonTypes::listTypesWithTranslationKeys(),
            ])
            ->add('matchingProfileRequestType', 'choice', [
                'label' => 'connection_request.form.matching_profile_request_type',
                'empty_data' => null,
                'empty_value' => 'connection_request.form.matching_profile_request_type.empty_value',
                'required' => false,
                'choices' => MatchingProfileRequestTypes::listTypesWithTranslationKeys(),
            ])
            ->add('wantToLearn', 'boolean_choice', [
                'expanded' => true,
                'label' => 'user.form.want_to_learn',
                'choices' => [
                    'user.form.want_to_learn.choice.learn'  => '1',
                    'user.form.want_to_learn.choice.teach'  => '0'
                ],
                'choices_as_values' => true,
            ])
            ->add('matchFamily', 'boolean_choice', [
                'expanded' => true,
                'label' => 'connection_request.form.match.family',
                'choices' => [
                    'no',
                    'yes',
                ],
            ])

        ;

        if ($options['remove_type']) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $event->getForm()->remove('type');
            });
        }
        if ($options['remove_want_to_learn']) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $event->getForm()->remove('wantToLearn');
            });
        }
        if ($options['remove_municipality']) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $event->getForm()->remove('municipality');
            });
        }
        if ($options['remove_city']) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $event->getForm()->remove('city');
            });
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ConnectionRequest',
            'remove_type' => false,
            'remove_want_to_learn' => false,
            'remove_municipality' => false,
            'remove_city' => false,
        ]);
    }

    public function getName()
    {
        return 'connection_request';
    }
}
