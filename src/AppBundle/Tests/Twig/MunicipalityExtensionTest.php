<?php

namespace AppkBundle\Tests\Manager;

use AppBundle\Entity\Municipality;
use AppBundle\Entity\MunicipalityRepository;
use AppBundle\Twig\MunicipalityExtension;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Translation\TranslatorInterface;

class MunicipalityExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider municipalityProvider
     */
    public function shouldReturnStringWithMunicipalities($expected, $names)
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $translatorMock->method('trans')->willReturn('och');
        $municipalityRepostoryMock = $this->createMock(MunicipalityRepository::class);
        $municipalityMocks = new ArrayCollection();
        foreach ($names as $name) {
            $municipalityMock = $this->createMock(Municipality::class);
            $municipalityMock->method('getName')->willReturn($name);
            $municipalityMocks->add($municipalityMock);
        }
        $municipalityRepostoryMock->method('findAllActiveStartMunicipalities')->willReturn($municipalityMocks);

        $municipalityExtension = new MunicipalityExtension($translatorMock, $municipalityRepostoryMock);
        $this->assertEquals($expected, $municipalityExtension->startFriendMunicipalities());
    }

    public function municipalityProvider()
    {
        return [
            ['', []],
            ['Örebro', ['Örebro kommun']],
            ['Örebro och Kumla', ['Örebro kommun', 'Kumla kommun']],
            ['Örebro, Hallsberg och Kumla', ['Örebro kommun', 'Hallsbergs', 'Kumla kommun']],
        ];
    }
}
