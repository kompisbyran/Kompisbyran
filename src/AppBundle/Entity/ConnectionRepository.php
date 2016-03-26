<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use AppBundle\Entity\Connection;

/**
 * Class ConnectionRepository
 * @package AppBundle\Entity
 */
class ConnectionRepository extends EntityRepository
{
    /**
     * @param Connection $connection
     * @return Connection
     */
    public function save(Connection $connection)
    {
        $this->getEntityManager()->persist($connection);
        $this->getEntityManager()->flush();

        return $connection;
    }

    /**
     * @param Connection $connection
     */
    public function remove(Connection $connection)
    {
        $this->getEntityManager()->remove($connection);
        $this->getEntityManager()->flush();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getFindAllQuery($searchString)
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('c, f, l, cb, c2, u')
            ->innerJoin('c.fluentSpeaker', 'f')
            ->innerJoin('c.learner', 'l')
            ->innerJoin('c.createdBy', 'cb')
            ->leftJoin('c.comments', 'c2')
            ->leftJoin('c2.user', 'u')
        ;

        if ($searchString) {
            $qb
                ->where('f.email LIKE :searchString')
                ->orWhere('l.email LIKE :searchString')
                ->orWhere("CONCAT(CONCAT(f.firstName, ' '), f.lastName) LIKE :searchString")
                ->orWhere("CONCAT(CONCAT(l.firstName, ' '), l.lastName) LIKE :searchString")
                ->setParameter('searchString', '%'.trim($searchString).'%')
            ;
        }

        return $qb
            ->orderBy('c.id', 'desc')
            ->getQuery()
        ;
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return Connection[]
     */
    public function findForUsers(User $user1, User $user2)
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.fluentSpeaker = :user1 and c.learner = :user2')
            ->orWhere('c.fluentSpeaker = :user2 and c.learner = :user1')
            ->setParameters([
                'user1' => $user1,
                'user2' => $user2,
            ])
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param User $user
     * @return int
     */
    public function isUserConnectionExists(User $user1, User $user2)
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->select('COUNT(c.id)')
            ->where('c.fluentSpeaker = :user1 and c.learner = :user2')
            ->orWhere('c.fluentSpeaker = :user2 and c.learner = :user1')
            ->setParameters([
                'user1' => $user1,
                'user2' => $user2,
            ])
        ;

        try{
            return $qb->getQuery()->getSingleScalarResult()? true: false;
        }
        catch(NoResultException $e) {
            return false;
        }
    }
}
