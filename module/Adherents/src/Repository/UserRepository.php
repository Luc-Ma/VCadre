<?php
namespace Adherents\Repository;

use Doctrine\ORM\EntityRepository;
use Adherents\Entity\User;

/**
 * This is the custom repository class for User entity.
 */
class UserRepository extends EntityRepository
{
    public function getAdmin()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('count(u.id)')
            ->from(User::class, 'u')
            ->andWhere('u.admin = ?1')
            ->setParameter('1', User::USER_IS_ADMIN);

        return $queryBuilder->getQuery();
    }
}
