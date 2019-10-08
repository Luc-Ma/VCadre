<?php
namespace Adherents\Controller\Factory;

use Interop\Container\ContainerInterface;
use Adherents\Controller\AuthController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Adherents\Service\AuthManager;
use Zend\Authentication\AuthenticationService;

/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authManager = $container->get(AuthManager::class);
        $authService = $container->get(AuthenticationService::class);
        return new AuthController($entityManager, $authManager, $authService);
    }
}
