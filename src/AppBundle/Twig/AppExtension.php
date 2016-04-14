<?php

namespace AppBundle\Twig;

use AppBundle\Enum\Countries;
use Symfony\Component\Translation\TranslatorInterface;

class AppExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('country_name', [$this, 'countryName']),
            new \Twig_SimpleFilter('pronoun', [$this, 'pronoun']),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'want_to_learn_name' => new \Twig_Function_Method($this, 'wantToLearnName')
        );
    }

    /**
     * @param string $countryCode
     *
     * @return string
     */
    public function countryName($countryCode)
    {
        return Countries::getName($countryCode);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_extension';
    }

    /**
     * @param string $gender
     *
     * @return string
     */
    public function pronoun($gender)
    {
        if ('M' == $gender) {
            return 'han';
        } elseif ('F' == $gender) {
            return 'hon';
        }

        return 'hen';
    }

    /**
     * @param bool $wantToLearn
     * @return string
     */
    public function wantToLearnName($wantToLearn)
    {
        return $wantToLearn? $this->translator->trans('New'): $this->translator->trans('Established');
    }
}
