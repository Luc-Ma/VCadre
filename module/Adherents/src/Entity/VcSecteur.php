<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcMinicv;

/**
 * VcSecteur
 *
 * @ORM\Table(name="vc_secteur")
 * @ORM\Entity
 */
class VcSecteur
{
    //  secteur tyê constants.
    const TYPE_PRIMAIRE   = 0;
    const TYPE_SECONDAIRE = 1;
    const TYPE_TERTIARE   = 2;

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
     * @var int
     *
     * @ORM\Column(name="type", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $type;
    /**
     * @ORM\ManyToMany(targetEntity="\Adherents\Entity\VcMinicv", mappedBy="secteur")
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
        $minicv->removeSecteur($this);
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return VcSecteur
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
     * Set type.
     *
     * @param int $type
     *
     * @return VcSecteur
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns possible type as array.
     * @return array
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_PRIMAIRE => "Primaire",
            self::TYPE_SECONDAIRE => "Secondaire",
            self::TYPE_TERTIARE => "Tertiare"
        ];
    }

    /**
     * Returns type as string.
     * @return string
     */
    public function getTypeStatus()
    {
        $list = self::getTypeList();
        if (isset($list[$this->type])) {
            return $list[$this->type];
        }

        return 'Unknown';
    }
}
