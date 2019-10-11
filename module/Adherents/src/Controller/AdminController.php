<?php
namespace Adherents\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Adherents\Entity\User;
use Adherents\Entity\VcLog;
use Adherents\Form\Admin\ApecForm;
use Adherents\Form\Admin\MetierForm;
use Adherents\Form\Admin\CompForm;
use Adherents\Form\Admin\CompBisForm;
use Adherents\Form\Admin\SecteurForm;
use Adherents\Form\Admin\SeForm;
use Adherents\Form\Admin\SeCatForm;

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

    public function logAction()
    {
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }
        $page = $this->params()->fromQuery('page', 1);
        $query = $this->entityManager->getRepository(VcLog::class)
                ->findAll();


        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(15);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'logs' => $paginator,
        ]);
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
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new ApecForm();

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
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }

        $users = $this->entityManager->getRepository(User::class)->findAll();

        return ['users' => $users];
    }

    public function metierAction()
    {
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new MetierForm();

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form, 'error' => true];
        }
        $data = $form->getData();

        $this->adminService->addMetier($data);

        return $this->redirect()->toRoute('admin', ['action' => 'metier']);
    }

    public function compAction()
    {
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new CompForm($this->entityManager);

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form, 'error' => true];
        }
        $data = $form->getData();

        $this->adminService->addComp($data);

        return $this->redirect()->toRoute('admin', ['action' => 'comp']);
    }

    public function compbisAction()
    {
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new CompBisForm($this->entityManager);

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form, 'error' => true];
        }
        $data = $form->getData();

        $this->adminService->addCompBis($data);

        return $this->redirect()->toRoute('admin', ['action' => 'compbis']);
    }

    public function secteurAction()
    {
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new SecteurForm($this->entityManager);

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form, 'error' => true];
        }
        $data = $form->getData();

        $this->adminService->addSecteur($data);

        return $this->redirect()->toRoute('admin', ['action' => 'secteur']);
    }

    public function savoiretrecatAction()
    {
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new SeCatForm();

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form, 'error' => true];
        }
        $data = $form->getData();

        $this->adminService->addSeCat($data);

        return $this->redirect()->toRoute('admin', ['action' => 'savoiretrecat']);
    }

    public function savoiretreAction()
    {
        if (!self::checkAccess()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new SeForm($this->entityManager);

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form, 'error' => true];
        }
        $data = $form->getData();

        $this->adminService->addSe($data);

        return $this->redirect()->toRoute('admin', ['action' => 'savoiretre']);
    }
}
