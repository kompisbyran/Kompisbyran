<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wantToLearn', 'choice', [
                'expanded' => true,
                'label' => 'Vill du',
                'choices' => [
                    true => 'Förbättra din svenska',
                    false => 'Hjälpa någon att förbättra sin svenska',
                ]
            ])
            ->add('categories', 'entity', [
                    'class' => 'AppBundle:Category',
                    'multiple' => true,
                    'expanded' => true,
                    'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                        },
                    'property' => 'name',
                ]
            )
            ->add('age', 'number')
            ->add('gender', 'choice', [
                'expanded' => true,
                'label' => 'Kön',
                'choices' => [
                    'M' => 'Man',
                    'F' => 'Kvinna',
                ]
            ])
            ->add('about', 'textarea')
            ->add('from', 'text')
            ->add('languages', 'text')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }

    public function getName()
    {
        return 'user';
    }
}