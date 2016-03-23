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
}