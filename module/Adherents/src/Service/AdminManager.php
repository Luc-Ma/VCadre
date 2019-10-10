<?php
namespace Adherents\Service;

use Adherents\Entity\User;
use Adherents\Entity\VcApec;

class AdminManager
{
    private $entityManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addAdmin($userId)
    {
        $user = $this->entityManager->getRepository(User::class)
                    ->findOneById($userId);

        if ($user === null) {
            return false;
        }
        $user->setAdmin(USER::USER_IS_ADMIN);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return true;
    }

    public function delAdmin($userId)
    {
        //make sure there is at least 1 admin left

        $users = $this->entityManager->getRepository(User::class)
                    ->getAdmin()->getResult();

        if ($users[0][1] <= 1) {
            return false;
        }

        //remove from admin
        $user = $this->entityManager->getRepository(User::class)
                    ->findOneById($userId);

        if ($user === null) {
            return false;
        }
        $user->setAdmin(USER::USER_NOT_ADMIN);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return true;
    }

    public function addApec($data)
    {
        $newApec = new VcApec();

        $newApec->setIntitule($data['intitule']);
        $this->entityManager->persist($newApec);
        $this->entityManager->flush();
    }

    public function delApec($ids)
    {
        $result = true;

        $apecs = $this->entityManager->getRepository(VcApec::class)
                            ->findById($ids);

        foreach ($apecs as $apec) {
            if ($apec->getMinicv()->count() > 0) {
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $this->entityManager->remove($apec); // delete it
        }

        //apply to db
        $this->entityManager->flush();

        return $result;
    }
}
