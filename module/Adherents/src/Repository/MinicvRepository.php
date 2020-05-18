<?php
namespace Adherents\Repository;

use Doctrine\ORM\EntityRepository;
use Adherents\Entity\VcMinicv;

/**
 * This is the custom repository class for User entity.
 */
class MinicvRepository extends EntityRepository
{
    public function findToValidate()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('m')
            ->from(VcMinicv::class, 'm')
            ->andWhere('m.complet = ?1')
            ->andWhere('m.valid = ?2')
            ->setParameter('2', VcMinicv::PROFIL_INVALID)
            ->setParameter('1', VcMinicv::PROFIL_IS_COMPLETE);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findValidated()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('m')
            ->from(VcMinicv::class, 'm')
            ->andWhere('m.complet = ?1')
            ->andWhere('m.valid = ?2')
            ->setParameter('2', VcMinicv::PROFIL_IS_VALID)
            ->setParameter('1', VcMinicv::PROFIL_IS_COMPLETE);

        return $queryBuilder->getQuery()->getResult();
    }

}
