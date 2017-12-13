<?php

namespace AppBundle\Event;

use AppBundle\Entity\Connection;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class MeetingStatusUpdatedEvent extends Event
{
    const NAME = 'meeting_status_updated';

    /**
     * @var User
     */
    private $user;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param User $user
     * @param Connection $connection
     */
    public function __construct(User $user, Connection $connection)
    {
        $this->user = $user;
        $this->connection = $connection;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
