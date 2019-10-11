<?php
namespace Adherents\Controller\Factory;

use Adherents\Controller\AdherentsController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\AuthenticationService;

/**
 * This is the factory for AdherentsController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class AdherentsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $authService = $container->get(AuthenticationService::class);
        return new AdherentsController($entityManager, $authService);
    }
}
