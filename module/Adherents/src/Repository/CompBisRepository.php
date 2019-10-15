<?php
namespace Adherents\Repository;

use Doctrine\ORM\EntityRepository;
use Adherents\Entity\VcCompBis;

/**
 * This is the custom repository class for User entity.
 */
class CompBisRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('metier' => 'ASC'));
    }

    public function findByMetier($metier)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('c')
            ->from(VcCompBis::class, 'c')
            ->andWhere('c.metier = ?1')
            ->setParameter('1', $metier);

        return $queryBuilder->getQuery()->getResult();
    }
}
