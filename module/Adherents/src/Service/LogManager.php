<?php
namespace Adherents\Service;

use Adherents\Entity\User;
use Adherents\Entity\VcLog;

class LogManager
{
    private $entityManager;
    private $authService;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $authService)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }

    public function addLog($log)
    {
        $newLog = new VcLog();
        $newLog->setLog($log);

        $user = $this->entityManager->getRepository(User::class)
                    ->findOneByUsername($this->authService->getIdentity());

        if ($user === null) {
            $admin = $this->entityManager->getRepository(User::class)
                        ->findOneById(1);
            $newLog->setUser($admin);
        } else {
            $newLog->setUser($user);
        }

        $this->entityManager->persist($newLog);
        $this->entityManager->flush();
    }
}
