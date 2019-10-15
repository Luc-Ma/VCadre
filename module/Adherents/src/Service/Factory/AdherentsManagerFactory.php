<?php
namespace Adherents\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Adherents\Service\AdherentsManager;
use Adherents\Service\LogManager;

/**
 * This is the factory class for AdherentsManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class AdherentsManagerFactory implements FactoryInterface
{
    /**
     * This method creates the AuthManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $logManager = $container->get(LogManager::class);
        $config = $container->get('Config');
        return new AdherentsManager($entityManager, $logManager, $config);
    }
}
