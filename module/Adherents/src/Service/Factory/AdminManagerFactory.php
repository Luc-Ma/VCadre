<?php
namespace Adherents\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\AuthenticationService;
use Adherents\Service\AdminManager;
use Adherents\Service\LogManager;

/**
 * This is the factory class for AdminManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class AdminManagerFactory implements FactoryInterface
{
    /**
     * This method creates the AuthManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $logManager = $container->get(LogManager::class);
        $authService = $container->get(AuthenticationService::class);
        return new AdminManager($entityManager, $logManager, $authService);
    }
}
