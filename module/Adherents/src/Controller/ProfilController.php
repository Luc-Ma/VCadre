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

}
