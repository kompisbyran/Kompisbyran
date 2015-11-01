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
