<?php
namespace Adherents\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Adherents\Entity\User;
use Adherents\Entity\VcMinicv;
use Adherents\Form\Adherents\UploadForm;
use Adherents\Form\Adherents\MinicvP1Form;
use Adherents\Form\Adherents\MinicvP2Form;
use Adherents\Form\Adherents\MinicvP3Form;
use Adherents\Form\Adherents\MinicvP4Form;
use Adherents\Form\Adherents\MinicvP5Form;
use Adherents\Form\Adherents\MinicvP6Form;
use Adherents\Form\Adherents\MinicvP7Form;

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
                case '3': //changer minicv priv-pub
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adherentsService->changeMinicvState($id, $curUser);
                    break;
                case '4': //delete minicv
                    $id = $this->params()->fromPost('id', null);
                    $result = $this->adherentsService->delMinicv($id, $curUser);
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
        $curUser = $this->entityManager->getRepository(User::class)
                    ->findOneByUsername($this->authService->getIdentity());

        $form = new MinicvP1Form($this->entityManager);

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form, 'error' => true];
        }
        $data = $form->getData();

        $id = $this->adherentsService->addMinicv($data, $curUser);

        return $this->redirect()->toRoute('adherents', ['action' => 'continue','id' => $id]);
    }

    public function continueAction()
    {
        $curUser = $this->entityManager->getRepository(User::class)
                    ->findOneByUsername($this->authService->getIdentity());

        $id = $this->params()->fromRoute('id', null);

        if ($id === null) {
            return $this->redirect()->toRoute('home');
        }

        //check ID is possesed by user
        $minicv = $this->entityManager->getRepository(VcMinicv::class)
                    ->findOneById($id);
        if ($minicv === null || ($minicv->getUser()->getId() != $curUser->getId())) {
            return $this->redirect()->toRoute('home');
        }
        $step = $minicv->getStep();

        switch ($step) {
            case 1:
                $form = new MinicvP2Form();
                break;
            case 2:
                $form = new MinicvP3Form($this->entityManager);
                break;
            case 3:
                $form = new MinicvP4Form($this->entityManager);
                break;
            case 4:
                $form = new MinicvP5Form($this->entityManager);
                break;
            case 5:
                $form = new MinicvP6Form($this->entityManager);
                break;
            case 6:
                $form = new MinicvP7Form($this->entityManager);
                break;
            default:
                return $this->redirect()->toRoute('home');
                break;
        }

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form,'step'=> $step];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form,'step'=> $step];
        }
        $data = $form->getData();

        $this->adherentsService->continueMinicv($id, $data, $step, $curUser);
        return $this->redirect()->toRoute('adherents', ['action' => 'continue','id' => $id]);
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
