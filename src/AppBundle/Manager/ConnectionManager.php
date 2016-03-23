<?php

namespace AppBundle\Manager;

use Knp\Component\Pager\Paginator;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use AppBundle\Entity\ConnectionRepository;
use AppBundle\Entity\Connection;

/**
 * @Service("connection_manager")
 */
class ConnectionManager implements ConnectionManagerInterface
{
    /**
     * @var ConnectionRepository
     */
    private $connectionRepository;

    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

    /**
     * @InjectParams({
     *      "paginator" = @Inject("knp_paginator")
     * })
     * @param ConnectionRepository $connectionRepository
     */
    public function __construct(ConnectionRepository $connectionRepository, Paginator $paginator)
    {
        $this->connectionRepository = $connectionRepository;
        $this->paginator            = $paginator;
    }

    /**
     * @return Connection
     */
    public function createNew()
    {
        return new Connection();
    }
}