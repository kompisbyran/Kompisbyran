<?php

namespace AppBundle\Form;

use JMS\DiExtraBundle\Annotation\FormType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('email_to_user', 'textarea', [
                'label' => 'match.email.to',
                'attr'  => [
                    'rows' => 25
                ]
            ])
            ->add('email_to_match_user', 'textarea', [
                'label' => 'match.email.to',
                'attr'  => [
                    'rows' => 25
                ]
            ])
            ->add('user_id', 'hidden', [
                'label' => false,
                'data'  => $options['user']->getId()
            ])
            ->add('match_user_id', 'hidden', [
                'label' => false
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
