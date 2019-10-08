<?php
namespace Adherents\Service\Factory;

use Interop\Container\ContainerInterface;
use Adherents\Service\AuthAdapter;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

/**
 * This is the factory class for AuthAdapter service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class AuthAdapterFactory implements FactoryInterface
{
    /**
     * This method creates the AuthAdapter service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new AuthAdapter($entityManager, $config);
    }
}
