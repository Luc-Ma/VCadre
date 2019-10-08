<?php
namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a registered user.
 * @ORM\Entity(repositoryClass="\Adherents\Repository\UserRepository")
 * @ORM\Table(name="llx_user")
 */
class User
{
    // lvl state constants.
    const USER_IS_ADMIN   = 1;
    const USER_NOT_ADMIN  = 0;

    /**
     * @ORM\Id
     * @ORM\Column(name="rowid")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="vc_admin")
     */
    protected $admin;

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

    /**
     * Returns admin status.
     * @return int
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Sets admin status
     * @param int $admin
     */
    public function setAdmin($status)
    {
        $this->admin = $status;
    }

    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getAdminStatusList()
    {
        return [
            self::USER_IS_ADMIN => true,
            self::USER_NOT_ADMIN => false
        ];
    }

    /**
     * Returns user status as string.
     * @return string
     */
    public function getAdminStatus()
    {
        $list = self::getAdminStatusList();
        if (isset($list[$this->admin])) {
            return $list[$this->admin];
        }

        return 'Unknown';
    }
}
