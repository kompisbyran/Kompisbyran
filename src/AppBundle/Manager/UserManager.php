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
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @InjectParams({
     *     "paginator" = @Inject("knp_paginator"),
     *     "router" = @Inject("router"),
     *     "translator" = @Inject("translator")
     * })
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, Paginator $paginator, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->userRepository   = $userRepository;
        $this->paginator        = $paginator;
        $this->router           = $router;
        $this->translator       = $translator;
    }

    /**
     * @return User
     */
    public function createNew()
    {
        return new User();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function save(User $user)
    {
        return $this->userRepository->save($user);
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
            'results'   => $this->getMatchResultsdByPager($pagerfanta, $user),
            'next'      => ($pagerfanta->hasNextPage()? $pagerfanta->getNextPage(): false)
        ];
    }

    /**
     * @param Pagerfanta $pagerfanta
     * @param User $user
     * @return array
     */
    private function getMatchResultsdByPager(Pagerfanta $pagerfanta, User $user)
    {
        $datas  = [];
        $users  = $pagerfanta->getCurrentPageResults();

        foreach ($users as $auser) {
            $matches        = [];
            $currentUser    = $this->getFind($auser['id']);

            $datas[] = [
                'user_id'           => $auser['id'],
                'score'             => $auser['score'],
                'interests'         => $this->getCategoriesExactMatchByUser($user, $currentUser),
                'user_info'         => $currentUser->getFullName(),
                'edit_profile_link' => $this->router->generate('admin_ajax_edit', ['id' => $auser['id']]),
                'about'             => $currentUser->getAbout(),
                'matches'           => $this->getExactMatchByUser($user, $currentUser),
                'ele'               => 'ele'.$auser['id']
            ];
        }

        return $datas;
    }

    /**
     * @param User $user
     * @param User $currentUser
     * @return array
     */
    private function getExactMatchByUser(User $user, User $currentUser)
    {
        $matches    = [];
        $matches[]  =  ($currentUser->getAge() && ($currentUser->getAge()-$user->getAge()) < 5? '<span class="matches">'.$currentUser->getAge().' '.$this->translator->trans('years').'</span>': $currentUser->getAge().' '.$this->translator->trans('years'));
        $matches[]  =  ($currentUser->getFrom() && $user->getFrom() == $currentUser->getFrom()? '<span class="matches">'.$currentUser->getCountryName().'</span>': $currentUser->getCountryName());
        $matches[]  =  ($currentUser->getMunicipality()->getId() && $user->getMunicipality()->getId() == $currentUser->getMunicipality()->getId()? '<span class="matches">'.$currentUser->getMunicipality()->getName().'</span>': $currentUser->getMunicipality()->getName());
        $matches[]  =  ($currentUser->hasChildren() && $user->hasChildren() == $currentUser->hasChildren()? '<span class="matches">'.($currentUser->hasChildren()? $this->translator->trans('kids'): $this->translator->trans('no kids')).'</span>': ($currentUser->hasChildren()? $this->translator->trans('kids'): $this->translator->trans('no kids')));

        return $matches;
    }

    /**
     * @param User $user
     * @param User $currentUser
     * @return array
     */
    private function getCategoriesExactMatchByUser(User $user, User $currentUser)
    {
        $matches    = [];

        foreach($currentUser->getCategoryNames() as $currentUserCategory) {
            $found = false;
            foreach($user->getCategoryNames() as $userCategory) {
                if ($currentUserCategory === $userCategory) {
                    $found = true;
                    break;
                }
            }

            $matches[]  = ($found? '<span class="matches">'.$currentUserCategory.'</span>': $currentUserCategory);
        }

        if (count($matches) > 1) {
            $lastMatch          = array_pop($matches);
            $categoryMatches    = implode(', ', $matches) .' and '.$lastMatch;
        } else {
            $categoryMatches    = implode(', ', $matches);
        }

        return $categoryMatches;
    }
}