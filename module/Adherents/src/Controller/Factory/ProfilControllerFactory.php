<?php
namespace Adherents\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Adherents\Controller\ProfilController;

/**
 * This is the factory for ProfilController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class ProfilControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new ProfilController($entityManager);
    }
}
