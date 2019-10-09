<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcMinicv;

/**
 * VcDispo
 *
 * @ORM\Table(name="vc_dispo")
 * @ORM\Entity
 */
class VcDispo
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
     * @ORM\Column(name="dispo", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $dispo;


    /**
     * @ORM\OneToMany(targetEntity="\Adherents\Entity\VcMinicv", mappedBy="dispo")
     * @ORM\JoinColumn(name="id", referencedColumnName="dispo")
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
     * Set dispo.
     *
     * @param string $dispo
     *
     * @return VcDispo
     */
    public function setDispo($dispo)
    {
        $this->dispo = $dispo;

        return $this;
    }

    /**
     * Get dispo.
     *
     * @return string
     */
    public function getDispo()
    {
        return $this->dispo;
    }
}
