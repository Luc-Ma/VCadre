<?php
namespace Adherents\Service;

use Adherents\Entity\VcUpload;
use Adherents\Entity\VcMinicv;
use Adherents\Entity\VcApec;
use Adherents\Entity\VcContrat;
use Adherents\Entity\VcDispo;
use Adherents\Entity\VcComp;
use Adherents\Entity\VcCompBis;
use Adherents\Entity\VcMobilite;
use Doctrine\DBAL\Types\DateTimeType;

class AdherentsManager
{
    private $entityManager;
    private $logManager;
    private $config;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $logManager, $config)
    {
        $this->entityManager = $entityManager;
        $this->logManager = $logManager;
        $this->config = $config;
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
            case 2: //form 3
                foreach ($data['contrat'] as $cID) {
                    $contrat = $this->entityManager->getRepository(VcContrat::class)
                                        ->findOneById($cID);
                    $minicv->addContrat($contrat);
                }
                $dispo = $this->entityManager->getRepository(VcDispo::class)
                                    ->findOneById($data['dispo']);
                $mob = $this->entityManager->getRepository(VcMobilite::class)
                                    ->findOneById($data['mob']);
                $source = $data['source'];

                $minicv->setDispo($dispo);
                $minicv->setMobilite($mob);
                $minicv->setMobiliteSource($source);
                $minicv->setStep(3);
                break;
            case 3: //form 4
                for ($i = 0; $i < $this->config['Adherents']['options']['competence']; $i++) {
                    $comp = $this->entityManager->getRepository(VcComp::class)
                                        ->findOneById($data['comp'.$i]);
                    $minicv->addComp($comp);
                }
                $minicv->setStep(4);
                break;
                
            default:
                return false;
                break;
        }
        $this->entityManager->persist($minicv);
        $this->entityManager->flush();

        $log = $user->getLastname()." ".$user->getFirstname();
        $log .= " à complété l'étape ".($step +1)." de son minicv ".$minicv->getIntitule();
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
