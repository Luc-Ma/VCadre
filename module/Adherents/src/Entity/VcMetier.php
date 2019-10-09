<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcComp;
use Adherents\Entity\VcCompBis;

/**
 * VcMetier
 *
 * @ORM\Table(name="vc_metier")
 * @ORM\Entity
 */
class VcMetier
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
     * @ORM\Column(name="nom", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="\Adherents\Entity\VcComp", mappedBy="metier")
     * @ORM\JoinColumn(name="ID", referencedColumnName="metier")
     */
    protected $comp;

    /**
     * @ORM\OneToMany(targetEntity="\Adherents\Entity\VcCompBis", mappedBy="metier")
     * @ORM\JoinColumn(name="id", referencedColumnName="metier")
     */
    protected $compBis;


    //constructor
    public function __construct()
    {
        $this->comp = new ArrayCollection();
        $this->compBis   = new ArrayCollection();
    }

    /**
     * Returns competence pour le metier.
     * @return array
     */
    public function getComp()
    {
        return $this->comp;
    }

    /**
     * Adds new comp pour ce metier.
     * @param $comp
     */
    public function addComp($comp)
    {
        $this->comp[] = $comp;
    }

    /**
     * Returns competence bis pour le metier.
     * @return array
     */
    public function getCompBis()
    {
        return $this->compBis;
    }

    /**
     * Adds new comp bis pour ce metier.
     * @param $comp
     */
    public function addCompBis($compBis)
    {
        $this->compBis[] = $compBis;
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
     * Set nom.
     *
     * @param string $nom
     *
     * @return VcMetier
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return VcMetier
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
