<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
