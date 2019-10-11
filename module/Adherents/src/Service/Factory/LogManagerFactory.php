<?php
namespace Adherents\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Adherents\Service\LogManager;

/**
 * This is the factory class for LogManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class LogManagerFactory implements FactoryInterface
{
    /**
     * This method creates the AuthManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new LogManager($entityManager);
    }
}
