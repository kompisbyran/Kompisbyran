<?php

namespace AppBundle\Form;

use AppBundle\Enum\FriendTypes;
use AppBundle\Enum\RoleTypes;
use AppBundle\Security\Authorization\Voter\UserVoter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use JMS\DiExtraBundle\Annotation\FormType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @FormType
 */
class AdminUserType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('internalComment', TextareaType::class, [
                'label' => 'Intern kommentar',
                'required' => false,
            ])
            ->add('email', EmailType::class)
            ->add('type', ChoiceType::class, [
                'label' => 'user.form.fikatype',
                'choices' => FriendTypes::listActiveTypesWithTranslationKeys(),
            ])
            ->remove('termsAccepted')
        ;

        /** @var AuthorizationCheckerInterface $authorizationChecker */
        $authorizationChecker = $options['authorization_checker'];
        if ($authorizationChecker->isGranted(UserVoter::CHANGE_ROLES, $builder->getData())) {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'Roller',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => array_flip(RoleTypes::listTypesWithTranslationKeys()),
            ]);
        }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'authorization_checker',
            'manager',
            'locale',
            'translator',
            'newly_arrived_date',
            'authorization_checker',
        ]);

        parent::configureOptions($resolver);
    }


    public function getName()
    {
        return 'admin_user';
    }
}
