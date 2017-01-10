<?php

namespace AppkBundle\Tests\Security\Authorization\Voter;

use AppBundle\Entity\Connection;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\UserVoter;
use AppBundle\Entity\Municipality;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class UserVoterTest extends \PHPUnit_Framework_TestCase
{
    protected $voter;

    public function setUp()
    {
        $this->voter = new UserVoter();
    }

    /**
     * @test
     */
    public function shouldGrantAccessToAdmin()
    {
        $loggedInUserMock = $this->getMock(User::class);
        $loggedInUserMock->method('hasRole')->with('ROLE_ADMIN')->willReturn(true);
        $tokenMock = $this->getMock(TokenInterface::class);
        $tokenMock->expects($this->once())->method('getUser')->willReturn($loggedInUserMock);

        $user = new User();

        $this->assertEquals(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote(
                $tokenMock,
                $user,
                [UserVoter::VIEW]
            )
        );
    }

    /**
     * @test
     */
    public function shouldGrantAccessToMunicipalityAdminIfUserHasConnectionRequest()
    {
        $loggedInUserMock = $this->getMock(User::class);
        $tokenMock = $this->getMock(TokenInterface::class);
        $tokenMock->expects($this->once())->method('getUser')->willReturn($loggedInUserMock);
        $municipalityMock = $this->getMock(Municipality::class);

        $user = new User();
        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setMunicipality($municipalityMock);
        $user->addConnectionRequest($connectionRequest);
        $loggedInUserMock->method('getAdminMunicipalities')->willReturn([$municipalityMock]);

        $this->assertEquals(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote(
                $tokenMock,
                $user,
                [UserVoter::VIEW]
            )
        );
    }

    /**
     * @test
     */
    public function shouldNotGrantAccessToMunicipalityAdminIfUserHasConnectionRequestToAnotherMunicipality()
    {
        $loggedInUserMock = $this->getMock(User::class);
        $tokenMock = $this->getMock(TokenInterface::class);
        $tokenMock->expects($this->once())->method('getUser')->willReturn($loggedInUserMock);
        $municipalityMock1 = $this->getMock(Municipality::class);
        $municipalityMock1->method('getId')->willReturn(1);
        $municipalityMock2 = $this->getMock(Municipality::class);
        $municipalityMock2->method('getId')->willReturn(2);

        $user = new User();
        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setMunicipality($municipalityMock1);
        $user->addConnectionRequest($connectionRequest);
        $loggedInUserMock->method('getAdminMunicipalities')->willReturn([$municipalityMock2]);

        $this->assertEquals(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote(
                $tokenMock,
                $user,
                [UserVoter::VIEW]
            )
        );
    }

    /**
     * @test
     */
    public function shouldGrantAccessToMunicipalityAdminIfUserHasLearnerConnection()
    {
        $loggedInUserMock = $this->getMock(User::class);
        $tokenMock = $this->getMock(TokenInterface::class);
        $tokenMock->expects($this->once())->method('getUser')->willReturn($loggedInUserMock);
        $municipalityMock = $this->getMock(Municipality::class);

        $user = new User();
        $connection = new Connection();
        $connection->setMunicipality($municipalityMock);
        $user->setLearnerConnections(new ArrayCollection([$connection]));
        $loggedInUserMock->method('getAdminMunicipalities')->willReturn([$municipalityMock]);

        $this->assertEquals(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote(
                $tokenMock,
                $user,
                [UserVoter::VIEW]
            )
        );
    }

    /**
     * @test
     */
    public function shouldNotGrantAccessToMunicipalityAdminIfUserHasLearnerConnectionToAnotherMunicipality()
    {
        $loggedInUserMock = $this->getMock(User::class);
        $tokenMock = $this->getMock(TokenInterface::class);
        $tokenMock->expects($this->once())->method('getUser')->willReturn($loggedInUserMock);
        $municipalityMock1 = $this->getMock(Municipality::class);
        $municipalityMock1->method('getId')->willReturn(1);
        $municipalityMock2 = $this->getMock(Municipality::class);
        $municipalityMock2->method('getId')->willReturn(2);

        $user = new User();
        $connection = new Connection();
        $connection->setMunicipality($municipalityMock1);
        $user->setLearnerConnections(new ArrayCollection([$connection]));
        $loggedInUserMock->method('getAdminMunicipalities')->willReturn([$municipalityMock2]);

        $this->assertEquals(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote(
                $tokenMock,
                $user,
                [UserVoter::VIEW]
            )
        );
    }

    /**
     * @test
     */
    public function shouldGrantAccessToMunicipalityAdminIfUserHasFluentSpeakerConnection()
    {
        $loggedInUserMock = $this->getMock(User::class);
        $tokenMock = $this->getMock(TokenInterface::class);
        $tokenMock->expects($this->once())->method('getUser')->willReturn($loggedInUserMock);
        $municipalityMock = $this->getMock(Municipality::class);

        $user = new User();
        $connection = new Connection();
        $connection->setMunicipality($municipalityMock);
        $user->setFluentSpeakerConnections(new ArrayCollection([$connection]));
        $loggedInUserMock->method('getAdminMunicipalities')->willReturn([$municipalityMock]);

        $this->assertEquals(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote(
                $tokenMock,
                $user,
                [UserVoter::VIEW]
            )
        );
    }

    /**
     * @test
     */
    public function shouldNotGrantAccessToMunicipalityAdminIfUserHasFluentSpeakerConnectionToAnotherMunicipality()
    {
        $loggedInUserMock = $this->getMock(User::class);
        $tokenMock = $this->getMock(TokenInterface::class);
        $tokenMock->expects($this->once())->method('getUser')->willReturn($loggedInUserMock);
        $municipalityMock1 = $this->getMock(Municipality::class);
        $municipalityMock1->method('getId')->willReturn(1);
        $municipalityMock2 = $this->getMock(Municipality::class);
        $municipalityMock2->method('getId')->willReturn(2);

        $user = new User();
        $connection = new Connection();
        $connection->setMunicipality($municipalityMock1);
        $user->setFluentSpeakerConnections(new ArrayCollection([$connection]));
        $loggedInUserMock->method('getAdminMunicipalities')->willReturn([$municipalityMock2]);

        $this->assertEquals(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote(
                $tokenMock,
                $user,
                [UserVoter::VIEW]
            )
        );
    }

}
