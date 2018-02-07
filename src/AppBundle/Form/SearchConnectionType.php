<?php

namespace AppBundle\Form;

use AppBundle\Entity\City;
use AppBundle\Form\Model\SearchConnection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchConnectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('q', 'text', [
                'label' => 'Fritext',
                'required' => false,
            ])
            ->add('city', 'entity', [
                'label' => 'Stad',
                'class' => City::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'property' => 'name',
                'empty_value' => '',
                'required' => false,
            ])
            ->add('from', 'date', [
                'label' => 'Från',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('to', 'date', [
                'label' => 'Till',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('onlyNewlyArrived', 'checkbox', [
                'label' => 'Endast nyanlända',
                'required' => false,
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
