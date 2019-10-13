<?php
namespace Adherents\Service;

use Adherents\Entity\VcUpload;
use Adherents\Entity\VcMinicv;
use Adherents\Entity\VcApec;
use Doctrine\DBAL\Types\DateTimeType;

class AdherentsManager
{
    private $entityManager;
    private $logManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $logManager)
    {
        $this->entityManager = $entityManager;
        $this->logManager = $logManager;
    }

    public function addMinicv($data, $user)
    {
        $newMinicv = new VcMinicv();
        $apec = $this->entityManager->getRepository(VcApec::class)
                    ->findOneById($data['apec']);

        $newMinicv->setUser($user);
        $newMinicv->setApec($apec);
        $newMinicv->setIntitule($data['intitule']);
        $newMinicv->setStep(1);
        $newMinicv->setDateCreation(new \DateTime("now"));
        $newMinicv->setProfil(VcMinicv::PROFIL_IS_PRIMARY);
        $newMinicv->setValid(VcMinicv::PROFIL_INVALID);
        $newMinicv->setComplet(VcMinicv::PROFIL_INCOMPLETE);
        $newMinicv->setPublish(VcMinicv::PROFIL_IS_PRIVATE);

        $this->entityManager->persist($newMinicv);
        $this->entityManager->flush();

        $log = $user->getLastname()." ".$user->getFirstname();
        $log .= " viens de créer la premiere étape de son nouveau minicv ".$data['intitule'];
        $this->logManager->addLog($log);

        return $user->getMinicv()->last()->getId();
    }

    public function addUpload($data, $user)
    {
        $newUpload = new VcUpload();

        $filename = str_replace("./public/cv/", "", $data['cv']['tmp_name']);
        $newUpload->setFile($filename);
        $newUpload->setUser($user);

        $this->entityManager->persist($newUpload);
        $this->entityManager->flush();

        $log = $user->getLastname()." ".$user->getFirstname()." à uploader son cv";
        $this->logManager->addLog($log);
    }

    public function delUpload($user)
    {
        $upload = $user->getUpload()->first();

        $file = $upload->getFile();
        unlink("./public/cv/".$file);

        $this->entityManager->remove($upload);
        $this->entityManager->flush();

        $log = $user->getLastname()." ".$user->getFirstname()." à suprimé son cv";
        $this->logManager->addLog($log);
        return true;
    }

    public function changeUploadState($state, $user)
    {
        if ($state == 0) { //passage en privé
            $user->getUpload()->first()->setPublic(VcUpload::CV_PRIVE);
        } else {
            $user->getUpload()->first()->setPublic(VcUpload::CV_PUBLIC);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $log = $user->getLastname()." ".$user->getFirstname()." viens de passer son cv en ";
        $log .= $state ? "public" : "privé" ;
        $this->logManager->addLog($log);

        return true;
    }

    public function changeMinicvState($id, $user)
    {
        $minicv = $this->entityManager->getRepository(VcMinicv::class)
                            ->findOneById($id);
        //sanity check
        if (!$user->getMinicv()->contains($minicv)) {
            return false;
        }
        if (!$minicv->getValid()) {
            return false;
        }

        $state = $minicv->getPublish();
        if ($state == VcMinicv::PROFIL_IS_PUBLIC) {
            $minicv->setPublish(VcMinicv::PROFIL_IS_PRIVATE);
        } else {
            $minicv->setPublish(VcMinicv::PROFIL_IS_PUBLIC);
        }
        $this->entityManager->persist($minicv);
        $this->entityManager->flush();

        $log = $user->getLastname()." ".$user->getFirstname()." viens de passer son minicv ".$minicv->getIntitule()." en ";
        $log .= $state ? "public" : "privé" ;
        $this->logManager->addLog($log);

        return true;
    }

    public function continueMinicv($id, $data, $step, $user)
    {
        $minicv = $this->entityManager->getRepository(VcMinicv::class)
                            ->findOneById($id);
        if (!$user->getMinicv()->contains($minicv)) {
            return false;
        }

        switch ($step) {
            case 1: // form 2
                $minicv->setExperiencePoste($data['xp']);
                $minicv->setExperienceTotal($data['xptot']);
                $minicv->setFormation($data['formation']);
                $minicv->setStep(2);
                break;

            default:
                break;
        }
        $this->entityManager->persist($minicv);
        $this->entityManager->flush();

        $log = $user->getLastname()." ".$user->getFirstname();
        $log .= " à complété l'étape ".$step." de son minicv ".$minicv->getIntitule();
        $this->logManager->addLog($log);

        return true;
    }

    public function delMinicv($id, $user)
    {
        $minicv = $this->entityManager->getRepository(VcMinicv::class)
                            ->findOneById($id);
        if (!$user->getMinicv()->contains($minicv)) {
            return false;
        }

        $this->entityManager->remove($minicv);
        $this->entityManager->flush();

        $log = $user->getLastname()." ".$user->getFirstname()." viens de supprimer son minicv ".$minicv->getIntitule();
        $this->logManager->addLog($log);

        return true;
    }
}
