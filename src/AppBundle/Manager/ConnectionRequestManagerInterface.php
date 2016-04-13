<?php

namespace AppBundle\Manager;

use AppBundle\Entity\City;
use AppBundle\Entity\User;

/**
 * Interface ConnectionRequestManagerInterface
 * @package AppBundle\Manager
 */
interface ConnectionRequestManagerInterface
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

    /**
     * @param City $city
     * @return mixed
     */
    public function getFindNewWithinCity(City $city);

    /**
     * @param City $city
     * @return mixed
     */
    public function getFindEstablishedWithinCity(City $city);

    /**
     * @param User $user
     * @return mixed
     */
    public function getFindOneByUser(User $user);

    /**
     * @return mixed
     */
    public function getFindAll();

    /**
     * @param City $city
     * @return mixed
     */
    public function getFindCityStats(City $city);

    /**
     * @param City $city
     * @return mixed
     */
    public function getFindCity(City $city);

    /**
     * @param $id
     * @return bool
     */
    public function markAsPending($id);

    /**
     * @param $id
     */
    public function markAsUnpending($id);

    /**
     * @param $userId
     * @return null|object
     */
    public function getFindOneUnpendingByUserId($userId);

    /**
     * @return array
     */
    public function getFindAllPending();

    /**
     * @return array
     */
    public function getFindAllUninspected();

    /**
     * @param $id
     * @return bool
     */
    public function markAsInspected($id);

    /**
     * @param $id
     * @return bool|null|object
     */
    public function markAsPendingOrUnpending($id);
}