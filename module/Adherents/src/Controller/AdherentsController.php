<?php
namespace Adherents\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Adherents\Entity\User;

class AdherentsController extends AbstractActionController
{
    private $entityManager;
    private $authService;


    public function __construct($entityManager, $authService)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }

    public function indexAction()
    {
        $curUser = $this->entityManager->getRepository(User::class)
                    ->findOneByUsername($this->authService->getIdentity());

        $minicv = $this->entityManager->getRepository(VcMinicv::class)
                    ->findByUser($curUser);

        return [
            'minicv' => $minicv,
        ]
    }
}
