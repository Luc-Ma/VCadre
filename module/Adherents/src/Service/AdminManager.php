<?php
namespace Adherents\Service;

use Adherents\Entity\User;
use Adherents\Entity\VcApec;
use Adherents\Entity\VcMetier;
use Adherents\Entity\VcComp;
use Adherents\Entity\VcCompBis;

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

    public function addMetier($data)
    {
        $newMetier = new VcMetier();

        $newMetier->setNom($data['intitule']);
        $this->entityManager->persist($newMetier);
        $this->entityManager->flush();
    }

    public function delMetier($ids)
    {
        $result = true;

        $metiers = $this->entityManager->getRepository(VcMetier::class)
                            ->findById($ids);

        foreach ($metiers as $metier) {
            if ($metier->getComp()->count() > 0 || $metier->getCompBis()->count() > 0) {
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $this->entityManager->remove($metier); // delete it
        }

        //apply to db
        $this->entityManager->flush();

        return $result;
    }

    public function addComp($data)
    {
        $metier = $this->entityManager->getRepository(VcMetier::class)
                            ->findOneById($data['metier']);
        if ($metier === null) {
            return false;
        }

        $newComp = new VcComp();

        $newComp->setNom($data['nom']);
        $newComp->setMetier($metier);
        $this->entityManager->persist($newComp);
        $this->entityManager->flush();
    }

    public function addCompBis($data)
    {
        $metier = $this->entityManager->getRepository(VcMetier::class)
                            ->findOneById($data['metier']);
        if ($metier === null) {
            return false;
        }

        $newCompBis = new VcCompBis();

        $newCompBis->setNom($data['nom']);
        $newCompBis->setMetier($metier);
        $this->entityManager->persist($newCompBis);
        $this->entityManager->flush();
    }
    public function delComp($ids)
    {
        $result = true;

        $comps = $this->entityManager->getRepository(VcComp::class)
                            ->findById($ids);

        foreach ($comps as $comp) {
            if ($comp->getMinicv()->count() > 0) {
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $this->entityManager->remove($comp); // delete it
        }

        //apply to db
        $this->entityManager->flush();

        return $result;
    }

    public function delCompBis($ids)
    {
        $result = true;

        $compBiss = $this->entityManager->getRepository(VcCompBis::class)
                            ->findById($ids);

        foreach ($compBiss as $compBis) {
            if ($compBis->getMinicv()->count() > 0) {
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $this->entityManager->remove($compBis); // delete it
        }

        //apply to db
        $this->entityManager->flush();

        return $result;
    }
}
