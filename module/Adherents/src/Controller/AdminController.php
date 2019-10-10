<?php
namespace Adherents\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Adherents\Entity\User;
use Adherents\Form\Admin\ApecForm;

class AdminController extends AbstractActionController
{
    private $entityManager;
    private $adminService;
    private $authService;

    public function __construct($entityManager, $authService, $adminService)
    {
        $this->entityManager = $entityManager;
        $this->adminService =  $adminService;
        $this->authService = $authService;
    }

    public function checkAccess()
    {
        $user = $this->entityManager->getRepository(User::class)
                            ->findOneByUsername($this->authService->getIdentity());

        if ($user === null) {
            return false;
        }

        if ($user->getAdminStatus()) {
            return true;
        } else {
            return false;
        }
    }

    public function ajaxAction()
    {
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $view = new JsonModel();
            $view->setTerminal(true);
            $type = $this->params()->fromPost('act', null);

            switch ($type) {
                case '0': //ajout nouvel admin
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adminService->addAdmin($id);
                    break;
                case '1': //del un admin
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adminService->delAdmin($id);
                    break;
                case '2': //delete Apec.s
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adminService->delApec($id);
                    break;
                default:
                    $result = false;
                    break;
            }
            if ($result) {  //all is delete
                $view->setVariable('SUCCES', 'OK');
            } else {
                $view->setVariable('SUCCES', 'NO');
            }

            return $view;
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function indexAction()
    {
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }
    }

    public function apecAction()
    {
        $form = new ApecForm($this->entityManager);

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form, 'error' => true];
        }
        $data = $form->getData();

        $this->adminService->addApec($data);

        return $this->redirect()->toRoute('admin', ['action' => 'apec']);
    }

    public function usersAction()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return ['users' => $users];
    }
}
