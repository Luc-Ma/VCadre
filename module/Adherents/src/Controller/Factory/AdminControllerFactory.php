<?php
namespace Adherents\Controller\Factory;

use Interop\Container\ContainerInterface;
use Adherents\Controller\AuthController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\AuthenticationService;
use Adherents\Controller\AdminController;
use Adherents\Service\AdminManager;

/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class AdminControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authService = $container->get(AuthenticationService::class);
        $adminService = $container->get(AdminManager::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new AdminController($entityManager, $authService, $adminService);
    }
}
