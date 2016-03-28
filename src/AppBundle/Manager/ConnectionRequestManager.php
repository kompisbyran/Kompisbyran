<?php

namespace AppBundle\Manager;

use Knp\Component\Pager\Paginator;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use AppBundle\Entity\ConnectionRequestRepository;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\City;
use AppBundle\Entity\User;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

/**
 * @Service("connection_request_manager")
 */
class ConnectionRequestManager implements ConnectionRequestManagerInterface
{
    /**
     * @var ConnectionRequestRepository
     */
    private $connectionRequestRepository;

    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

    /**
     * @InjectParams({
     *     "paginator" = @Inject("knp_paginator")
     * })
     * @param ConnectionRequestRepository $connectionRequestRepository
     */
    public function __construct(ConnectionRequestRepository $connectionRequestRepository, Paginator $paginator)
    {
        $this->connectionRequestRepository  = $connectionRequestRepository;
        $this->paginator                    = $paginator;
    }

    /**
     * @return ConnectionRequest
     */
    public function createNew()
    {
        return new ConnectionRequest();
    }

    /**
     * @param ConnectionRequest $connectionRequest
     */
    public function remove(ConnectionRequest $connectionRequest)
    {
        $this->connectionRequestRepository->remove($connectionRequest);
    }

    /**
     * @param City $city
     * @return array
     */
    public function getFindNewWithinCity(City $city)
    {
        return $this->connectionRequestRepository->findNewWithinCity($city);
    }

    /**
     * @param City $city
     * @return array
     */
    public function getFindEstablishedWithinCity(City $city)
    {
        return $this->connectionRequestRepository->findEstablishedWithinCity($city);
    }

    /**
     * @param User $user
     * @return null|object
     */
    public function getFindOneByUser(User $user)
    {
        return $this->connectionRequestRepository->findOneByUser($user);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function userHasActiveRequest(User $user)
    {
        return $this->countUserActiveRequests($user)? true: false;
    }

    /**
     * @return array
     */
    public function getFindAll()
    {
        return $this->connectionRequestRepository->findAll();
    }

    /**
     * @param City $city
     * @return array
     */
    public function getFindCityStats(City $city)
    {
        return $this->connectionRequestRepository->findCityStats($city);
    }

    /**
     * @param City $city
     * @return array
     */
    public function getFindCity(City $city)
    {
        return $this->connectionRequestRepository->findCity($city);
    }

    /**
     * @param City $city
     * @param int $page
     * @return array
     */
    public function getFindPaginatedByCityResults(City $city, $page = 1)
    {
        $qb         = $this->connectionRequestRepository->findByCityQueryBuilder($city);
        $adapter    = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(2);
        $pagerfanta->setCurrentPage($page);

        return [
            'success'           => true,
            'newUsers'          => count($this->getFindNewWithinCity($city)),
            'establishedUsers'  => count($this->getFindEstablishedWithinCity($city)),
            'results'           => $this->getCityResultsdByPagination($pagerfanta),
            'next'              => ($pagerfanta->hasNextPage()? $pagerfanta->getNextPage(): false)
        ];
    }

    /**
     * @param Pagerfanta $pagerfanta
     * @return array
     */
    private function getCityResultsdByPagination(Pagerfanta $pagerfanta)
    {
        $datas              = [];
        $connectionRequests = $pagerfanta->getCurrentPageResults();

        foreach ($connectionRequests as $connectionRequest) {
            $datas[] = [
                'request_date'  => $connectionRequest->getCreatedAt()->format('Y-m-d'),
                'name'          => $connectionRequest->getUser()->getFullName(),
                'category'      => $connectionRequest->getType(),
                'action'        => $connectionRequest->getUser()->getId()
            ];
        }

        return $datas;
    }
}