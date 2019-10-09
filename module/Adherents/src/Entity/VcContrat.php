<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcMinicv;

/**
 * VcContrat
 *
 * @ORM\Table(name="vc_contrat")
 * @ORM\Entity
 */
class VcContrat
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
     * @ORM\Column(name="type", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="\Adherents\Entity\VcMinicv", mappedBy="contrat")
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
        $minicv->removeContrat($this);
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return VcContrat
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
