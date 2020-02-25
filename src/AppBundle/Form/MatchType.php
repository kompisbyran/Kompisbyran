<?php

namespace AppBundle\Form;

use JMS\DiExtraBundle\Annotation\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @FormType
 */
class MatchType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @InjectParams({
     *     "translator" = @Inject("translator")
     * })
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email_to_user', TextareaType::class, [
                'label' => 'match.email.to',
                'attr'  => [
                    'rows' => 25
                ]
            ])
            ->add('email_to_match_user', TextareaType::class, [
                'label' => 'match.email.to',
                'attr'  => [
                    'rows' => 25
                ]
            ])
            ->add('user_id', HiddenType::class, [
                'label' => false,
                'data'  => $options['user']->getId()
            ])
            ->add('match_user_id', HiddenType::class, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false
        ]);

        $resolver->setRequired([
            'user'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'match';
    }
}
