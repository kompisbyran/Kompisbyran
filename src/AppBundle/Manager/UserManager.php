<?php

namespace AppBundle\Manager;

use Knp\Component\Pager\Paginator;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use AppBundle\Entity\UserRepository;
use AppBundle\Entity\User;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Service("user_manager")
 */
class UserManager implements UserManagerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

    /**
     * @var Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @InjectParams({
     *     "paginator" = @Inject("knp_paginator"),
     *     "router" = @Inject("router")
     * })
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, Paginator $paginator, RouterInterface $router)
    {
        $this->userRepository   = $userRepository;
        $this->paginator        = $paginator;
        $this->router           = $router;
    }

    /**
     * @return User
     */
    public function createNew()
    {
        return new User();
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getFind($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param User $user
     * @param int $page
     * @param array $criterias
     * @return array
     */
    public function getFindMatch(User $user, $page = 1, array $criterias)
    {
        unset($criterias['_token']);

        if (strlen(trim($criterias['category']))  == 0) {
            unset($criterias['category']);
        }

        if (strlen(trim($criterias['gender']))  == 0) {
            unset($criterias['gender']);
        }

        if (strlen(trim($criterias['hasChildren'])) == 0) {
            unset($criterias['hasChildren']);
        }

        if (strlen(trim($criterias['from'])) == 0) {
            unset($criterias['from']);
        }

        if (strlen(trim($criterias['municipality'])) == 0) {
            unset($criterias['municipality']);
        }

        $results    = $this->userRepository->findMatchArray($user, $criterias);
        $adapter    = new ArrayAdapter($results);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(2);
        $pagerfanta->setCurrentPage($page);

        return [
            'success'   => true,
            'results'   => $this->getMatchResultsdByPager($pagerfanta),
            'next'      => ($pagerfanta->hasNextPage()? $pagerfanta->getNextPage(): false)
        ];
    }

    /**
     * @param Pagerfanta $pagerfanta
     * @return array
     */
    private function getMatchResultsdByPager(Pagerfanta $pagerfanta)
    {
        $datas  = [];
        $users  = $pagerfanta->getCurrentPageResults();

        foreach ($users as $user) {
            $currentUser    = $this->getFind($user['id']);

            $datas[] = [
                'user_id'           => $user['id'],
                'score'             => $user['score'],
                'interests'         => $currentUser->getNameArrayOfCategories(),
                'user_info'         => $currentUser->getFullName(),
                'edit_profile_link' => $this->router->generate('admin_ajax_edit', ['id' => $user['id']]),
                'about'             => $currentUser->getAbout()
            ];
        }

        return $datas;
    }
}