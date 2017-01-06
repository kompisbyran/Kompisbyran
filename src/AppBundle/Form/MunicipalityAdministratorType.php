<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MunicipalityAdministratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', ['label' => 'E-postadress'])
            ->add('plainPassword', 'password', ['label' => 'Lösenord'])
            ->add('firstName', 'text', ['label' => 'Förnamn'])
            ->add('lastName', 'text', ['label' => 'Efternamn'])
            ->add('adminMunicipalities', 'entity', [
                    'multiple' => true,
                    'class' => 'AppBundle:Municipality',
                    'property' => 'name',
                    'label' => 'Kommun',
                ]
            )
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $event->getData()->addRole('ROLE_MUNICIPALITY');
            $event->getData()->setEnabled(true);
        });
    }

    public function getName()
    {
        return 'municipalityAdministrator';
    }
}
