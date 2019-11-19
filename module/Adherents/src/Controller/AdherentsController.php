<?php
namespace Adherents\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Adherents\Entity\User;
use Adherents\Entity\VcMinicv;
use Adherents\Entity\VcMetier;
use Adherents\Entity\VcComp;
use Adherents\Entity\VcCompBis;
use Adherents\Entity\VcSecteur;
use Adherents\Entity\VcSavoiretreList;
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

    public function viewAction()
    {
        $id = $this->params()->fromRoute('id', null);

        //test if id exist
        $minicv = $this->entityManager->getRepository(VcMinicv::class)
                    ->findOneById($id);
        if ($minicv === null) {
            return $this->redirect()->toRoute('home');
        }
        //check permission
        $curUser = $this->entityManager->getRepository(User::class)
                    ->findOneByUsername($this->authService->getIdentity());

        if ($curUser->getAdminStatus() || $minicv->getUser()->getId() == $curUser->getId()) {
            return ["minicv" => $minicv];
        } else {
            // you're not admin and this is not your minicv
            return $this->redirect()->toRoute('home');
        }
    }

    public function elementsAction()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $view = new JsonModel();
            $view->setTerminal(true);
            $type = $this->params()->fromPost('act', null);
            $value = $this->params()->fromPost('value', null);

            if ($type === null || $value === null) {
                return false;
            }
            $data = [];
            switch ($type) {
                case 0:
                    $comps =  $this->entityManager->getRepository(VcComp::class)
                                ->findByMetier($value);

                    foreach ($comps as $comp) {
                        $data[] = [
                            'name' => $comp->getNom(),
                            'value' => $comp->getId(),
                        ];
                    }
                    break;
                case 1:
                    $comps =  $this->entityManager->getRepository(VcCompBis::class)
                                ->findByMetier($value);

                    foreach ($comps as $comp) {
                        $data[] = [
                            'name' => $comp->getNom(),
                            'value' => $comp->getId(),
                        ];
                    }
                    break;
                case 2:
                    $secteurs =  $this->entityManager->getRepository(VcSecteur::class)
                                ->findBySecteur($value);

                    foreach ($secteurs as $secteur) {
                        $data[] = [
                            'name' => $secteur->getNom(),
                            'value' => $secteur->getId(),
                        ];
                    }
                    break;
                case 3:
                    $ses =  $this->entityManager->getRepository(VcSavoiretreList::class)
                                ->findBySe($value);

                    foreach ($ses as $se) {
                        $data[] = [
                            'name' => $se->getNom(),
                            'value' => $se->getId(),
                        ];
                    }
                    break;
                default:
                    return;
                    break;

            }

            $view->setVariable('data', $data);
            $view->setVariable('SUCCES', 'OK');

            return $view;
        } else {
            return $this->redirect()->toRoute('home');
        }
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
        $selector = 0;
        $subtitle = "";
        switch ($step) {
            case 1:
                $subtitle = "Expérience et formation";
                $form = new MinicvP2Form();
                break;
            case 2:
                $subtitle = "Contrat - disponibilité - mobilité";
                $form = new MinicvP3Form($this->entityManager);
                break;
            case 3:
                $subtitle = "Vos compétences principales";
                $form = new MinicvP4Form($this->entityManager, $this->config);
                $selector = $this->config['Adherents']['options']['competence'];
                break;
            case 4:
                $subtitle = "Vos compétences spécifiques";
                $form = new MinicvP5Form($this->entityManager, $this->config);
                $selector = $this->config['Adherents']['options']['competenceBis'];
                break;
            case 5:
                $subtitle = "Vos secteurs cible";
                $form = new MinicvP6Form($this->entityManager, $this->config);
                break;
            case 6:
                $subtitle = "Vos savoirs-être";
                $form = new MinicvP7Form($this->entityManager, $this->config);
                break;
            default:
                return $this->redirect()->toRoute('home');
                break;
        }

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form,'step'=> $step,'selector' => $selector,'subtitle' => $subtitle];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form,'step'=> $step,'selector' => $selector,'subtitle' => $subtitle];
        }
        $data = $form->getData();
        //print_r($data);
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
