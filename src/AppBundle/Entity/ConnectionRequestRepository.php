<?php

namespace AppBundle\Entity;

use AppBundle\Enum\FriendTypes;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ConnectionRequestRepository
 * @package AppBundle\Entity
 */
class ConnectionRequestRepository extends EntityRepository
{
    /**
     * @param ConnectionRequest $connectionRequest
     * @return ConnectionRequest
     */
    public function save(ConnectionRequest $connectionRequest)
    {
        $this->getEntityManager()->persist($connectionRequest);
        $this->getEntityManager()->flush();

        return $connectionRequest;
    }

    /**
     * @param ConnectionRequest $connectionRequest
     */
    public function remove(ConnectionRequest $connectionRequest)
    {
        $this->getEntityManager()->remove($connectionRequest);
        $this->getEntityManager()->flush();
    }

    /**
     * @param User $user
     * @return null|ConnectionRequest
     */
    public function findOneOpenByUser(User $user)
    {
        return $this->createQueryBuilder('cr')
            ->leftJoin('cr.fluentSpeakerConnection', 'fsc')
            ->leftJoin('cr.learnerConnection', 'lc')
            ->where('cr.user = :user')
            ->setParameter('user', $user)
            ->andWhere('cr.disqualified = false')
            ->andWhere('fsc.id IS NULL')
            ->andWhere('lc.id IS NULL')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param $userId
     * @return null|ConnectionRequest
     */
    public function findOneUnpendingByUserId($userId)
    {
        return $this->createQueryBuilder('cr')
            ->leftJoin('cr.fluentSpeakerConnection', 'fsc')
            ->leftJoin('cr.learnerConnection', 'lc')
            ->innerJoin('cr.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('cr.pending = false')
            ->andWhere('fsc.id IS NULL')
            ->andWhere('lc.id IS NULL')
            ->getQuery()
            ->getOneOrNullResult()
            ;

    }

    /**
     * @param $userId
     * @return null|object
     */
    public function findOneByUserId($userId)
    {
        return $this->findOneBy(array('user' => $userId));
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this
            ->createQueryBuilder('cr')
            ->where('cr.disqualified     = false')
            ->orderBy('cr.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param City $city
     * @param $wantToLearn
     * @param $musicFriend
     * @return array
     */
    public function countByCityWantToLearnAndMusicFriend(City $city, $wantToLearn, $musicFriend)
    {
        return $this
            ->createQueryBuilder('cr')
            ->select('COUNT(cr.id)')
            ->join('cr.user', 'u')
            ->leftJoin('cr.fluentSpeakerConnection', 'fsc')
            ->leftJoin('cr.learnerConnection', 'lc')
            ->where('u.wantToLearn = :wantToLearn')
            ->andWhere('cr.city = :city')
            ->andWhere('cr.disqualified = false')
            ->andWhere('cr.pending = false')
            ->andWhere('u.type = :type')
            ->andWhere('fsc.id IS NULL')
            ->andWhere('lc.id IS NULL')
            ->andWhere('u.enabled = true')
            ->setParameters([
                'city'          => $city,
                'wantToLearn'   => $wantToLearn,
                'type' => $musicFriend ? FriendTypes::MUSIC : FriendTypes::FRIEND,
            ])
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param City $city
     * @return array
     */
    public function countNewWithinCity(City $city)
    {
        return $this->countByCityWantToLearnAndMusicFriend($city, true, false);
    }

    /**
     * @param City $city
     * @return array
     */
    public function countNewMusicFriendWithinCity(City $city)
    {
        return $this->countByCityWantToLearnAndMusicFriend($city, true, true);
    }

    /**
     * @param City $city
     * @return array
     */
    public function countEstablishedWithinCity(City $city)
    {
        return $this->countByCityWantToLearnAndMusicFriend($city, false, false);
    }

    /**
     * @param City $city
     * @return array
     */
    public function countEstablishedMusicFriendWithinCity(City $city)
    {
        return $this->countByCityWantToLearnAndMusicFriend($city, false, true);
    }

    /**
     * @param City $city
     * @return array
     */
    public function findCityStats(City $city)
    {
        return $this
            ->createQueryBuilder('cr')
            ->select('SUM(IF(cr.want_to_learn = 0,0,1)) AS new, SUM(IF(cr.want_to_learn = 0,1,0)) AS established')
            ->where('cr.city = :city')
            ->setParameter('city', $city)
            ->getQuery()
            ->getArrayResult()
        ;
    }

    /**
     * @param City $city
     * @param $excludeType
     *
     * @return ConnectionRequest[]
     */
    public function findByCity(City $city, $excludeType = null)
    {
        return $this
            ->findByCityQueryBuilder($city, $excludeType)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @param City $city
     * @param $excludeType
     *
     * @return ConnectionRequest[]
     */
    public function findOpenByCity(City $city, $excludeType = null)
    {
        $qb = $this->findByCityQueryBuilder($city, $excludeType);
        $qb
            ->leftJoin('cr.fluentSpeakerConnection', 'fsc')
            ->leftJoin('cr.learnerConnection', 'lc')
            ->andWhere('fsc.id IS NULL')
            ->andWhere('lc.id IS NULL')
            ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param City $city
     * @param $excludeType
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByCityQueryBuilder(City $city, $excludeType = null)
    {
        $qb = $this
            ->createQueryBuilder('cr')
            ->innerJoin('cr.user', 'u')
            ->where('cr.city        = :city')
            ->andWhere('cr.disqualified = false')
            ->andWhere('cr.pending = false')
            ->andWhere('cr.inspected = true')
            ->andWhere('u.enabled = true')
            ->groupBy('cr.user')
            ->orderBy('cr.sortOrder', 'DESC')
            ->addOrderBy('cr.createdAt', 'ASC')
            ->setParameter('city', $city)
        ;
        if ($excludeType) {
            $qb->andWhere('cr.type != :type')->setParameter('type', $excludeType);
        }

        return $qb;
    }

    /**
     * @param User $user
     * @return array
     */
    public function findAllPending(User $user)
    {
        $qb     =  $this
            ->createQueryBuilder('cr')
            ->where('cr.pending  = true')
            ->groupBy('cr.user')
            ->orderBy('cr.sortOrder', 'DESC')
            ->addOrderBy('cr.createdAt', 'ASC')
        ;

        $qb->andWhere($this->createCityAndMunicipalityOrExpr($qb, $user));

        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @param $inspected
     * @return array
     */
    public function findAllByInspected(User $user, $inspected)
    {
        if (count($user->getCities()) == 0 && count($user->getAdminMunicipalities()) == 0) {
            return [];
        }

        $qb = $this
            ->createQueryBuilder('cr')
            ->where('cr.inspected  = :inspected')
            ->groupBy('cr.user')
            ->addOrderBy('cr.createdAt', 'ASC')
            ->setParameter('inspected', $inspected)
        ;

        $qb->andWhere($this->createCityAndMunicipalityOrExpr($qb, $user));

        return $qb->getQuery()->getResult();
    }

    private function createCityAndMunicipalityOrExpr(QueryBuilder $qb, User $user)
    {
        $orX = $qb->expr()->orX();

        if (count($user->getCities()) > 0) {
            $orX->add('cr.type = :friendtype AND cr.city IN (:cities)');
            $qb
                ->setParameter('cities', $user->getCities())
                ->setParameter('friendtype', FriendTypes::FRIEND)
            ;
        }
        if (count($user->getAdminMunicipalities()) > 0) {
            $orX->add('cr.type = :starttype AND cr.municipality IN (:municipalities)');
            $qb
                ->setParameter('municipalities', $user->getAdminMunicipalities())
                ->setParameter('starttype', FriendTypes::START)
            ;
        }

        return $orX;
    }

    /**
     * @param Municipality $municipality
     *
     * @return ConnectionRequest[]
     */
    public function findWantToLearnStartFriendsByMunicipality(Municipality $municipality)
    {
        return $qb = $this->createQueryBuilder('cr')
            ->innerJoin('cr.user', 'u')
            ->where('cr.municipality = :municipality')
            ->andWhere('cr.wantToLearn = true')
            ->andWhere('cr.type = :type')
            ->andWhere('cr.inspected = true')
            ->andWhere('cr.pending = false')
            ->setParameter('municipality', $municipality)
            ->setParameter('type', FriendTypes::START)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param Municipality $municipality
     *
     * @return ConnectionRequest[]
     */
    public function findInspectedNotPendingStartFriendsByMunicipality(Municipality $municipality)
    {
        return $qb = $this->createQueryBuilder('cr')
            ->innerJoin('cr.user', 'u')
            ->leftJoin('cr.fluentSpeakerConnection', 'fsc')
            ->leftJoin('cr.learnerConnection', 'lc')
            ->where('cr.municipality = :municipality')
            ->andWhere('cr.type = :type')
            ->andWhere('cr.inspected = true')
            ->andWhere('cr.pending = false')
            ->andWhere('fsc.id IS NULL AND lc.id IS NULL')
            ->setParameter('municipality', $municipality)
            ->setParameter('type', FriendTypes::START)
            ->orderBy('cr.createdAt', 'DESC')
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     *
     * @return array
     */
    public function findConnectionRequestCountPerCity(\DateTimeInterface $from, \DateTimeInterface $to)
    {
        return $qb = $this->createQueryBuilder('cr')
            ->select('c.name as cityName, SUM(CASE WHEN (cr.wantToLearn = 0) THEN 0 ELSE 1 END) AS leanerCount, SUM(CASE WHEN (cr.wantToLearn = 1) THEN 0 ELSE 1 END) AS fluentSpeakerCount')
            ->leftJoin('cr.city' , 'c')
            ->where('cr.createdAt between :from and :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->groupBy('c.name')
            ->orderBy('c.name')
            ->getQuery()
            ->getArrayResult()
            ;
    }
}
