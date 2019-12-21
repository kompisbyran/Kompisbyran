<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MunicipalityAdministratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'E-postadress'])
            ->add('plainPassword', PasswordType::class, ['label' => 'Lösenord'])
            ->add('firstName', TextType::class, ['label' => 'Förnamn'])
            ->add('lastName', TextType::class, ['label' => 'Efternamn'])
            ->add('adminMunicipalities', EntityType::class, [
                    'multiple' => true,
                    'class' => 'AppBundle:Municipality',
                    'choice_label' => 'name',
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
