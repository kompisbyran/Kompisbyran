<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Entity\Connection;
use AppBundle\Tests\Phpunit\DatabaseTestCase;
use AppBundle\Tests\Phpunit\Extension\AuthenticationExtensionTrait;
use AppBundle\Tests\Phpunit\Extension\RepositoryExtensionTrait;

class ConnectionControllerTest extends DatabaseTestCase
{
    use AuthenticationExtensionTrait;
    use RepositoryExtensionTrait;

    public function testShouldLoadConnectionsPage()
    {
        $this->authenticateUser(
            $this->getUserRepository()->findOneBy(['email' => 'fluentspeaker@example.com']),
            ['ROLE_ADMIN']
        );

        $client = static::$client;
        $client->request('GET', '/admin/connections/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShouldLoadConnectionPage()
    {
        $this->authenticateUser(
            $this->getUserRepository()->findOneBy(['email' => 'fluentspeaker@example.com']),
            ['ROLE_ADMIN']
        );

        /** @var Connection $connection */
        $connection = $this->getConnectionRepository()->findAll()[0];

        $client = static::$client;
        $client->request('GET', sprintf('/admin/connections/%s', $connection->getId()));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSetMetCreatedAtWithCurrentDateIfNoneProvided()
    {
        $this->authenticateUser(
            $this->getUserRepository()->findOneBy(['email' => 'fluentspeaker@example.com']),
            ['ROLE_ADMIN']
        );

        /** @var Connection $connection */
        $connection = $this->getConnectionRepository()->findAll()[0];

        $this->assertEquals('unknown', $connection->getLearnerMeetingStatus());
        $this->assertEquals('unknown', $connection->getFluentSpeakerMeetingStatus());

        $client = static::$client;
        $crawler = $client->request('GET', sprintf('/admin/connections/%s', $connection->getId()));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('form[name=edit_connection]')->form([
            'edit_connection[fluentSpeakerMeetingStatus]' => 'met',
            'edit_connection[fluentSpeakerMarkedAsMetCreatedAt][date][day]' => null,
            'edit_connection[fluentSpeakerMarkedAsMetCreatedAt][date][month]' => null,
            'edit_connection[fluentSpeakerMarkedAsMetCreatedAt][date][year]' => null,
            'edit_connection[fluentSpeakerMarkedAsMetCreatedAt][time][hour]' => null,
            'edit_connection[fluentSpeakerMarkedAsMetCreatedAt][time][minute]' => null,
            'edit_connection[learnerMeetingStatus]' => 'met',
            'edit_connection[learnerMarkedAsMetCreatedAt][date][day]' => null,
            'edit_connection[learnerMarkedAsMetCreatedAt][date][month]' => null,
            'edit_connection[learnerMarkedAsMetCreatedAt][date][year]' => null,
            'edit_connection[learnerMarkedAsMetCreatedAt][time][hour]' => null,
            'edit_connection[learnerMarkedAsMetCreatedAt][time][minute]' => null,
        ]);

        $crawler = $client->submit($form);
        $this->assertCount(0, $crawler->filter('.has-error'));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $connection = $this->getConnectionRepository()->findAll()[0];

        $this->assertEquals(
            (new \DateTime())->format('Y-m-d'),
            $connection->getFluentSpeakerMarkedAsMetCreatedAt()->format('Y-m-d')
        );
        $this->assertEquals(
            (new \DateTime())->format('Y-m-d'),
            $connection->getLearnerMarkedAsMetCreatedAt()->format('Y-m-d')
        );
    }
}
