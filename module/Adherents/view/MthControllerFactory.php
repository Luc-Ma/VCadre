<?php
namespace Mth\Controller\Factory;

use Mth\Controller\MthController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Mth\Service\RatingManager;
use Mth\Service\VideoManager;

/**
 * This is the factory for MthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class MthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ratingService = $container->get(RatingManager::class);
        $videoService = $container->get(VideoManager::class);

        return new MthController($entityManager,$config,$ratingService,$videoService);
    }
}
