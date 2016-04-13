<?php

namespace AppBundle\Manager;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use AppBundle\Entity\CityRepository;
use AppBundle\Entity\City;

/**
 * @Service("city_manager")
 */
class CityManager implements CityManagerInterface
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
}