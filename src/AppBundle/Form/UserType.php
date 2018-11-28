<?php

namespace AppBundle\Form;

use AppBundle\Entity\City;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\User;
use AppBundle\Enum\FriendTypes;
use AppBundle\Enum\OccupationTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
            ->add('firstName', 'text', ['label' => 'user.form.first_name'])
            ->add('lastName', 'text', ['label' => 'user.form.last_name'])
            ->add('wantToLearn', 'boolean_choice', [
                'expanded' => true,
                'label' => 'user.form.want_to_learn',
                'choices' => [
                    'user.form.want_to_learn.choice.learn'  => '1',
                    'user.form.want_to_learn.choice.teach'  => '0'
                ],
                'choices_as_values' => true,
                'data' => $user->hasRole('ROLE_COMPLETE_USER') ? $user->getWantToLearn() : null,
            ])
            ->add('categories', 'entity', [
                    'class' => 'AppBundle:Category',
                    'multiple' => true,
                    'expanded' => true,
                    'choice_list' => new ArrayChoiceList($categories),
                    'property' => 'name',
                    'label' => 'user.form.categories',
                ]
            )
            ->add('age', 'choice', [
                'label' => 'user.form.age',
                'empty_data'  => null,
                'required'    => false,
                'choices' => array_combine(range(18, 100), range(18, 100)),
            ])
            ->add('gender', 'choice', [
                'expanded' => true,
                'label' => 'user.form.gender',
                'choices' => [
                    'M' => 'user.form.gender.m',
                    'F' => 'user.form.gender.f',
                    'X' => 'user.form.gender.x',
                ]
            ])
            ->add('about', 'textarea', ['label' => 'user.form.about'])
            ->add('from', 'choice', [
                'label' => 'user.form.from',
                'choices' => Countries::getList(),
                'empty_data' => null,
                'empty_value' => ''
            ])
            ->add('hasChildren', 'boolean_choice', [
                'expanded' => true,
                'label' => 'user.form.has_children',
                'choices' => [
                    'no',
                    'yes',
                ],
                'data' => $user->hasRole('ROLE_COMPLETE_USER') ? $user->hasChildren() : null,
            ])
            ->add('profilePicture', 'hidden')
            ->add('municipality', 'entity', [
                    'class' => 'AppBundle:Municipality',
                    'property' => 'name',
                    'empty_data'  => null,
                    'required' => false,
                    'label' => 'user.form.municipality',
                ]
            )
            ->add('city', 'entity', [
                    'class' => City::class,
                    'property' => 'name',
                    'empty_data'  => null,
                    'required' => false,
                    'label' => 'user.form.city',
                ]
            )
            ->add('occupation', 'choice', [
                'label' => 'user.form.occupation',
                'choices' => OccupationTypes::listTypesWithTranslationKeys(),
                'empty_data' => null,
                'required' => false
            ])
            ->add('occupationDescription', 'textarea', [
                'label_attr' => ['id' => 'occupationDescriptionLabel'],
                'required' => false,
            ])
            ->add('education', 'boolean_choice', [
                'expanded' => true,
                'label' => 'user.form.has_education',
                'choices' => [
                    'no',
                    'yes'
                ],
                'data' => $user->hasRole('ROLE_COMPLETE_USER') ? $user->hasEducation() : null,
            ])
            ->add('educationDescription', 'textarea', [
                'label' => 'user.form.education_description',
                'required' => false,
            ])
            ->add('timeInSweden', 'textarea', [
                'label' => 'user.form.time_in_sweden',
            ])
            ->add('childrenAge', 'textarea', [
                'label' => 'user.form.children_age',
                'required' => false,
            ])
            ->add('phoneNumber', 'text', [
                'label' => 'user.form.phone_number',
                'required' => false,
                'attr' => ['placeholder' => '0701234567'],
            ])
            ->add('termsAccepted', 'checkbox', [
                'mapped' => false,
                'constraints' => $constraint,
                'validation_groups' => ['registration', 'Default']
            ])
            ->add('newlyArrived',  'boolean_choice', [
                'expanded' => true,
                'label' => 'user.form.newly_arrived',
                'choices' => [
                    'user.form.newly_arrived.no',
                    $options['translator']->trans('user.form.newly_arrived.yes', [
                        '%month%' => $options['translator']->trans('month.' . $options['newly_arrived_date']->getDate()->format('n'), [], 'months'),
                        '%year%' => $options['newly_arrived_date']->getDate()->format('Y'),
                    ]),
                ],
                'data' => $user->hasRole('ROLE_COMPLETE_USER') ? $user->isNewlyArrived() : null,
            ])
            ->add('identityNumber', 'text', [
                'label' => 'user.form.identity_number',
                'required' => false,
            ])
        ;

        if (!$user->hasRole('ROLE_COMPLETE_USER') || $options['add_connection_request']) {
            $builder->add('newConnectionRequest', 'connection_request', [
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
