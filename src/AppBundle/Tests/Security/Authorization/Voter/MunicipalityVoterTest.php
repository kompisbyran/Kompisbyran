<?php

namespace AppkBundle\Tests\Security\Authorization\Voter;

use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\MunicipalityVoter;
use AppBundle\Entity\Municipality;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class MunicipalityVoterTest extends \PHPUnit_Framework_TestCase
{
    protected $voter;

    public function setUp()
    {
        $this->voter = new MunicipalityVoter();
    }

    /**
     * @test
     */
    public function shouldGrantAccessToUserConnectedToMunicipality()
    {
        $userMock = $this->getMock(User::class);
        $tokenMock = $this->getMock(TokenInterface::class);
        $tokenMock->expects($this->once())->method('getUser')->willReturn($userMock);

        $municipality = new Municipality();
        $userMock->method('getAdminMunicipalities')->willReturn([$municipality]);

        $this->assertEquals(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote(
                $tokenMock,
                $municipality,
                [MunicipalityVoter::ADMIN_VIEW]
            )
        );
    }

    /**
     * @test
     */
    public function shouldDenyAccessToUserNotConnectedToMunicipality()
    {
        $userMock = $this->getMock(User::class);
        $tokenMock = $this->getMock(TokenInterface::class);
        $tokenMock->expects($this->once())->method('getUser')->willReturn($userMock);

        $municipality = new Municipality();
        $userMock->method('getAdminMunicipalities')->willReturn([]);

        $this->assertEquals(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote(
                $tokenMock,
                $municipality,
                [MunicipalityVoter::ADMIN_VIEW]
            )
        );
    }
}
