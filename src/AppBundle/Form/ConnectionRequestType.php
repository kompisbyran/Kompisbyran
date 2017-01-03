<?php

namespace AppBundle\Form;

use AppBundle\Enum\ExtraPersonTypes;
use AppBundle\Enum\FriendTypes;
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
                            ->where('m.startMunicipality = true')
                            ->orderBy('m.name', 'ASC');
                    },
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => false,
                ]
            )
            ->add('comment', 'textarea', [
                'required' => false,
                'label' => 'connection_request.form.comment',
            ])
            ->add('type', 'choice', [
                'label' => 'global.music_buddy',
                'choices' => [
                    'user.form.fikatype.fikafriend' => FriendTypes::FRIEND,
                    'user.form.fikatype.musicfriend' => FriendTypes::MUSIC
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
                    true => 'yes',
                    false => 'no',
                ],
                'choice_value' => function ($currentChoiceKey) {
                    return $currentChoiceKey ? 'true' : 'false';
                },
            ])

            ->add('extraPersonGender', 'choice', [
                'label' => 'connection_request.form.extra_person_gender',
                'empty_data'  => null,
                'required'    => false,
                'choices' => [
                    'M' => 'user.form.gender.m',
                    'F' => 'user.form.gender.f',
                ]
            ])
            ->add('extraPersonType', 'choice', [
                'label' => 'connection_request.form.extra_person_type',
                'empty_data'  => null,
                'required'    => false,
                'choices' => ExtraPersonTypes::listTypesWithTranslationKeys(),
            ])
            ->add('wantSameGender', 'checkbox', [
                'required' => false,
                'label' => 'connection_request.form.want_same_gender',
            ])
            ->add('wantTwoPersons', 'checkbox', [
                'required' => false,
                'label' => 'connection_request.form.want_two_persons',
            ])
            ->add('wantSameAge', 'checkbox', [
                'required' => false,
                'label' => 'connection_request.form.want_same_age',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $event->getForm()->remove('type');
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ConnectionRequest',
        ]);
    }

    public function getName()
    {
        return 'connection_request';
    }
}
