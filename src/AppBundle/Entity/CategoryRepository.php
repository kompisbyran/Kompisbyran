<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\NonUniqueResultException;
use  AppBundle\Entity\Category;

/**
 * Class CategoryRepository
 * @package AppBundle\Entity
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @param Category $category
     * @return Category
     */
    public function save(Category $category)
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();

        return $category;
    }

    /**
     * @param Category $category
     */
    public function remove(Category $category)
    {
        $this->getEntityManager()->remove($category);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $locale
     * @return array
     */
    public function findAllMusicByLocale($locale)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where('c INSTANCE OF AppBundle:MusicCategory')
            ->orderBy('c.name', 'ASC')
        ;

        $query = $qb->getQuery();

        $this->setTranslationWalker($query, $locale);

        return $query->getResult();
    }

    /**
     * @param $locale
     * @return array
     */
    public function findAllGeneralByLocale($locale)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where('c INSTANCE OF AppBundle:GeneralCategory')
            ->orderBy('c.name', 'ASC')
        ;

        $query = $qb->getQuery();

        $this->setTranslationWalker($query, $locale);

        return $query->getResult();
    }

    /**
     * @param $locale
     * @return array
     */
    public function findAllByLocale($locale)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->orderBy('c.name', 'ASC');

        $query = $qb->getQuery();

        $this->setTranslationWalker($query, $locale);

        return $query->getResult();
    }

    /**
     * @param $id
     * @param $locale
     * @return mixed|null
     */
    public function findOneByIdAndLocale($id, $locale)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where('c.id = :id')
            ->setParameter('id', $id)
        ;

        $query = $qb->getQuery();

        $this->setTranslationWalker($query, $locale);

        try {
            return $query->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param array $ids
     * @param $locale
     * @return array
     */
    public function findByIdsAndLocale(array $ids, $locale)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where('c.id IN (:ids)')
            ->orderBy('c.name')
            ->setParameter('ids', $ids)
        ;

        $query = $qb->getQuery();

        $this->setTranslationWalker($query, $locale);

        return $query->getResult();
    }

    /**
     * @param Query $query
     * @param $locale
     */
    public function setTranslationWalker(Query $query, $locale)
    {
        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );
        $query->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);
    }
}
