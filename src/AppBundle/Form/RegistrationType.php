<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraint = new IsTrue();
        $constraint->message = 'Du måste godkänna Kompisbyråns villkor.';

        $builder
            ->remove('username')
            ->remove('email')
            ->add('email', 'repeated', array(
                'type' => 'text',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'register.form.email'),
                'second_options' => array('label' => 'register.form.email_confirmation'),
                'invalid_message' => 'fos_user.email.mismatch',
            ))
            ->add('termsAccepted', 'checkbox', [
                'mapped' => false,
                'constraints' => $constraint,
            ])
        ;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'fos_user_registration';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_user_registration';
    }
}
