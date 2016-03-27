<?php

namespace AppBundle\Manager;

use Knp\Component\Pager\Paginator;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use AppBundle\Entity\CategoryRepository;
use AppBundle\Entity\Category;

/**
 * @Service("category_manager")
 */
class CategoryManager implements CategoryInterface
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var \Knp\Component\Pager\Paginator
     */
    private $paginator;

    /**
     * @InjectParams({
     *      "paginator" = @Inject("knp_paginator")
     * })
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository, Paginator $paginator)
    {
        $this->categoryRepository   = $categoryRepository;
        $this->paginator            = $paginator;
    }

    /**
     * @param string $locale
     * @return array
     */
    public function getFindAllMusicByLocale($locale)
    {
        return $this->categoryRepository->findAllMusicByLocale($locale);
    }

    /**
     * @param $locale
     * @return array
     */
    public function getFindAllGeneralByLocale($locale)
    {
        return $this->categoryRepository->findAllGeneralByLocale($locale);
    }

    /**
     * @param $locale
     * @return array
     */
    public function getFindAllByLocale($locale)
    {
        return $this->categoryRepository->findAllByLocale($locale);
    }

    /**
     * @param $id
     * @param $locale
     * @return mixed
     */
    public function getFindOneByIdAndLocale($id, $locale)
    {
        return $this->categoryRepository->findOneByIdAndLocale($id, $locale);
    }

    /**
     * @param array $ids
     * @param $locale
     * @return array
     */
    public function getFindByIdsAndLocale(array $ids, $locale)
    {
        return $this->categoryRepository->findByIdsAndLocale($ids, $locale);
    }
}