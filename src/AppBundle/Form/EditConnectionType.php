<?php

namespace AppBundle\Form;

use AppBundle\Enum\MeetingTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

class EditConnectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fluentSpeakerMarkedAsMetCreatedAt', DateTimeType::class, [
                'label' => 'Tid mötet bekräftat av etablerad',
                'required' => false,
            ])
            ->add('learnerMarkedAsMetCreatedAt', DateTimeType::class, [
                'label' => 'Tid mötet bekräftat av nyanländ',
                'required' => false,
            ])
            ->add('learnerMeetingStatus', ChoiceType::class, [
                'expanded' => true,
                'label' => 'Nyanländ mötesstatus',
                'choices' => array_flip(MeetingTypes::listTypesWithTranslationKeys()),
            ])
            ->add('fluentSpeakerMeetingStatus', ChoiceType::class, [
                'expanded' => true,
                'label' => 'Etablerads mötesstatus',
                'choices' => array_flip(MeetingTypes::listTypesWithTranslationKeys()),
            ])
        ;
    }

    public function getName()
    {
        return 'edit_connection';
    }
}
