<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcMinicv;
use Adherents\Entity\VcSavoiretre;

/**
 * VcSavoiretreList
 *
 * @ORM\Table(name="vc_savoiretre_list", indexes={@ORM\Index(name="link_savoiretre", columns={"savoiretre"})})
 * @ORM\Entity
 */
class VcSavoiretreList
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
     * @var \Adherents\Entity\VcSavoiretre
     *
     * @ORM\ManyToOne(targetEntity="Adherents\Entity\VcSavoiretre", inversedBy="seList")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="savoiretre", referencedColumnName="id", nullable=true)
     * })
     */
    private $savoiretre;


    /**
     * @ORM\ManyToMany(targetEntity="\Adherents\Entity\VcMinicv", mappedBy="se")
     */
    private $minicv;


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
    * Constructor.
    */
    public function __construct()
    {
        $this->minicv = new ArrayCollection();
    }

    /**
    * Returns minicv for this comp.
    * @return array
    */

    public function getMinicv()
    {
        return $this->minicv;
    }

    /**
    * Adds a minicv to this comp.
    * @param $minicv
    */
    public function addMinicv($minicv)
    {
        $this->minicv[] = $minicv;
    }

    public function removeMinicv($minicv)
    {
        if (!$this->minicv->contains($minicv)) {
            return;
        }
        $this->minicv->removeElement($minicv);
        $minicv->removeSavoirEtre($this);
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return VcSavoiretreList
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
     * Set savoiretre.
     *
     * @param \Adherents\Entity\VcSavoiretre|null $savoiretre
     *
     * @return VcSavoiretreList
     */
    public function setSavoiretre(\Adherents\Entity\VcSavoiretre $savoiretre = null)
    {
        $this->savoiretre = $savoiretre;
        $savoiretre->addSeList($this);
        return $this;
    }

    /**
     * Get savoiretre.
     *
     * @return \Adherents\Entity\VcSavoiretre|null
     */
    public function getSavoiretre()
    {
        return $this->savoiretre;
    }
}
