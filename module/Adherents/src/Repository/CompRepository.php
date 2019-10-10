<?php
namespace Adherents\Repository;

use Doctrine\ORM\EntityRepository;
use Adherents\Entity\VcComp;

/**
 * This is the custom repository class for User entity.
 */
class CompRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('metier' => 'ASC'));
    }
}
