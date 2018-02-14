<?php

namespace AppBundle\Tests\Controller\Open;

use AppBundle\Entity\Connection;
use AppBundle\Enum\MeetingTypes;
use AppBundle\Tests\Phpunit\DatabaseTestCase;
use AppBundle\Tests\Phpunit\Extension\RepositoryExtensionTrait;

class ConnectionControllerTest extends DatabaseTestCase
{
    use RepositoryExtensionTrait;

    /**
     * @test
     */
    public function shouldLoadConfirmMeetingPage()
    {
        $client = static::createClient();
        /** @var Connection $connection */
        $connection = $this->getConnectionRepository()->findAll()[0];
        $user = $connection->getFluentSpeaker();

        $client->request(
            'GET',
            sprintf('/public/meetings/%s/%s', $user->getUuid(), $connection->getId())
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function shouldUpdateConfirmedMeeting()
    {
        $client = static::createClient();
        /** @var Connection $connection */
        $connection = $this->getConnectionRepository()->findAll()[0];
        $user = $connection->getFluentSpeaker();

        $status = $connection->getFluentSpeakerMeetingStatus();

        $client->request(
            'POST',
            sprintf('/public/meetings/%s/%s', $user->getUuid(), $connection->getId()),
            ['status' => MeetingTypes::MET]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->getEntityManager()->refresh($connection);
        $this->assertNotEquals($status, $connection->getFluentSpeakerMeetingStatus());
    }

    /**
     * @test
     */
    public function shouldNotUpdateConfirmedMeeting()
    {
        $client = static::createClient();
        /** @var Connection $connection */
        $connection = $this->getConnectionRepository()->findAll()[0];
        $user = $connection->getFluentSpeaker();

        $status = $connection->getFluentSpeakerMeetingStatus();

        $client->request(
            'POST',
            sprintf('/public/meetings/%s/%s', $user->getUuid(), $connection->getId()),
            ['status' => 'Invalid status']
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->getEntityManager()->refresh($connection);
        $this->assertEquals($status, $connection->getFluentSpeakerMeetingStatus());
    }

    /**
     * @test
     */
    public function shouldCloneConnectionRequest()
    {
        $client = static::createClient();
        /** @var Connection $connection */
        $connection = $this->getConnectionRepository()->findAll()[1];
        $user = $connection->getLearner();

        $connectionRequestCount = count($this->getConnectionRequestRepository()->findAll());

        $client->request(
            'POST',
            sprintf('/public/meetings/%s/%s/clone', $user->getUuid(), $connection->getId())
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertNotEquals($connectionRequestCount, count($this->getConnectionRequestRepository()->findAll()));
    }
}
