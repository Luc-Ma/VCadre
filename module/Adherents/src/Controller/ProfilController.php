<?php
namespace Adherents\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Adherents\Entity\VcApec;
use Adherents\Entity\VcMinicv;

class ProfilController extends AbstractActionController
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $apec = $this->entityManager->getRepository(VcApec::class)->findAll();
        $htmlView = new ViewModel();

        $htmlOutput = $htmlView
                 ->setTerminal(true)
                 ->setTemplate("adherents/profil/index")
                 ->setVariable('apec', $apec);

        return $htmlOutput;

    }

    public function dataAction()
    {
        $htmlView = new ViewModel();
        $type = $this->params()->fromPost('cat', null);
        $apec = $this->entityManager->getRepository(VcApec::class)
                        ->findOneById($type);
        $htmlOutput = $htmlView
                 ->setTerminal(true)
                 ->setTemplate("adherents/profil/data")
                 ->setVariable('apec', $apec);

        return $htmlOutput;
    }

    public function msgAction()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $view = new JsonModel();
            $view->setTerminal(true);
            $view->setVariable('SUCCES', 'OK');

            $name = $this->params()->fromPost('name', null);
            $email = $this->params()->fromPost('email', null);
            $message = $this->params()->fromPost('msg', null);
            $id = $this->params()->fromPost('id', null);

            $minicv = $this->entityManager->getRepository(VcMinicv::class)
                            ->findOneById($id);
            if($id === NULL) {
                $view->setVariable('SUCCES', 'NO');
                return $view;
            }

            $to = $minicv->getUser()->getEmail();
            $subject = $name." est intéressé par votre profil";

            $message = "
            <html>
            <head>
            <title>".$name." est intéressé par votre profil</title>
            </head>
            <body>
            <p>".$name." est intéressé par votre profil : ".$minicv->getIntitule()."</p>
            <p>
            Recontatez à cette adresse e-mail : <a href=\"mailto:".$email."\">".$email."</a>
            </p>
            <p>
            Son message : <br />
            ".htmlspecialchars($message, ENT_QUOTES)."
            </p>
            </body>
            </html>
            ";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <no-reply@vendeecadres.com>' . "\r\n";
            mail($to,'=?utf-8?B?'.base64_encode($subject).'?=',$message,$headers);
            return $view;
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
}
