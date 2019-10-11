<?php
namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcMinicv;
use Adherents\Entity\VcLog;

/**
 * This class represents a registered user.
 * @ORM\Entity(repositoryClass="\Adherents\Repository\UserRepository")
 * @ORM\Table(name="llx_user")
 */
class User
{
    // user admin constants.
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
     * @ORM\OneToMany(targetEntity="\Adherents\Entity\VcMinicv", mappedBy="user")
     * @ORM\JoinColumn(name="rowid", referencedColumnName="user")
     */
    protected $minicv;

    /**
     * @ORM\OneToMany(targetEntity="\Adherents\Entity\VcLog", mappedBy="user")
     * @ORM\JoinColumn(name="rowid", referencedColumnName="user")
     */
    protected $log;


    //constructor
    public function __construct()
    {
        $this->minicv = new ArrayCollection();
        $this->log = new ArrayCollection();
    }

    /**
     * Returns minicv .
     * @return array
     */
    public function getMinicv()
    {
        return $this->minicv;
    }

    /**
     * Adds new minicv.
     * @param $comp
     */
    public function addMinicv($minicv)
    {
        $this->minicv[] = $minicv;
    }

    /**
     * Returns logs .
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Adds new logs.
     * @param $comp
     */
    public function addLog($log)
    {
        $this->log[] = $log;
    }

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
