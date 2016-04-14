<?php

namespace AppBundle\Manager;

use JMS\DiExtraBundle\Annotation\Service;
use AppBundle\Entity\CityRepository;
use AppBundle\Entity\City;

/**
 * @Service("city_manager")
 */
class CityManager implements ManagerInterface
{
    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @param CityRepository $cityRepository
     */
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository   = $cityRepository;
    }

    /**
     * @return City
     */
    public function createNew()
    {
        return new City();
    }

    /**
     * @param $entity
     * @return City
     */
    public function save($entity)
    {
        return $this->cityRepository->save($entity);
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getFind($id)
    {
        return $this->cityRepository->find($id);
    }

    /**
     * @return array
     */
    public function getFindAll()
    {
        return $this->cityRepository->findAll();
    }

    /**
     * @param $entity
     */
    public function remove($entity)
    {
        return $this->cityRepository->remove($entity);
    }
}