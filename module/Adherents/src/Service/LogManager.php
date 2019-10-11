<?php
namespace Adherents\Service;

use Adherents\Entity\User;
use Adherents\Entity\VcLog;

class LogManager
{
    private $entityManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addLog($log, $identity)
    {
        $newLog = new VcLog();
        $newLog->setLog($log);

        $user = $this->entityManager->getRepository(User::class)
                    ->findOneByUsername($identity);

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
