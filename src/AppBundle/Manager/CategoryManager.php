<?php

namespace AppBundle\Manager;

use JMS\DiExtraBundle\Annotation\Service;
use AppBundle\Entity\CategoryRepository;
use AppBundle\Entity\Category;

/**
 * @Service("category_manager")
 */
class CategoryManager implements ManagerInterface
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository   = $categoryRepository;
    }

    /**
     * @return Category
     */
    public function createNew(){}

    /**
     * @param Category $connection
     * @return mixed
     */
    public function save($entity)
    {
        return $this->categoryRepository->save($entity);
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getFind($id)
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * @return array
     */
    public function getFindAll()
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * @param $entity
     */
    public function remove($entity)
    {
        return $this->categoryRepository->remove($entity);
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