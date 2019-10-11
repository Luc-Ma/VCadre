<?php
namespace Adherents\Service;

use Adherents\Entity\VcUpload;

class AdherentsManager
{
    private $entityManager;
    private $logManager;
    private $authService;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $logManager)
    {
        $this->entityManager = $entityManager;
        $this->logManager = $logManager;
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
}
