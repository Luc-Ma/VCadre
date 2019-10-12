<?php
namespace Adherents\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Adherents\Entity\User;
use Adherents\Entity\VcMinicv;
use Adherents\Form\Adherents\UploadForm;

class AdherentsController extends AbstractActionController
{
    private $entityManager;
    private $authService;
    private $adherentsService;
    private $config;

    public function __construct($entityManager, $authService, $adherentsService, $config)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
        $this->adherentsService = $adherentsService;
        $this->config = $config;
    }

    public function indexAction()
    {
        $curUser = $this->entityManager->getRepository(User::class)
                    ->findOneByUsername($this->authService->getIdentity());

        return [
            'user' => $curUser,
        ];
    }

    public function ajaxAction()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $curUser = $this->entityManager->getRepository(User::class)
                        ->findOneByUsername($this->authService->getIdentity());
            $view = new JsonModel();
            $view->setTerminal(true);
            $type = $this->params()->fromPost('act', null);

            switch ($type) {
                case '0': //changer cv privé
                    $result = $this->adherentsService->changeUploadState(0, $curUser);
                    break;
                case '1': //changer cv public
                    $result = $this->adherentsService->changeUploadState(1, $curUser);
                    break;
                case '2': //suprimé cv
                    $result = $this->adherentsService->delUpload($curUser);
                    break;
                case '3': //delete metier.s
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adminService->delMetier($id);
                    break;
                case '4': //delete comp.s
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adminService->delComp($id);
                    break;
                case '5': //delete compbis.s
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adminService->delCompBis($id);
                    break;
                case '6': //delete secteur.s
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adminService->delSecteur($id);
                    break;
                case '7': //delete savoir etre cat
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adminService->delSeCat($id);
                    break;
                case '8': //delete savoir etre
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adminService->delSe($id);
                    break;
                default:
                    $result = false;
                    break;
            }
            if ($result) {  //all is good
                $view->setVariable('SUCCES', 'OK');
            } else { //something went wrong
                $view->setVariable('SUCCES', 'NO');
            }

            return $view;
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function newAction()
    {
    }

    public function uploadAction()
    {
        $curUser = $this->entityManager->getRepository(User::class)
                    ->findOneByUsername($this->authService->getIdentity());

        $form = new UploadForm($curUser);

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form,'user' => $curUser];
        }

        $data = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );

        $form->setData($data);

        if (!$form->isValid()) {
            return ['form' => $form, 'user' => $curUser];
        }
        $data = $form->getData();
        $this->adherentsService->addUpload($data, $curUser);

        return $this->redirect()->toRoute('home');
    }
}
