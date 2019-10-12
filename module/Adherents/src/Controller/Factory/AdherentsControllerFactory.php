<?php
namespace Adherents\Controller\Factory;

use Adherents\Controller\AdherentsController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\AuthenticationService;
use Adherents\Service\AdherentsManager;

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
        $adherentsService = $container->get(AdherentsManager::class);
        $config = $container->get('Config');
        return new AdherentsController($entityManager, $authService, $adherentsService, $config);
    }
}
