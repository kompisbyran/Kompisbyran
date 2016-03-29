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
     * @param User $user
     * @return mixed
     */
    public function save(User $user);

    /**
     * @param $id
     * @return mixed
     */
    public function getFind($id);

    /**
     * @param User $user
     * @param int $page
     * @param array $criterias
     * @return mixed
     */
    public function getFindMatch(User $user, $page = 1, array $criterias);
}