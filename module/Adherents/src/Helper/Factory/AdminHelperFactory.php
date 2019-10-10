<?php
namespace Adherents\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Adherents\Helper\AdminHelper;

/**
 * This is the factory for tricks view helper. Its purpose is to instantiate the
 * helper
 */
class AdminHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new AdminHelper($entityManager);
    }
}
