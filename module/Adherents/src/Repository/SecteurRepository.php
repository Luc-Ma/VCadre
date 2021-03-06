<?php
namespace Adherents\Repository;

use Doctrine\ORM\EntityRepository;
use Adherents\Entity\VcSecteur;

/**
 * This is the custom repository class for User entity.
 */
class SecteurRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('type' => 'ASC'));
    }

    public function findBySecteur($secteur)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('s')
            ->from(VcSecteur::class, 's')
            ->andWhere('s.type = ?1')
            ->setParameter('1', $secteur);

        return $queryBuilder->getQuery()->getResult();
    }
}
