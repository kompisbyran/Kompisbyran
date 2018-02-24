<?php

namespace AppBundle\Twig;

use AppBundle\Entity\MunicipalityRepository;
use Symfony\Component\Translation\TranslatorInterface;

class MunicipalityExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var MunicipalityRepository
     */
    private $municipalityRepository;

    /**
     * @param TranslatorInterface $translator
     * @param MunicipalityRepository $municipalityRepository
     */
    public function __construct(TranslatorInterface $translator, MunicipalityRepository $municipalityRepository)
    {
        $this->translator = $translator;
        $this->municipalityRepository = $municipalityRepository;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            'start_friend_municipalities' => new \Twig_Function_Method($this, 'startFriendMunicipalities'),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'municipality_extension';
    }

    /**
     * @return string
     */
    public function startFriendMunicipalities($lastNameSeparatorTranslationKey = 'global.and')
    {
        $municipalityNames = [];
        $municipalities = $this->municipalityRepository->findAllActiveStartMunicipalities();
        foreach ($municipalities as $municipality) {
            $name = str_replace(' kommun', '', $municipality->getName());
            $municipalityNames[] = trim($name, 's');
        }
        if (count($municipalityNames) == 0) {
            return '';
        }
        if (count($municipalityNames) == 1) {
            return $municipalityNames[0];
        }

        $last = array_pop($municipalityNames);

        return implode(', ', $municipalityNames) . ' '.  $this->translator->trans($lastNameSeparatorTranslationKey) .' '. $last;
    }
}
