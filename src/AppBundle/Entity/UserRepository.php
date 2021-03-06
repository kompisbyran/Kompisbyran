<?php
namespace AppBundle\Entity;

use AppBundle\Enum\MatchingProfileRequestTypes;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;
use AppBundle\Entity\ConnectionRequest;

/**
 * Class UserRepository
 * @package AppBundle\Entity
 */
class UserRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return User
     */
    public function save(User $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        return $user;
    }

    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @param User $user
     */
    public function softDelete(User $user)
    {
        $user->setFirstName('x');
        $user->setLastName('x');
        $user->setEnabled(false);
        $user->setEmail('x' . $user->getId());
        $user->setUsername('x' . $user->getId());
        $user->setUsernameCanonical('x' . $user->getId());
        $user->setPassword('xxxxxxx');

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array
     */
    public function findAllWithCategoryJoinAssoc()
    {
        $sql = "
            SELECT u.id, u.email, u.first_name, u.last_name, u.want_to_learn, u.gender, u.age, u.from_country,
                GROUP_CONCAT(DISTINCT uc.category_id) as category_ids,
                IF (
                    IFNULL(MAX(c1.created_at), '2000-01-01') > IFNULL(MAX(c2.created_at), '2000-01-01'),
                    MAX(c1.created_at),
                    MAX(c2.created_at)
                    ) as connection_created_at
            FROM fos_user u
            LEFT JOIN users_categories uc on u.id = uc.user_id
            LEFT JOIN connection c1 on u.id = c1.fluent_speaker_id
            LEFT JOIN connection c2 on u.id = c2.learner_id
            WHERE u.roles != 'a:0:{}'
            AND u.enabled = 1
            GROUP BY u.id
            ORDER BY u.id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param User $user
     * @param ConnectionRequest $userRequest
     * @param array $criterias
     * @return array
     */
    public function findMatchArray(User $user, ConnectionRequest $userRequest, array $criterias)
    {
        $userParams = [
            'user_municipality'     => $user->getMunicipality()->getId(),
            'user_gender'           => $user->getGender(),
            'user_age'              => $user->getAge(),
            'user_children'         => $user->hasChildren(),
            'want_to_learn'         => $userRequest->getWantToLearn(),
            'user'                  => $user->getId(),
            'user_categories'       => array_values($user->getCategoryIds()),
        ];

        if ($userRequest->getMatchingProfileRequestType()) {
            $criterias['matchingCriterias'] = $userRequest->getMatchingProfileRequestType();
        }

        $where  = $this->prepareMatchCriterias($criterias);
        $rsm    = new \Doctrine\ORM\Query\ResultSetMapping();
        $sql    = "SELECT *, (COALESCE(SUM(cat_score),0) + SUM(age_score) + SUM(area_score) + SUM(children_score) + SUM(gender_score) + SUM(newly_arrived_score)) AS score
              FROM
              (
                  SELECT u.id, cr.created_at AS connection_request_created_at, cr.pending, cr.id AS connection_request_id, (CASE WHEN(u.municipality_id=:user_municipality) THEN 2 ELSE 0 END) AS area_score, (CASE WHEN(u.has_children=true AND true=:user_children) THEN 2 ELSE 0 END) AS children_score, (CASE WHEN(u.newly_arrived=true) THEN 2 ELSE 0 END) AS newly_arrived_score, (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END) AS gender_score, (CASE WHEN((u.age-:user_age) BETWEEN -5 AND 5) THEN 2 ELSE 0 END) AS age_score, ((SELECT COUNT(users_categories.category_id) FROM users_categories WHERE users_categories.user_id = u.id AND users_categories.category_id IN (:user_categories) GROUP BY users_categories.user_id)*3) cat_score, u.newly_arrived
                  FROM fos_user u
                  JOIN connection_request cr
                  ON cr.user_id = u.id
                  LEFT JOIN users_categories c
                  ON c.user_id = u.id
                  LEFT JOIN connection fsc ON fsc.fluent_speaker_connection_request_id = cr.id
                  LEFT JOIN connection lc ON lc.learner_connection_request_id = cr.id
                  WHERE u.id != :user
                  AND cr.want_to_learn != :want_to_learn
                  AND cr.pending = false
                  AND cr.inspected = true
                  AND cr.disqualified = false
                  and u.enabled = true
                  AND (cr.matching_profile_request_type IS NULL OR cr.matching_profile_request_type != 'same_gender' OR u.gender = :user_gender)
                  AND fsc.id IS NULL
                  AND lc.id IS NULL
                  AND $where
                  GROUP BY u.id
              ) temp
              GROUP BY temp.id
              ORDER BY score DESC
        ";

        $query = $this->_em->createNativeQuery($sql, $rsm);

        $rsm->addScalarResult('id'                      , 'id');
        $rsm->addScalarResult('area_score'              , 'area_score');
        $rsm->addScalarResult('children_score'          , 'children_score');
        $rsm->addScalarResult('gender_score'            , 'gender_score');
        $rsm->addScalarResult('age_score'               , 'age_score');
        $rsm->addScalarResult('cat_score'               , 'cat_score');
        $rsm->addScalarResult('score'                   , 'score');
        $rsm->addScalarResult('pending'                 , 'pending');
        $rsm->addScalarResult('connection_request_id'   , 'connection_request_id');
        $rsm->addScalarResult('connection_request_created_at' , 'connection_request_created_at');
        $rsm->addScalarResult('newly_arrived' , 'newly_arrived');

        $this->setSearchParamaters(array_merge($criterias, $userParams), $query);

        return $query->getArrayResult();
    }

    /**
     * @param array $params
     * @param $query
     */
    private function setSearchParamaters(array $params, $query)
    {
        foreach($params as $key => $value) {
            if ($key == 'q') {
                $query->setParameter($key, "%$value%");

                continue;
            }

            $query->setParameter($key, $value);
        }
    }

    /**
     * @param array $criterias
     * @return string
     */
    private function prepareMatchCriterias(array $criterias)
    {
        $where  = ['u.enabled = true'];
        $fields = array_keys($criterias);
        foreach($fields as $field) {
            if ($field === 'ageFrom' || $field === 'ageTo' || $field === 'category_id' || $field === 'city_id' || $field === 'type' || $field === 'q' || $field == 'municipality_id' || $field == 'inspected' || $field == 'matchingCriterias') {
                continue;
            }
            $where[] = 'u.'.$field .' = :'.$field;
        }
        if (isset($criterias['ageFrom']) && isset($criterias['ageTo'])) {
            $where[] = 'u.age BETWEEN :ageFrom AND :ageTo';
        }
        if (isset($criterias['category_id'])) {
            $where[] = 'c.category_id = :category_id';
        }
        if (isset($criterias['city_id'])) {
            $where[] = 'cr.city_id = :city_id';
        }
        if (isset($criterias['type'])) {
            $where[] = 'cr.type = :type';
        }
        if (isset($criterias['inspected'])) {
            $where[] = 'cr.inspected = :inspected';
        }
        if (isset($criterias['q'])) {
            $where[] = 'u.about LIKE :q';
        }
        if (isset($criterias['municipality_id'])) {
            $where[] = 'cr.municipality_id = :municipality_id';
        }
        if (isset($criterias['matchingCriterias']) && $criterias['matchingCriterias'] == MatchingProfileRequestTypes::SAME_GENDER) {
            $where[] = 'u.gender = :user_gender';
        }

        return implode(' AND ', $where);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllAdmin()
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.roles LIKE :role_admin')
            ->orWhere('u.roles LIKE :role_super_admin')
            ->setParameter('role_admin', "%ROLE_ADMIN%")
            ->setParameter('role_super_admin', "%ROLE_SUPER_ADMIN%")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return User[]
     */
    public function findAllMunicipalityAdministrators()
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', "%ROLE_MUNICIPALITY%")
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param \DateTime $date
     * @return User[]
     */
    public function findIncompleteByCreatedDate(\DateTime $date)
    {
        $from = clone $date;
        $from->setTime(0, 0, 0);
        $to = clone $from;
        $to->setTime(23, 59, 59);

        return $this
            ->createQueryBuilder('u')
            ->where('u.roles = :role')
            ->andWhere('u.createdAt between :from and :to')
            ->andWhere('u.enabled = true')
            ->setParameter('role', serialize([]))
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param \DateTime $date
     *
     * @return User
     */
    public function findInactiveSince(\DateTime $date)
    {
        /** @var User[] $users */
        $users = $this
            ->createQueryBuilder('u')
            ->where('u.enabled = true')
            ->andWhere('u.createdAt < :date')
            ->andWhere('u.lastLogin IS NULL OR u.lastLogin < :date')
            ->andWhere('u.confirmedKeepDataAt IS NULL OR u.confirmedKeepDataAt < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
            ;

        $filteredUsers = [];
        foreach ($users as $user) {
            if ($user->hasOpenConnectionRequest() && !$user->getOpenConnectionRequest()->getInspected()) {
                continue;
            }
            if ($user->getMostRecentConnection() && $user->getMostRecentConnection() > $date) {
                continue;
            }

            $filteredUsers[] = $user;
        }

        return $filteredUsers;
    }
}
