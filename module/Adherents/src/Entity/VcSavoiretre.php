<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Adherents\Entity\VcSavoiretreList;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * VcSavoiretre
 *
 * @ORM\Table(name="vc_savoiretre")
 * @ORM\Entity
 */
class VcSavoiretre
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
     * @ORM\OneToMany(targetEntity="\Adherents\Entity\VcSavoiretreList", mappedBy="savoiretre")
     * @ORM\JoinColumn(name="ID", referencedColumnName="savoiretre")
     */
    protected $seList;


    //constructor
    public function __construct()
    {
        $this->seList = new ArrayCollection();
    }

    /**
     * Returns list for savoir etre.
     * @return array
     */
    public function getSeList()
    {
        return $this->seList;
    }

    /**
     * Adds new comp pour ce metier.
     * @param $comp
     */
    public function addSeList($seList)
    {
        $this->seList[] = $seList;
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
     * @return VcSavoiretre
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
}
