<?php
namespace Adherents\Service;

use Adherents\Entity\User;
use Adherents\Entity\VcApec;
use Adherents\Entity\VcMetier;
use Adherents\Entity\VcComp;
use Adherents\Entity\VcCompBis;
use Adherents\Entity\VcSecteur;
use Adherents\Entity\VcSavoiretre;
use Adherents\Entity\VcSavoiretreList;

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

    public function addSecteur($data)
    {
        $newSecteur = new VcSecteur();

        $newSecteur->setNom($data['nom']);
        $newSecteur->setType($data['type']);
        $this->entityManager->persist($newSecteur);
        $this->entityManager->flush();
    }

    public function delSecteur($ids)
    {
        $result = true;

        $secteurs = $this->entityManager->getRepository(VcSecteur::class)
                            ->findById($ids);

        foreach ($secteurs as $secteur) {
            if ($secteur->getMinicv()->count() > 0) {
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $this->entityManager->remove($secteur); // delete it
        }

        //apply to db
        $this->entityManager->flush();

        return $result;
    }

    public function addSeCat($data)
    {
        $newSeCat = new VcSavoiretre();

        $newSeCat->setNom($data['nom']);
        $this->entityManager->persist($newSeCat);
        $this->entityManager->flush();
    }

    public function addSe($data)
    {
        $seCat = $this->entityManager->getRepository(VcSavoiretre::class)
                            ->findOneById($data['secat']);
        if ($seCat === null) {
            return false;
        }

        $newSe = new VcSavoiretreList();

        $newSe->setNom($data['nom']);
        $newSe->setSavoiretre($seCat);

        $this->entityManager->persist($newSe);
        $this->entityManager->flush();
    }

    public function delSeCat($ids)
    {
        $result = true;

        $seCats = $this->entityManager->getRepository(VcSavoiretre::class)
                            ->findById($ids);

        foreach ($seCats as $seCat) {
            if ($seCat->getSeList()->count() > 0) {
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $this->entityManager->remove($seCat); // delete it
        }

        //apply to db
        $this->entityManager->flush();

        return $result;
    }

    public function delSe($ids)
    {
        $result = true;

        $ses = $this->entityManager->getRepository(VcSavoiretreList::class)
                            ->findById($ids);

        foreach ($ses as $se) {
            if ($se->getMinicv()->count() > 0) {
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $this->entityManager->remove($se); // delete it
        }

        //apply to db
        $this->entityManager->flush();

        return $result;
    }
}
