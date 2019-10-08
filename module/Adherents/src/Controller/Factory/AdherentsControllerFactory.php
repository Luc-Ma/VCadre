<?php
namespace Adherents\Controller\Factory;

use Adherents\Controller\AdherentsController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory for AdherentsController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class AdherentsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new AdherentsController($entityManager, $config);
    }
}
