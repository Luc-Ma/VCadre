<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcMinicv;

/**
 * VcApec
 *
 * @ORM\Table(name="vc_apec")
 * @ORM\Entity
 */
class VcApec
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
     * @ORM\Column(name="intitule", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $intitule;

    /**
     * @ORM\OneToMany(targetEntity="\Adherents\Entity\VcMinicv", mappedBy="apec")
     * @ORM\JoinColumn(name="id", referencedColumnName="apec")
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
     * Set intitule.
     *
     * @param string $intitule
     *
     * @return VcApec
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule.
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }
}
