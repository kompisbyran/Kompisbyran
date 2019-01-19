<?php

namespace AppBundle\Entity;

use AppBundle\Enum\ConnectionMeetingVariantTypes;
use AppBundle\Enum\FriendTypes;
use AppBundle\Enum\MeetingTypes;
use AppBundle\Form\Model\SearchConnection;
use Doctrine\ORM\EntityRepository;

/**
 * Class ConnectionRepository
 * @package AppBundle\Entity
 */
class ConnectionRepository extends EntityRepository
{

    /**
     * @param String $city
     * @param String $year
     * @param String $type
     * @return array
     */
    public function getMatches($city, $year, $type)
    {
        $query = "
            SELECT c.created_at
            FROM connection c
            WHERE YEAR(c.created_at) = :year";

        $params['year'] = $year;

        if ($city !== "") {
            $query .= " AND c.city_id = :city";
            $params['city'] = $city;
        }

        if ($type !== "") {
            $query .= " AND c.type = :type";
            $params['type'] = $type;
        }

        $query .= " ORDER BY c.created_at ASC";

        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function getYearSpan()
    {
        // get the years in a unique list
        $query = "
            SELECT DISTINCT(SUBSTRING(c.created_at, 1, 4)) as year
            FROM connection c
            ORDER BY year";
        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        $stmt->execute();

        return array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'year');
    }

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
     * @param SearchConnection $searchConnection
     * @param User $user
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getFindAllQueryBuilderForUser(SearchConnection $searchConnection, User $user)
    {
        $cities = $this->getEntityManager()->getRepository(City::class)
            ->createQueryBuilder('c')
            ->innerJoin('c.users', 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();

        $municipalities = $this->getEntityManager()->getRepository(Municipality::class)
            ->createQueryBuilder('m')
            ->innerJoin('m.adminUsers', 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();

        $qb = $this
            ->createQueryBuilder('c')
            ->select('c, f, l, cb, c2, u')
            ->innerJoin('c.fluentSpeaker', 'f')
            ->innerJoin('c.learner', 'l')
            ->innerJoin('c.createdBy', 'cb')
            ->leftJoin('c.city', 'city')
            ->leftJoin('c.municipality', 'm')
            ->leftJoin('c.comments', 'c2')
            ->leftJoin('c2.user', 'u')
            ->andWhere('c.city IS NULL OR city.id IN (:cities)')
            ->andWhere('c.municipality IS NULL OR m.id IN (:municipalities)')
            ->setParameter('cities', $cities)
            ->setParameter('municipalities', $municipalities)
        ;

        if ($searchConnection->getCity()) {
            $qb
                ->andWhere('c.city = :city')
                ->setParameter('city', $searchConnection->getCity());
        }
        if ($searchConnection->getMunicipality()) {
            $qb
                ->andWhere('c.municipality = :municipality')
                ->setParameter('municipality', $searchConnection->getMunicipality());
        }
        if ($searchConnection->getQ()) {
            $qb
                ->andwhere("
                    f.email LIKE :searchString
                    OR l.email LIKE :searchString
                    OR CONCAT(CONCAT(f.firstName, ' '), f.lastName) LIKE :searchString
                    OR CONCAT(CONCAT(l.firstName, ' '), l.lastName) LIKE :searchString
                ")
                ->setParameter('searchString', '%'.$searchConnection->getQ().'%')
            ;
        }
        if ($searchConnection->getFrom()) {
            $qb->andWhere('c.createdAt >= :from')->setParameter('from', $searchConnection->getFrom());
        }
        if ($searchConnection->getTo()) {
            $qb->andWhere('c.createdAt <= :to')->setParameter('to', $searchConnection->getTo());
        }
        if ($searchConnection->isOnlyNewlyArrived()) {
            $qb->andWhere('c.newlyArrived = true');
        }
        if ($searchConnection->getMeetingStatus() == ConnectionMeetingVariantTypes::ONE_MARKED_AS_MET) {
            $qb->andWhere('
                (c.fluentSpeakerMeetingStatus = :status and c.learnerMeetingStatus != :status)
                or
                (c.fluentSpeakerMeetingStatus != :status and c.learnerMeetingStatus = :status)
            ')->setParameter('status', MeetingTypes::MET);
        }
        if ($searchConnection->getMeetingStatus() == ConnectionMeetingVariantTypes::BOTH_MARKED_AS_MET) {
            $qb
                ->andWhere('c.fluentSpeakerMeetingStatus = :status and c.learnerMeetingStatus = :status')
                ->setParameter('status', MeetingTypes::MET)
            ;
        }
        if ($searchConnection->getType()) {
            $qb
                ->andWhere('c.type = :type')
                ->setParameter('type', $searchConnection->getType())
            ;
        }

        return $qb
            ->orderBy('c.id', 'desc')
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

        return $qb->getQuery()->getSingleScalarResult()? true: false;
    }

    /**
     * @param Municipality $municipality
     *
     * @return Connection
     */
    public function findStartFriendsByMunicipality(Municipality $municipality)
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.type = :type')
            ->andWhere('c.municipality = :municipality')
            ->setParameter('type', FriendTypes::START)
            ->setParameter('municipality', $municipality)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param \DateTime $createdAt
     * @param int $previousMailsCount
     *
     * @return Connection[]
     */
    public function findForMeetingConfirmation(\DateTime $createdAt, $previousMailsCount)
    {
        $from = clone $createdAt;
        $from->setTime(0, 0, 0);
        $to = clone $from;
        $to->setTime(23, 59, 59);

        return $this
            ->createQueryBuilder('c')
            ->andWhere('
                c.fluentSpeakerMeetingStatus = :statusUnknown or c.fluentSpeakerMeetingStatus = :statusNotYetMet
                or c.learnerMeetingStatus = :statusUnknown or c.learnerMeetingStatus = :statusNotYetMet
            ')
            ->andWhere('c.createdAt between :from and :to')
            ->andWhere('
                c.fluentSpeakerMeetingStatusEmailsCount = :previousMailsCount
                or c.learnerMeetingStatusEmailsCount = :previousMailsCount
            ')
            ->setParameter('statusUnknown', MeetingTypes::UNKNOWN)
            ->setParameter('statusNotYetMet', MeetingTypes::NOT_YET_MET)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('previousMailsCount', $previousMailsCount)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param \DateTime $markedAsMetAt
     *
     * @return Connection[]
     */
    public function findForMeetingFollowUpEmail2(\DateTime $markedAsMetAt)
    {
        $from = clone $markedAsMetAt;
        $from->setTime(0, 0, 0);
        $to = clone $from;
        $to->setTime(23, 59, 59);

        return $this
            ->createQueryBuilder('c')
            ->andWhere('
                c.fluentSpeakerMeetingStatus = :status
                or c.learnerMeetingStatus = :status
            ')
            ->andWhere('
                c.fluentSpeakerMarkedAsMetCreatedAt between :from and :to
                or c.learnerMarkedAsMetCreatedAt between :from and :to
            ')
            ->andWhere('
                c.fluentSpeakerFollowUpEmail2Count = 0
                or c.learnerFollowUpEmail2Count = 0
            ')
            ->setParameter('status', MeetingTypes::MET)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param \DateTime $markedAsMetAt
     *
     * @return Connection[]
     */
    public function findForMeetAgain(\DateTime $markedAsMetAt)
    {
        $from = clone $markedAsMetAt;
        $from->setTime(0, 0, 0);
        $to = clone $from;
        $to->setTime(23, 59, 59);

        return $this
            ->createQueryBuilder('c')
            ->innerJoin('c.fluentSpeaker', 'u')
            ->andWhere('c.fluentSpeakerMeetingStatus = :metStatus')
            ->andWhere('c.createdAt between :from and :to')
            ->andWhere('u.enabled = true')
            ->setParameter('metStatus', MeetingTypes::MET)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param \DateTime $markedAsMetAt
     *
     * @return Connection[]
     */
    public function findForMeetingFollowUpEmail3(\DateTime $markedAsMetAt)
    {
        $from = clone $markedAsMetAt;
        $from->setTime(0, 0, 0);
        $to = clone $from;
        $to->setTime(23, 59, 59);

        return $this
            ->createQueryBuilder('c')
            ->andWhere('
                c.fluentSpeakerMeetingStatus = :status
                or c.learnerMeetingStatus = :status
            ')
            ->andWhere('
                c.fluentSpeakerMarkedAsMetCreatedAt between :from and :to
                or c.learnerMarkedAsMetCreatedAt between :from and :to
            ')
            ->setParameter('status', MeetingTypes::MET)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->execute()
            ;

    }

    /**
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     *
     * @return Connection[]
     */
    public function findConfirmedBetweenDates(\DateTimeInterface $from, \DateTimeInterface $to)
    {
        $qb = $this->createQueryBuilder('c');

        $orX = $qb->expr()->orX();
        $andX1 = $qb->expr()->andX();
        $andX2 = $qb->expr()->andX();

        $andX1->add('c.learnerMarkedAsMetCreatedAt > c.fluentSpeakerMarkedAsMetCreatedAt');
        $andX1->add('c.learnerMarkedAsMetCreatedAt between :from and :to');
        $andX2->add('c.fluentSpeakerMarkedAsMetCreatedAt > c.learnerMarkedAsMetCreatedAt');
        $andX2->add('c.fluentSpeakerMarkedAsMetCreatedAt between :from and :to');
        $orX->add($andX1);
        $orX->add($andX2);

        $qb
            ->innerJoin('c.learner', 'l')
            ->innerJoin('c.fluentSpeaker', 'f')
            ->leftJoin('c.city', 'city')
            ->leftJoin('c.municipality', 'm')
            ->where('c.learnerMarkedAsMetCreatedAt IS NOT NULL')
            ->andWhere('c.fluentSpeakerMarkedAsMetCreatedAt IS NOT NULL')
            ->andWhere($orX)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ;

        return $qb->getQuery()->execute();
    }
}
