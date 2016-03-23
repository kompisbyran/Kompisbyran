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
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return array
     */
    public function getFindAllMusic()
    {
        return $this->categoryRepository->findAllMusic();
    }

    /**
     * @return array
     */
    public function getFindAllGeneral()
    {
        return $this->categoryRepository->findAllGeneral();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getFindAllQueryBuilder()
    {
        return $this->categoryRepository->findAllQueryBuilder();
    }
}