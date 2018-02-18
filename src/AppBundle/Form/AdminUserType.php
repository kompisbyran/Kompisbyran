<?php

namespace AppBundle\Form;

use AppBundle\Enum\RoleTypes;
use AppBundle\Security\Authorization\Voter\UserVoter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
            ->add('internalComment', 'textarea', [
                'label' => 'Intern kommentar',
                'required' => false,
            ])
            ->add('email', 'email')
            ->remove('termsAccepted')
        ;

        /** @var AuthorizationCheckerInterface $authorizationChecker */
        $authorizationChecker = $options['authorization_checker'];
        if ($authorizationChecker->isGranted(UserVoter::CHANGE_ROLES, $builder->getData())) {
            $builder->add('roles', 'choice', [
                'label' => 'Roller',
                'required' => false,
                'multiple' => true,
                'choices' => RoleTypes::listTypesWithTranslationKeys(),
            ]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            'authorization_checker',
        ]);

        parent::setDefaultOptions($resolver);
    }


    public function getName()
    {
        return 'admin_user';
    }
}
