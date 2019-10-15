<?php
namespace Adherents\Repository;

use Doctrine\ORM\EntityRepository;
use Adherents\Entity\VcSavoiretreList;

/**
 * This is the custom repository class for User entity.
 */
class SeRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('nom' => 'ASC'));
    }

    public function findBySe($se)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('s')
            ->from(VcSavoiretreList::class, 's')
            ->andWhere('s.savoiretre = ?1')
            ->setParameter('1', $se);

        return $queryBuilder->getQuery()->getResult();
    }
}
