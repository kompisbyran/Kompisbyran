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
use Symfony\Component\HttpFoundation\RequestStack;
use AppBundle\Manager\CategoryManager;

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
     * @var CategoryManager
     */
    private $categoryManager;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @InjectParams({
     *     "paginator" = @Inject("knp_paginator"),
     *     "router" = @Inject("router"),
     *     "translator" = @Inject("translator"),
     *     "requestStack" = @Inject("request_stack")
     * })
     * @param UserRepository $userRepository
     * @param CategoryManager $categoryManager
     */
    public function __construct(UserRepository $userRepository, CategoryManager $categoryManager, Paginator $paginator, RouterInterface $router, TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->userRepository   = $userRepository;
        $this->categoryManager  = $categoryManager;
        $this->paginator        = $paginator;
        $this->router           = $router;
        $this->translator       = $translator;
        $this->requestStack     = $requestStack;
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

        if (strlen(trim($criterias['category_id']))  == 0) {
            unset($criterias['category_id']);
        }

        if (strlen(trim($criterias['gender']))  == 0) {
            unset($criterias['gender']);
        }

        if (strlen(trim($criterias['has_children'])) == 0) {
            unset($criterias['has_children']);
        }

        if (strlen(trim($criterias['from'])) == 0) {
            unset($criterias['from']);
        }

        if (strlen(trim($criterias['municipality_id'])) == 0) {
            unset($criterias['municipality_id']);
        }

        $results    = $this->userRepository->findMatchArray($user, $criterias);
        $adapter    = new ArrayAdapter($results);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
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
            $currentUser    = $this->getFind($auser['id']);
            $datas[]        = [
                'user_id'           => $auser['id'],
                'score'             => $auser['score'],
                'interests'         => $this->getCategoriesExactMatchByUser($user, $currentUser),
                'user_info'         => $currentUser->getFullName(),
                'edit_profile_link' => $this->router->generate('admin_ajax_edit', ['id' => $auser['id']]),
                'mark_pending_link' => $this->router->generate('admin_ajax_connection_request_mark_pending', ['user_id' => $auser['id']]),
                'about'             => $currentUser->getAbout(),
                'matches'           => $this->getExactMatchByUser($user, $currentUser),
                'ele'               => 'ele'.$auser['id'],
                'gender'            => ($currentUser->getGender() == $user->getGender()? 1: 0),
                'age_diff'          => $currentUser->getAge()-$user->getAge()
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
        $ageDiff    = $currentUser->getAge()-$user->getAge();
        $matches    = [];
        $matches[]  =  ( $ageDiff > -5 && $ageDiff < 5? '<span class="matches">'.$currentUser->getAge().' '.$this->translator->trans('years').'</span>': $currentUser->getAge().' '.$this->translator->trans('years'));
        $matches[]  =  $currentUser->getCountryName();
        $matches[]  =  ($user->getMunicipality()->getId() == $currentUser->getMunicipality()->getId()? '<span class="matches">'.$currentUser->getMunicipality()->getName().'</span>': $currentUser->getMunicipality()->getName());
        $matches[]  =  ($user->hasChildren() == true && $currentUser->hasChildren() == true? '<span class="matches">'.($currentUser->hasChildren()? $this->translator->trans('kids'): $this->translator->trans('no kids')).'</span>': ($currentUser->hasChildren()? $this->translator->trans('kids'): $this->translator->trans('no kids')));

        return $matches;
    }

    /**
     * @param User $user
     * @param User $currentUser
     * @return string
     */
    private function getCategoriesExactMatchByUser(User $user, User $currentUser)
    {
        $categories             = [];
        $locale                 = $this->requestStack->getCurrentRequest()->getLocale();
        $currentUserCategories  = $this->categoryManager->getFindByIdsAndLocale(array_keys($currentUser->getCategoryNames()), $locale);
        $userCategories         = $user->getCategoryNames();

        foreach($currentUserCategories as $currentUserCategory) {

            if (isset($userCategories[$currentUserCategory->getId()])) {
                $categories[]   = '<span class="matches">'.$currentUserCategory->getName().'</span>';
            } else {
                $categories[]   = $currentUserCategory->getName();
            }
        }

        if (count($categories) > 1) {
            $lastCategory   = array_pop($categories);
            $categories     = implode(', ', $categories) .' and '.$lastCategory;
        } else {
            $categories     = implode(', ', $categories);
        }

        return $categories;
    }
}