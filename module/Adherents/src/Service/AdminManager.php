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
use Adherents\Entity\VcContrat;
use Adherents\Entity\VcDispo;
use Adherents\Entity\VcMobilite;
use Adherents\Entity\VcMinicv;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;


class AdminManager
{
    private $entityManager;
    private $logManager;
    private $authService;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $logManager, $authService)
    {
        $this->entityManager = $entityManager;
        $this->logManager = $logManager;
        $this->authService = $authService;
    }

    public function changeMinicvValid($id)
    {
        $minicv = $this->entityManager->getRepository(VcMinicv::class)
                            ->findOneById($id);

        if ($minicv === null) {
            return false;
        }

        $state = $minicv->getValid();

        if ($state == VcMinicv::PROFIL_IS_VALID) {
            $minicv->setPublish(VcMinicv::PROFIL_IS_PUBLIC);
            $minicv->setValid(VcMinicv::PROFIL_INVALID);

        } else {
            $minicv->setValid(VcMinicv::PROFIL_IS_VALID);
        }

        $this->entityManager->persist($minicv);
        $this->entityManager->flush();

        $log = $minicv->getUser()->getLastname()." ".$minicv->getUser()->getFirstname()." A son profil ";
        $log .= $state == VcMinicv::PROFIL_IS_VALID ? "Invalidé" : "Validé";
        $this->logManager->addLog($log);
        $subject = "Votre miniCV est validé";
        $body = "Votre minicv est validé et est maintenant disponible publiquement";
        $this->sendMail($minicv->getUser()->getEmail(),$subject,$body);
        return true;
    }

    private function sendMail($usermail,$subject,$body)
    {
        $log = "envoie mail à ".$usermail;
        $this->logManager->addLog($log);
        mail($usermail,$subject,$msg);
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

        $log = $user->getLastname()." ".$user->getFirstname()." ajouté en tant que administrateur ";
        $this->logManager->addLog($log);
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

        $log = $user->getLastname()." ".$user->getFirstname()." suprimé des administrateurs ";
        $this->logManager->addLog($log);
        return true;
    }

    public function addApec($data)
    {
        $newApec = new VcApec();

        $newApec->setIntitule($data['intitule']);
        $this->entityManager->persist($newApec);
        $this->entityManager->flush();

        $log = "Ajout APEC : ".$data['intitule'];
        $this->logManager->addLog($log);
    }

    public function delApec($ids)
    {
        $result = true;
        $log = "Supression APEC : ";
        $apecs = $this->entityManager->getRepository(VcApec::class)
                            ->findById($ids);

        foreach ($apecs as $apec) {
            if ($apec->getMinicv()->count() > 0) {
                $log .= $apec->getIntitule()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $apec->getIntitule()." => suprimé ";
            $this->entityManager->remove($apec); // delete it
        }

        //apply to db
        $this->entityManager->flush();
        $this->logManager->addLog($log);
        return $result;
    }

    public function addMetier($data)
    {
        $newMetier = new VcMetier();

        $newMetier->setNom($data['intitule']);
        $this->entityManager->persist($newMetier);
        $this->entityManager->flush();

        $log = "Ajout METIER : ".$data['intitule'];
        $this->logManager->addLog($log);
    }

    public function delMetier($ids)
    {
        $result = true;
        $log = "Supression METIER : ";
        $metiers = $this->entityManager->getRepository(VcMetier::class)
                            ->findById($ids);

        foreach ($metiers as $metier) {
            if ($metier->getComp()->count() > 0 || $metier->getCompBis()->count() > 0) {
                $log .= $metier->getNom()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $metier->getNom()." => suprimé ";
            $this->entityManager->remove($metier); // delete it
        }

        //apply to db
        $this->entityManager->flush();
        $this->logManager->addLog($log);
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

        $log = "Ajout COMPETENCE : ".$data['nom'];
        $this->logManager->addLog($log);
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

        $log = "Ajout COMPETENCE complémentaire : ".$data['nom'];
        $this->logManager->addLog($log);
    }

    public function delComp($ids)
    {
        $result = true;
        $log = "Supression COMPETENCE : ";
        $comps = $this->entityManager->getRepository(VcComp::class)
                            ->findById($ids);

        foreach ($comps as $comp) {
            if ($comp->getMinicv()->count() > 0) {
                $log .= $comp->getNom()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $comp->getNom()." => suprimé ";
            $this->entityManager->remove($comp); // delete it
        }

        //apply to db
        $this->entityManager->flush();

        $this->logManager->addLog($log);

        return $result;
    }

    public function delCompBis($ids)
    {
        $result = true;
        $log = "Supression COMPETENCE complémentaire : ";
        $compBiss = $this->entityManager->getRepository(VcCompBis::class)
                            ->findById($ids);

        foreach ($compBiss as $compBis) {
            if ($compBis->getMinicv()->count() > 0) {
                $log .= $compBis->getNom()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $compBis->getNom()." => suprimé ";
            $this->entityManager->remove($compBis); // delete it
        }

        //apply to db
        $this->entityManager->flush();
        $this->logManager->addLog($log);
        return $result;
    }

    public function addSecteur($data)
    {
        $newSecteur = new VcSecteur();

        $newSecteur->setNom($data['nom']);
        $newSecteur->setType($data['type']);
        $this->entityManager->persist($newSecteur);
        $this->entityManager->flush();

        $log = "Ajout SECTEUR : ".$data['nom'];
        $this->logManager->addLog($log);
    }

    public function delSecteur($ids)
    {
        $result = true;
        $log = "Supression SECTEUR : ";
        $secteurs = $this->entityManager->getRepository(VcSecteur::class)
                            ->findById($ids);

        foreach ($secteurs as $secteur) {
            if ($secteur->getMinicv()->count() > 0) {
                $log .= $secteur->getNom()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $secteur->getNom()." => suprimé ";
            $this->entityManager->remove($secteur); // delete it
        }

        //apply to db
        $this->entityManager->flush();
        $this->logManager->addLog($log);
        return $result;
    }

    public function addSeCat($data)
    {
        $newSeCat = new VcSavoiretre();

        $newSeCat->setNom($data['nom']);
        $this->entityManager->persist($newSeCat);
        $this->entityManager->flush();

        $log = "Ajout CATEGORIE SAVOIR ETRE : ".$data['nom'];
        $this->logManager->addLog($log);
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

        $log = "Ajout SAVOIR ETRE : ".$data['nom'];
        $this->logManager->addLog($log);
    }

    public function delSeCat($ids)
    {
        $result = true;
        $log = "Supression CAT SAVOIR ETRE : ";
        $seCats = $this->entityManager->getRepository(VcSavoiretre::class)
                            ->findById($ids);

        foreach ($seCats as $seCat) {
            if ($seCat->getSeList()->count() > 0) {
                $log .= $seCat->getNom()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $seCat->getNom()." => suprimé ";
            $this->entityManager->remove($seCat); // delete it
        }

        //apply to db
        $this->entityManager->flush();
        $this->logManager->addLog($log);
        return $result;
    }

    public function delSe($ids)
    {
        $result = true;
        $log = "Supression SAVOIR ETRE : ";
        $ses = $this->entityManager->getRepository(VcSavoiretreList::class)
                            ->findById($ids);

        foreach ($ses as $se) {
            if ($se->getMinicv()->count() > 0) {
                $log .= $se->getNom()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $se->getNom()." => suprimé ";
            $this->entityManager->remove($se); // delete it
        }

        //apply to db
        $this->entityManager->flush();
        $this->logManager->addLog($log);
        return $result;
    }

    public function addContrat($data)
    {
        $newContrat = new VcContrat();

        $newContrat->setType($data['type']);
        $this->entityManager->persist($newContrat);
        $this->entityManager->flush();

        $log = "Ajout Contrat : ".$data['type'];
        $this->logManager->addLog($log);
    }

    public function delContrat($ids)
    {
        $result = true;
        $log = "Supression Contrat : ";
        $contrats = $this->entityManager->getRepository(VcContrat::class)
                            ->findById($ids);

        foreach ($contrats as $contrat) {
            if ($contrat->getMinicv()->count() > 0) {
                $log .= $contrat->getType()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $contrat->getType()." => suprimé ";
            $this->entityManager->remove($contrat); // delete it
        }

        //apply to db
        $this->entityManager->flush();
        $this->logManager->addLog($log);
        return $result;
    }

    public function addDispo($data)
    {
        $newDispo = new VcDispo();

        $newDispo->setDispo($data['dispo']);
        $this->entityManager->persist($newDispo);
        $this->entityManager->flush();

        $log = "Ajout Disponibilité : ".$data['dispo'];
        $this->logManager->addLog($log);
    }

    public function delDispo($ids)
    {
        $result = true;
        $log = "Supression disponibilité : ";
        $dispos = $this->entityManager->getRepository(VcDispo::class)
                            ->findById($ids);

        foreach ($dispos as $dispo) {
            if ($dispo->getMinicv()->count() > 0) {
                $log .= $dispo->getDispo()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $dispo->getDispo()." => suprimé ";
            $this->entityManager->remove($dispo); // delete it
        }

        //apply to db
        $this->entityManager->flush();
        $this->logManager->addLog($log);
        return $result;
    }

    public function addMob($data)
    {
        $newMob = new VcMobilite();

        $newMob->setMobilite($data['mob']);
        $this->entityManager->persist($newMob);
        $this->entityManager->flush();

        $log = "Ajout Mobilité : ".$data['mob'];
        $this->logManager->addLog($log);
    }

    public function delMob($ids)
    {
        $result = true;
        $log = "Supression Mobilité : ";
        $mobs = $this->entityManager->getRepository(VcMobilite::class)
                            ->findById($ids);

        foreach ($mobs as $mob) {
            if ($mob->getMinicv()->count() > 0) {
                $log .= $mob->getMobilite()." => refus ";
                $result = false; // partial delete
                continue; // skip this one don't delete it, it's under use
            }
            $log .= $mob->getMobilite()." => suprimé ";
            $this->entityManager->remove($mob); // delete it
        }

        //apply to db
        $this->entityManager->flush();
        $this->logManager->addLog($log);
        return $result;
    }
}
