<?php
namespace Adherents\Repository;

use Doctrine\ORM\EntityRepository;
use Adherents\Entity\VcLog;

/**
 * This is the custom repository class for VcLog entity.
 */
class LogRepository extends EntityRepository
{
    public function findAll()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('l')
            ->from(VcLog::class, 'l')
            ->orderBy('l.id', 'DESC');

        return $queryBuilder->getQuery();
    }
}
