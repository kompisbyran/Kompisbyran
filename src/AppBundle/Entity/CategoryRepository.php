<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository
 * @package AppBundle\Entity
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllMusic()
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where('c INSTANCE OF AppBundle:MusicCategory')
            ->orderBy('c.name', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function findAllGeneral()
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where('c INSTANCE OF AppBundle:GeneralCategory')
            ->orderBy('c.name', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllQueryBuilder()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->orderBy('c.name', 'ASC');

        return $qb;
    }
}
