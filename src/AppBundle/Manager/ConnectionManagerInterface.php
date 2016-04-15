<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Connection;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\User;

/**
 * Interface ConnectionManagerInterface
 * @package AppBundle\Manager
 */
interface ConnectionManagerInterface
{
    /**
     * @return mixed
     */
    public function createNew();

    /**
     * @param Connection $connection
     * @return mixed
     */
    public function save(Connection $connection);

    /**
     * @param ConnectionRequest $userRequest
     * @param ConnectionRequest $matchUserRequest
     * @param User $loggedUser
     * @return mixed
     */
    public function saveByConnectionRequest(ConnectionRequest $userRequest, ConnectionRequest $matchUserRequest, User $loggedUser);

    /**
     * @param User $user
     * @param User $matchUser
     * @return mixed
     */
    public function getIsUserConnectionExists(User $user, User $matchUser);

    /**
     * @param ConnectionRequest $userRequest
     * @param ConnectionRequest $matchUserRequest
     * @return mixed
     */
    public function getLearnerSpeaker(ConnectionRequest $userRequest, ConnectionRequest $matchUserRequest);
}