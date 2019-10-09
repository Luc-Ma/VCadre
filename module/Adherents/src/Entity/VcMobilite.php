<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcMinicv;

/**
 * VcMobilite
 *
 * @ORM\Table(name="vc_mobilite")
 * @ORM\Entity
 */
class VcMobilite
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
     * @var string
     *
     * @ORM\Column(name="mobilite", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $mobilite;


    /**
     * @ORM\OneToMany(targetEntity="\Adherents\Entity\VcMinicv", mappedBy="mobilite")
     * @ORM\JoinColumn(name="id", referencedColumnName="mobilite")
     */
    protected $minicv;


    //constructor
    public function __construct()
    {
        $this->minicv = new ArrayCollection();
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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mobilite.
     *
     * @param string $mobilite
     *
     * @return VcMobilite
     */
    public function setMobilite($mobilite)
    {
        $this->mobilite = $mobilite;

        return $this;
    }

    /**
     * Get mobilite.
     *
     * @return string
     */
    public function getMobilite()
    {
        return $this->mobilite;
    }
}
