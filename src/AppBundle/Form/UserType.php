<?php

namespace AppBundle\Form;

use AppBundle\Entity\City;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\User;
use AppBundle\Enum\FriendTypes;
use AppBundle\Enum\OccupationTypes;
use Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Enum\Countries;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \Doctrine\Orm\EntityManager $manager */
        $manager = $options['manager'];
        $query = $manager->createQuery('SELECT c FROM AppBundle:Category c ORDER BY c.name');
        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        $query->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $options['locale']);

        $categories = [];
        foreach ($query->getResult() as $category) {
            $categories[] = $category;
        };
        $constraint = new IsTrue();
        $constraint->message = 'Du måste godkänna Kompisbyråns villkor.';

        /** @var User $user */
        $user = $builder->getData();

        $builder
            ->add('firstName', TextType::class, ['label' => 'user.form.first_name'])
            ->add('lastName', TextType::class, ['label' => 'user.form.last_name'])
            ->add('wantToLearn', ChoiceTypeBoolean::class, [
                'expanded' => true,
                'label' => 'user.form.want_to_learn',
                'choices' => [
                    'user.form.want_to_learn.choice.learn'  => '1',
                    'user.form.want_to_learn.choice.teach'  => '0'
                ],
                'choices_as_values' => true,
                'data' => $user->hasRole('ROLE_COMPLETE_USER') ? $user->getWantToLearn() : null,
            ])
            ->add('categories', EntityType::class, [
                    'class' => 'AppBundle:Category',
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => $categories,
                    'choice_label' => 'name',
                    'label' => 'user.form.categories',
                    'label_attr' => [
                        'class' => 'checkbox-inline',
                    ]
                ]
            )
            ->add('age', ChoiceType::class, [
                'label' => 'user.form.age',
                'empty_data'  => null,
                'required'    => false,
                'choices' => array_combine(range(18, 100), range(18, 100)),
            ])
            ->add('gender', ChoiceType::class, [
                'expanded' => true,
                'label' => 'user.form.gender',
                'choices' => [
                    'user.form.gender.m' => 'M',
                    'user.form.gender.f' => 'F',
                    'user.form.gender.x' => 'X',
                ]
            ])
            ->add('about', TextareaType::class, ['label' => 'user.form.about'])
            ->add('from', ChoiceType::class, [
                'label' => 'user.form.from',
                'choices' => array_flip(Countries::getList()),
                'empty_data' => null,
            ])
            ->add('hasChildren', ChoiceTypeBoolean::class, [
                'expanded' => true,
                'label' => 'user.form.has_children',
                'choices' => [
                    'no' => 0,
                    'yes' => 1,
                ],
                'data' => $user->hasRole('ROLE_COMPLETE_USER') ? $user->hasChildren() : null,
            ])
            ->add('profilePicture', HiddenType::class)
            ->add('municipality', EntityType::class, [
                    'class' => 'AppBundle:Municipality',
                    'choice_label' => 'name',
                    'empty_data'  => null,
                    'required' => false,
                    'label' => 'user.form.municipality',
                ]
            )
            ->add('city', EntityType::class, [
                    'class' => City::class,
                    'choice_label' => 'name',
                    'required' => false,
                    'label' => 'user.form.city',
                ]
            )
            ->add('occupation', ChoiceType::class, [
                'label' => 'user.form.occupation',
                'choices' => array_flip(OccupationTypes::listTypesWithTranslationKeys()),
                'empty_data' => null,
                'required' => false
            ])
            ->add('occupationDescription', TextareaType::class, [
                'label_attr' => ['id' => 'occupationDescriptionLabel'],
                'required' => false,
            ])
            ->add('education', ChoiceTypeBoolean::class, [
                'expanded' => true,
                'label' => 'user.form.has_education',
                'choices' => [
                    'no' => 0,
                    'yes' => 1,
                ],
                'data' => $user->hasRole('ROLE_COMPLETE_USER') ? $user->hasEducation() : null,
            ])
            ->add('educationDescription', TextareaType::class, [
                'label' => 'user.form.education_description',
                'required' => false,
            ])
            ->add('timeInSweden', TextType::class, [
                'label' => 'user.form.time_in_sweden',
            ])
            ->add('childrenAge', TextareaType::class, [
                'label' => 'user.form.children_age',
                'required' => false,
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'user.form.phone_number',
                'required' => false,
                'attr' => ['placeholder' => '0701234567'],
            ])
            ->add('termsAccepted', CheckboxType::class, [
                'mapped' => false,
                'constraints' => $constraint,
                'validation_groups' => ['registration', 'Default']
            ])
            ->add('newlyArrived',  ChoiceTypeBoolean::class, [
                'expanded' => true,
                'label' => 'user.form.newly_arrived',
                'choices' => [
                    'user.form.newly_arrived.no' => 0,
                    $options['translator']->trans('user.form.newly_arrived.yes', [
                        '%month%' => $options['translator']->trans('month.' . $options['newly_arrived_date']->getDate()->format('n'), [], 'months'),
                        '%year%' => $options['newly_arrived_date']->getDate()->format('Y'),
                    ]) => 1,
                ],
                'data' => $user->hasRole('ROLE_COMPLETE_USER') ? $user->isNewlyArrived() : null,
            ])
            ->add('atArbetsformedlingen',  ChoiceTypeBoolean::class, [
                'expanded' => true,
                'label' => 'user.form.at_arbetsformedlingen',
                'choices' => [
                    'no' => 0,
                    'yes' => 1,
                ],
                'data' => $user->hasRole('ROLE_COMPLETE_USER') ? $user->isAtArbetsformedlingen() : null,
            ])
            ->add('identityNumber', TextType::class, [
                'label' => 'user.form.identity_number',
                'required' => false,
            ])
        ;

        if (!$user->hasRole('ROLE_COMPLETE_USER') || $options['add_connection_request']) {
            $builder->add('newConnectionRequest', ConnectionRequestType::class, [
                'remove_type' => true,
                'remove_want_to_learn' => true,
                'remove_municipality' => true,
                'remove_city' => true,
            ]);

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var User $user */
                $user = $event->getData();
                $user->setNewConnectionRequest(new ConnectionRequest());
            });

            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                /** @var User $user */
                $user = $event->getForm()->getNormData();
                $connectionRequest = $user->getNewConnectionRequest();
                $connectionRequest->setType($user->getType());
                $connectionRequest->setWantToLearn($user->getWantToLearn());
                $connectionRequest->setCity($user->getCity());
                $connectionRequest->setMunicipality($user->getMunicipality());
            }, 1);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
            'add_connection_request' => false,
        ]);
        $resolver->setRequired([
            'manager',
            'locale',
            'translator',
            'newly_arrived_date',
        ]);
    }

    public function getName()
    {
        return 'user';
    }
}
