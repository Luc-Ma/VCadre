<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VcUpload
 *
 * @ORM\Table(name="vc_upload", indexes={@ORM\Index(name="link_upload_user", columns={"user"})})
 * @ORM\Entity
 */
class VcUpload
{
    //  cv state constants.
    const CV_PUBLIC = 1;
    const CV_PRIVE = 0;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $file;

    /**
     * @var int
     *
     * @ORM\Column(name="public", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $public = 0;

    /**
     * @var \Adherents\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Adherents\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="rowid", nullable=true)
     * })
     */
    private $user;


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
     * Set file.
     *
     * @param string $file
     *
     * @return VcUpload
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set public.
     *
     * @param int $public
     *
     * @return VcUpload
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public.
     *
     * @return int
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set user.
     *
     * @param \Adherents\Entity\User|null $user
     *
     * @return VcUpload
     */
    public function setUser(\Adherents\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Adherents\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
