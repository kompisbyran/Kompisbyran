<?php

namespace AppBundle\Manager;

use Knp\Component\Pager\Paginator;
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
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

    /**
     * @InjectParams({
     *      "paginator" = @Inject("knp_paginator")
     * })
     * @param CityRepository $cityRepository
     */
    public function __construct(CityRepository $cityRepository, Paginator $paginator)
    {
        $this->cityRepository   = $cityRepository;
        $this->paginator        = $paginator;
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