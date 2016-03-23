<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

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
            'user_municipality' => $user->getMunicipality(),
            'user_gender'       => $user->getGender(),
            'user_age'          => $user->getAge(),
            'user_children'     => $user->hasChildren(),
            'want_to_learn'     => ($user->getWantToLearn()? false: true),
            'user'              => $user->getId()
        ];

        $qb = $this->createQueryBuilder('u');

        $qb
            ->select('u.id, ((CASE WHEN(u.municipality=:user_municipality) THEN 2 ELSE 0 END)+ (CASE WHEN((u.age-:user_age)<5) THEN 2 ELSE 0 END)+ (CASE WHEN(u.gender=:user_gender) THEN 1 ELSE 0 END)+ (CASE WHEN(u.hasChildren=:user_children) THEN 2 ELSE 0 END)) AS score')
            ->where($qb->expr()->neq('u.id'             , ':user'))
            ->andWhere($qb->expr()->neq('u.wantToLearn' , ':want_to_learn'))
        ;

        $this->prepareMatchCriterias($qb, $criterias);

        $qb
            ->setParameters(array_merge($criterias, $userParams))
            ->groupBy('u.id')
            ->orderBy('score', 'DESC')
        ;

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param $qb
     * @param array $criterias
     */
    private function prepareMatchCriterias($qb, array $criterias)
    {
        $fields = array_keys($criterias);

        foreach($fields as $field) {
            if ($field === 'ageFrom' || $field === 'ageTo') {
                continue;
            }

            $qb->andWhere('u.'.$field .' = :'.$field);
        }

        if (isset($criterias['ageFrom']) && isset($criterias['ageTo'])) {
            $qb->andWhere($qb->expr()->between(
                'u.age',
                ':ageFrom',
                ':ageTo'
            ));
        }
    }
}
