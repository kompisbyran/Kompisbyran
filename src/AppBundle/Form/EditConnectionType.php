<?php

namespace AppBundle\Form;

use AppBundle\Enum\MeetingTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EditConnectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fluentSpeakerMeetingStatus', 'choice', [
                'expanded' => true,
                'label' => 'Etablerads mötesstatus',
                'choices' => MeetingTypes::listTypesWithTranslationKeys(),
            ])
            ->add('fluentSpeakerMarkedAsMetCreatedAt', 'datetime', [
                'label' => 'Tid mötet bekräftat av etablerad',
                'required' => false,
            ])
            ->add('learnerMeetingStatus', 'choice', [
                'expanded' => true,
                'label' => 'Nyanländ mötesstatus',
                'choices' => MeetingTypes::listTypesWithTranslationKeys(),
            ])
            ->add('learnerMarkedAsMetCreatedAt', 'datetime', [
                'label' => 'Tid mötet bekräftat av nyanländ',
                'required' => false,
            ])
        ;
    }

    public function getName()
    {
        return 'edit_connection';
    }
}
