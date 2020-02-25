<?php

namespace AppBundle\Form;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Enum\FriendTypes;
use AppBundle\Enum\MatchingProfileRequestTypes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use JMS\DiExtraBundle\Annotation\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @FormType
 */
class ConnectionRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', EntityType::class, [
                    'label' => 'connection_request.form.city',
                    'class' => 'AppBundle:City',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                    },
                    'choice_label' => 'name',
                    'required' => false,
                ]
            )
            ->add('municipality', EntityType::class, [
                    'label' => 'connection_request.form.municipality',
                    'class' => 'AppBundle:Municipality',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->where('m.activeStartMunicipality = true')
                            ->orderBy('m.name', 'ASC');
                    },
                    'choice_label' => 'name',
                    'required' => false,
                ]
            )
            ->add('type', ChoiceType::class, [
                'label' => 'Typ',
                'choices' => [
                    'user.form.fikatype.fikafriend' => FriendTypes::FRIEND,
                    'Startkompis' => FriendTypes::START,
                ],
                'choices_as_values' => true,
            ])
            ->add('matchingProfileRequestType', ChoiceType::class, [
                'label' => 'connection_request.form.matching_profile_request_type',
                //'empty_data' => null,
                'placeholder' => 'connection_request.form.matching_profile_request_type.empty_value',
                'required' => false,
                'choices' => array_flip(MatchingProfileRequestTypes::listTypesWithTranslationKeys()),
            ])
            ->add('wantToLearn', ChoiceTypeBoolean::class, [
                'expanded' => true,
                'label' => 'user.form.want_to_learn',
                'choices' => [
                    'user.form.want_to_learn.choice.learn'  => '1',
                    'user.form.want_to_learn.choice.teach'  => '0'
                ],
                'choices_as_values' => true,
            ])
            ->add('matchFamily', ChoiceTypeBoolean::class, [
                'expanded' => true,
                'label' => 'connection_request.form.match.family',
                'choices' => [
                    'connection_request.form.match.family.no',
                    'connection_request.form.match.family.yes',
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConnectionRequest::class,
            'remove_type' => false,
            'remove_want_to_learn' => false,
            'remove_municipality' => false,
            'remove_city' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'connection_request';
    }
}
