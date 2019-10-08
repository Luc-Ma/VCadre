<?php
namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a registered user.
 * @ORM\Entity
 * @ORM\Table(name="llx_user")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(name="rowid")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @ORM\Column(name="login")
     */
    protected $username;

    /**
     * @ORM\Column(name="firstname")
     */
    protected $firstname;

    /**
     * @ORM\Column(name="lastname")
     */
    protected $lastname;

    /**
     * @ORM\Column(name="pass_crypted")
     */
    protected $password;

    /**
     * Returns user ID.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns user email.
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns user username.
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns user first name.
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Returns user lastname.
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Returns user password.
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
