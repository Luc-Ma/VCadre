<?php
namespace Adherents\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

use Adherents\Entity\User;

class AuthAdapter implements AdapterInterface
{
    private $username;

    private $password;

    private $config;

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager, $config)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = (string)$password;
    }
    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {
        // Check the database if there is a user with such email.
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByUsername($this->username);

        // If there is no such user, return 'Identity Not Found' status.
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']
            );
        }

        $valid = password_verify($this->password, $user->getPassword());

        if ($valid) {
            return new Result(
                Result::SUCCESS,
                $this->username,
                ['Authenticated successfully.']
            );
        }
        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']
        );
    }
}
