<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;

/**
 * Interface UserManagerInterface
 * @package AppBundle\Manager
 */
interface UserManagerInterface
{
    /**
     * @return mixed
     */
    public function createNew();

    /**
     * @param $id
     * @return mixed
     */
    public function getFind($id);

    public function getFindMatch(User $user, $page = 1, array $criterias);
}