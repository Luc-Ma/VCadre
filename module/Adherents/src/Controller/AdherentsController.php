<?php
namespace Adherents\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class AdherentsController extends AbstractActionController
{
    private $entityManager;
    private $config;


    public function __construct($entityManager, $config)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    public function indexAction()
    {
    }
}
