<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

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
     * @return array
     */
    public function findAllWithCategoryJoinAssoc()
    {
        $sql = "
            SELECT u.id, u.email, u.first_name, u.last_name, u.want_to_learn, u.gender, u.age, u.from_country,
                GROUP_CONCAT(uc.category_id) as category_ids
            FROM fos_user u
            LEFT JOIN users_categories uc on u.id = uc.user_id
            WHERE u.roles != 'a:0:{}'
            GROUP BY u.id
            ORDER BY u.id";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param User $user
     * @param array $criterias
     * @return array
     */
    public function findMatchArray(User $user, array $criterias)
    {
        $userParams = [
            'user_municipality'     => $user->getMunicipality()->getId(),
            'user_gender'           => $user->getGender(),
            'user_age'              => $user->getAge(),
            'user_children'         => $user->hasChildren(),
            'want_to_learn'         => ($user->getWantToLearn()? false: true),
            'user'                  => $user->getId(),
            'user_categories'       => array_values($user->getCategoryIds()),
            'user_music_categories' => array_values($user->getMusicCategoryIds())
        ];

        $qb = $this->createQueryBuilder('u');

        /*$qb
            //->select('u.id, (CASE WHEN(u.municipality=:user_municipality) THEN 2 ELSE 0 END) AS area_score, (CASE WHEN(u.hasChildren=:user_children) THEN 2 ELSE 0 END) AS children_score, (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END) AS gender_score, (CASE WHEN((u.age-:user_age)<5) THEN 2 ELSE 0 END) AS age_score, (COUNT(c.id)*3) cat_score, (COUNT(mc.id)) cat_score2, ((COUNT(c)*3) + (COUNT(mc.id)*3) + (CASE WHEN(u.municipality=:user_municipality) THEN 2 ELSE 0 END)+ (CASE WHEN((u.age-:user_age)<5) THEN 2 ELSE 0 END)+ (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END)+ (CASE WHEN(u.hasChildren=:user_children) THEN 2 ELSE 0 END)) AS score')
            ->select('u.id, (CASE WHEN(u.municipality=:user_municipality) THEN 2 ELSE 0 END) AS area_score, (CASE WHEN(u.hasChildren=:user_children) THEN 2 ELSE 0 END) AS children_score, (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END) AS gender_score, (CASE WHEN((u.age-:user_age)<5) THEN 2 ELSE 0 END) AS age_score, (SELECT COUNT(c.id) FROM u.categories c WHERE c.id IN (:user_categories)) cat_score')
            ->where($qb->expr()->neq('u.id'             , ':user'))
            ->andWhere($qb->expr()->neq('u.wantToLearn' , ':want_to_learn'))
            //->andWhere('c.id IN (:user_categories) OR mc.id IN (:user_music_categories)')
            //->andWhere('mc.id IN (:user_music_categories)')
            ->andWhere('cr.disqualified = false')
            ->join('u.connectionRequests', 'cr')
            //->leftJoin('u.categories', 'c')
            //->leftJoin('u.musicCategories', 'mc')
        ;*/

        $where  = $this->prepareMatchCriterias($criterias);
        $rsm    = new \Doctrine\ORM\Query\ResultSetMapping();
        /*$sql    = "SELECT * FROM (
              SELECT u.id, 0 AS cat_score2, (CASE WHEN(u.municipality_id=:user_municipality) THEN 2 ELSE 0 END) AS area_score, (CASE WHEN(u.has_children=:user_children) THEN 2 ELSE 0 END) AS children_score, (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END) AS gender_score, (CASE WHEN((u.age-:user_age)<5) THEN 2 ELSE 0 END) AS age_score, (COUNT(c.category_id)*3) cat_score, ((COUNT(c.category_id)*3) + (CASE WHEN(u.municipality_id=:user_municipality) THEN 2 ELSE 0 END)+ (CASE WHEN((u.age-:user_age)<5) THEN 2 ELSE 0 END)+ (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END)+ (CASE WHEN(u.has_children=:user_children) THEN 2 ELSE 0 END)) AS score
              FROM fos_user u
              JOIN connection_request cr
              ON cr.user_id = u.id
              LEFT JOIN users_categories c
              ON c.user_id = u.id
              WHERE u.id != :user
              AND u.want_to_learn != :want_to_learn
              AND c.category_id IN (:user_categories)
              AND $where
              UNION ALL
              SELECT u.id, 0 AS cat_score, (CASE WHEN(u.municipality_id=:user_municipality) THEN 2 ELSE 0 END) AS area_score, (CASE WHEN(u.has_children=:user_children) THEN 2 ELSE 0 END) AS children_score, (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END) AS gender_score, (CASE WHEN((u.age-:user_age)<5) THEN 2 ELSE 0 END) AS age_score, (COUNT(mc.category_id)*3) cat_score2, ((COUNT(mc.category_id)*3) + (CASE WHEN(u.municipality_id=:user_municipality) THEN 2 ELSE 0 END)+ (CASE WHEN((u.age-:user_age)<5) THEN 2 ELSE 0 END)+ (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END)+ (CASE WHEN(u.has_children=:user_children) THEN 2 ELSE 0 END)) AS score
              FROM fos_user u
              JOIN connection_request cr
              ON cr.user_id = u.id
              LEFT JOIN users_music_categories mc
              ON mc.user_id = u.id
              WHERE u.id != :user
              AND u.want_to_learn != :want_to_learn
              AND mc.category_id IN (:user_music_categories)
              AND $where
        ) temp
        ORDER BY temp.score DESC
        ";*/

        $userParams = [
            'user_municipality'     => $user->getMunicipality()->getId(),
            'user_gender'           => $user->getGender(),
            'user_age'              => $user->getAge(),
            'user_children'         => $user->hasChildren(),
            'want_to_learn'         => ($user->getWantToLearn()? false: true),
            'user'                  => $user->getId(),
            'user_categories'       => array_values($user->getCategoryIds()),
            'user_music_categories' => array_values($user->getMusicCategoryIds())
        ];

        $where  = $this->prepareMatchCriterias($criterias);
        $rsm    = new \Doctrine\ORM\Query\ResultSetMapping();
        $sql    = "SELECT *, (COALESCE(SUM(cat_score),0) + SUM(age_score) + SUM(area_score) + SUM(children_score) + SUM(gender_score) + COALESCE(SUM(music_cat_score),0)) AS score
              FROM
              (
                  SELECT u.id, (CASE WHEN(u.municipality_id=:user_municipality) THEN 2 ELSE 0 END) AS area_score, (CASE WHEN(u.has_children=:user_children) THEN 2 ELSE 0 END) AS children_score, (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END) AS gender_score, (CASE WHEN((u.age-:user_age)<5) THEN 2 ELSE 0 END) AS age_score, ((SELECT COUNT(users_categories.category_id) FROM users_categories WHERE users_categories.user_id = u.id AND users_categories.category_id IN (:user_categories) GROUP BY users_categories.user_id)*3) cat_score, ((SELECT COUNT(users_music_categories.category_id) FROM users_music_categories WHERE users_music_categories.user_id = u.id AND users_music_categories.category_id IN (:user_music_categories) GROUP BY users_music_categories.user_id)*3) AS music_cat_score
                  FROM fos_user u
                  JOIN connection_request cr
                  ON cr.user_id = u.id
                  LEFT JOIN users_categories c
                  ON c.user_id = u.id
                  LEFT JOIN users_music_categories mc
                  ON mc.user_id = u.id
                  WHERE u.id != :user
                  AND u.want_to_learn != :want_to_learn
                  AND $where
                  GROUP BY u.id
              ) temp
              GROUP BY temp.id
              ORDER BY score DESC
        ";

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('area_score', 'area_score');
        $rsm->addScalarResult('children_score', 'children_score');
        $rsm->addScalarResult('gender_score', 'gender_score');
        $rsm->addScalarResult('age_score', 'age_score');
        $rsm->addScalarResult('cat_score', 'cat_score');
        $rsm->addScalarResult('music_cat_score', 'music_cat_score');
        $rsm->addScalarResult('score', 'score');
        $query->setParameters(array_merge($criterias, $userParams));

        return $query->getArrayResult();
    }

    /**
     * @param array $criterias
     * @return string
     */
    private function prepareMatchCriterias(array $criterias)
    {
        $where  = [];
        $fields = array_keys($criterias);

        foreach($fields as $field) {
            if ($field === 'ageFrom' || $field === 'ageTo' || $field === 'category_id') {
                continue;
            }

            $where[] = 'u.'.$field .' = :'.$field;
        }

        if (isset($criterias['ageFrom']) && isset($criterias['ageTo'])) {
            $where[] = 'u.age BETWEEN :ageFrom AND :ageTo';
        }

        if (isset($criterias['category_id'])) {
            $where[] = '(c.category_id = :category_id OR mc.category_id = :category_id)';
        }

        return implode(' AND ', $where);
    }
}