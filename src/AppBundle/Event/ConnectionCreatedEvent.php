<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use AppBundle\Entity\Connection;

class ConnectionCreatedEvent extends Event
{
    /**
     * @var \AppBundle\Entity\Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
