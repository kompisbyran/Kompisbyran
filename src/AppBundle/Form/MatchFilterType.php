<?php

namespace AppBundle\Form;

use AppBundle\Enum\FriendTypes;
use JMS\DiExtraBundle\Annotation\FormType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('category_id', EntityType::class, [
                'class'         => 'AppBundle:Category',
                'choice_label'      => 'name',
                'label'         => 'Interests',
                'empty_data'    => '',
                'placeholder'   => 'All',
                'choices'       => $this->categoryManager->getFindAllByLocale($this->requestStack->getCurrentRequest()->getLocale())
            ])
            ->add('ageFrom', ChoiceType::class, [
                'label'         => 'Age',
                'data'          => 18,
                'choices'       => array_combine($age, $age)
            ])
            ->add('ageTo', ChoiceType::class, [
                'label'         => 'Age to',
                'data'          => 100,
                'choices'       => array_combine($age, $age)
            ])
            ->add('gender', ChoiceType::class, [
                'label'         => 'Gender',
                'choices'       => User::getGenders(),
                'empty_data'    => '',
                'placeholder'   => 'All'
            ])
            ->add('has_children', ChoiceTypeBoolean::class, [
                'label'             => 'Children',
                'choices_as_values' => true,
                'empty_data'        => '',
                'placeholder'       => 'Doesn\'t matter',
                'choices'           => [
                    'no'            => '0',
                    'yes'           => '1'
                ]
            ])
            ->add('from_country', ChoiceType::class, [
                'label'         => 'Country',
                'choices'       => Countries::getList(),
                'empty_data'    => '',
                'placeholder'   => 'All'
            ])
            ->add('municipality_id', EntityType::class, [
                'class'         => 'AppBundle:Municipality',
                'choice_label'      => 'name',
                'label'         => 'Area',
                'empty_data'    => '',
                'placeholder'   => 'All'
            ])
            ->add('q', TextType::class, [
                'label' => 'Search'
            ])
            ->add('city_id', HiddenType::class, [
                'data' => $options['city_id']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false
        ]);

        $resolver->setRequired([
            'type',
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
