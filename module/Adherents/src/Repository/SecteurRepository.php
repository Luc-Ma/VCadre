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
}
