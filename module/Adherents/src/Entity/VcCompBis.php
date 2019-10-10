<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcMetier;
use Adherents\Entity\VcMinicv;

/**
 * VcCompBis
 *
 * @ORM\Table(name="vc_comp_bis", indexes={@ORM\Index(name="link_metier_bis", columns={"metier"})})
 * @ORM\Entity(repositoryClass="\Adherents\Repository\CompRepository")
 */
class VcCompBis
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
     * @var \Adherents\Entity\VcMetier
     *
     * @ORM\ManyToOne(targetEntity="Adherents\Entity\VcMetier", inversedBy="compBis")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="metier", referencedColumnName="id", nullable=true)
     * })
     */
    private $metier;

    /**
     * @ORM\ManyToMany(targetEntity="\Adherents\Entity\VcMinicv", mappedBy="compBis")
     */
    private $minicv;

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
        $minicv->removeCompBis($this);
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
     * @return VcCompBis
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
     * Set metier.
     *
     * @param \Adherents\Entity\VcMetier|null $metier
     *
     * @return VcCompBis
     */
    public function setMetier(\Adherents\Entity\VcMetier $metier = null)
    {
        $this->metier = $metier;
        $metier->addCompBis($this);
        return $this;
    }

    /**
     * Get metier.
     *
     * @return \Adherents\Entity\VcMetier|null
     */
    public function getMetier()
    {
        return $this->metier;
    }
}
