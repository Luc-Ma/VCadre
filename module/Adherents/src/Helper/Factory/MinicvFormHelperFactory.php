<?php
namespace Adherents\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Adherents\Helper\MinicvFormHelper;

/**
 * This is the factory for MinicvForm view helper. Its purpose is to instantiate the
 * helper
 */
class MinicvFormHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $config = $container->get('Config');
        return new MinicvFormHelper($entityManager, $config);
    }
}
