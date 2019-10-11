<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Adherents\Entity\User;

/**
 * VcLog
 *
 * @ORM\Table(name="vc_log")
 * @ORM\Entity(repositoryClass="\Adherents\Repository\LogRepository")
 */
class VcLog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Adherents\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Adherents\Entity\User", inversedBy="log")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="rowid", nullable=true)
     * })
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", precision=0, scale=0, nullable=false, options={"default"="CURRENT_TIMESTAMP"}, unique=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="log", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $log;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user.
     *
     *  @param \Adherents\Entity\User
     *
     * @return \Adherents\Entity\User
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Adherents\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return VcLog
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set log.
     *
     * @param string $log
     *
     * @return VcLog
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log.
     *
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }
}
