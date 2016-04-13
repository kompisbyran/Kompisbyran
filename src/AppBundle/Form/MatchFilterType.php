<?php

namespace AppBundle\Form;

use JMS\DiExtraBundle\Annotation\FormType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use AppBundle\Enum\Countries;
use AppBundle\Entity\User;
use AppBundle\Manager\CategoryManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * @FormType
 */
class MatchFilterType extends AbstractType
{
    /**
     * @var CategoryManager
     */
    private $categoryManager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @InjectParams({
     *     "categoryManager" = @Inject("category_manager"),
     *     "requestStack" = @Inject("request_stack")
     * })
     */
    public function __construct(CategoryManager $categoryManager, RequestStack $requestStack)
    {
        $this->categoryManager  = $categoryManager;
        $this->requestStack     = $requestStack;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $age = range(18, 100);
        $builder
            ->add('category_id', 'entity', [
                'class'         => 'AppBundle:Category',
                'property'      => 'name',
                'label'         => 'filter.form.interests',
                'empty_data'    => '',
                'empty_value'   => 'All',
                'choices'       => $this->categoryManager->getFindAllByLocale($this->requestStack->getCurrentRequest()->getLocale())
            ])
            ->add('ageFrom', 'choice', [
                'label'         => 'filter.form.age_from',
                'data'          => 18,
                'choices'       => array_combine($age, $age)
            ])
            ->add('ageTo', 'choice', [
                'label'         => 'filter.form.age_to',
                'data'          => 100,
                'choices'       => array_combine($age, $age)
            ])
            ->add('gender', 'choice', [
                'label'         => 'filter.form.gender',
                'choices'       => User::getGenders(),
                'empty_data'    => '',
                'empty_value'   => 'All'
            ])
            ->add('has_children', 'boolean_choice', [
                'label'             => 'filter.form.has_children',
                'choices_as_values' => true,
                'empty_data'        => '',
                'empty_value'       => 'Doesn\'t matter',
                'choices'           => [
                    'no'            => '0',
                    'yes'           => '1'
                ]
            ])
            ->add('from', 'choice', [
                'label'         => 'filter.form.from',
                'choices'       => Countries::getList(),
                'empty_data'    => '',
                'empty_value'   => 'All'
            ])
            ->add('municipality_id', 'entity', [
                'class'         => 'AppBundle:Municipality',
                'property'      => 'name',
                'label'         => 'filter.form.municipality',
                'empty_data'    => '',
                'empty_value'   => 'All'
            ])
            ->add('music_friend', 'boolean_choice', [
                'label'             => 'filter.form.type',
                'choices_as_values' => true,
                'data'              => $options['music_friend'],
                'choices'           => [
                    'filter.form.fika_buddy'    => '0',
                    'filter.form.music_buddy'   => '1'
                ]
            ])
            ->add('city_id', 'hidden', [
                'data' => $options['city_id']
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false
        ]);

        $resolver->setRequired([
            'music_friend',
            'city_id',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'match_filter';
    }
}
