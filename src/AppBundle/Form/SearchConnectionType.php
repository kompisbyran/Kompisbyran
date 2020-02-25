<?php

namespace AppBundle\Form;

use AppBundle\Entity\City;
use AppBundle\Entity\Municipality;
use AppBundle\Enum\ConnectionMeetingVariantTypes;
use AppBundle\Enum\FriendTypes;
use AppBundle\Form\Model\SearchConnection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchConnectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('q', TextType::class, [
                'label' => 'Fritext',
                'required' => false,
            ])
            ->add('city', EntityType::class, [
                'label' => 'Stad',
                'class' => City::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('municipality', EntityType::class, [
                'label' => 'Kommun',
                'class' => Municipality::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('m')->where('m.startMunicipality = true')->orderBy('m.name', 'ASC');
                },
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('learnerHomeMunicipality', EntityType::class, [
                'label' => 'Övares hemkommun',
                'class' => Municipality::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('m')->where('m.startMunicipality = true')->orderBy('m.name', 'ASC');
                },
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('from', DateType::class, [
                'label' => 'Från',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('to', DateType::class, [
                'label' => 'Till',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('onlyNewlyArrived', CheckboxType::class, [
                'label' => 'Endast nyanlända',
                'required' => false,
            ])
            ->add('meetingStatus', ChoiceType::class, [
                'required' => false,
                'label' => 'Mötesstatus',
                'choices' => [
                    ConnectionMeetingVariantTypes::ONE_MARKED_AS_MET => 'Endast en har markerat som träffats',
                    ConnectionMeetingVariantTypes::BOTH_MARKED_AS_MET => 'Båda har markerat som träffats',
                ]
            ])
            ->add('type', ChoiceType::class, [
                'required' => false,
                'label' => 'Typ',
                'choices' => FriendTypes::listTypesWithTranslationKeys(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchConnection::class,
        ]);
    }

    public function getName()
    {
        return 'search_connection';
    }
}
