<?php

namespace Adherents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Adherents\Entity\VcApec;
use Adherents\Entity\VcDispo;
use Adherents\Entity\VcMobilite;
use Adherents\Entity\User;
use Adherents\Entity\VcComp;
use Adherents\Entity\VcCompBis;
use Adherents\Entity\VcSecteur;
use Adherents\Entity\VcSavoiretreList;
use Adherents\Entity\VcContrat;

/**
 * VcMinicv
 *
 * @ORM\Table(name="vc_minicv", indexes={@ORM\Index(name="link_apec", columns={"apec"}), @ORM\Index(name="link_mobilite", columns={"mobilite"}), @ORM\Index(name="link_dispo", columns={"dispo"})})
 * @ORM\Entity
 */
class VcMinicv
{
    // minicv constants.
    const PROFIL_IS_PRIMARY = 1;
    const PROFIL_IS_SECONDARY = 2;

    const PROFIL_IS_COMPLETE = 1;
    const PROFIL_INCOMPLETE = 0;

    const PROFIL_IS_VALID = 1;
    const PROFIL_INVALID = 0;

    const PROFIL_IS_PUBLIC = 1;
    const PROFIL_IS_PRIVATE = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="profil", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $profil;

    /**
     * @var string|null
     *
     * @ORM\Column(name="intitule", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $intitule;

    /**
     * @var string|null
     *
     * @ORM\Column(name="experience_poste", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $experiencePoste;

    /**
     * @var string|null
     *
     * @ORM\Column(name="experience_total", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $experienceTotal;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mobilite_source", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $mobiliteSource;

    /**
     * @var string|null
     *
     * @ORM\Column(name="formation", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $formation;

    /**
     * @var int
     *
     * @ORM\Column(name="complet", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $complet;

    /**
     * @var int
     *
     * @ORM\Column(name="valid", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $valid;

    /**
     * @var int
     *
     * @ORM\Column(name="publish", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $publish;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_creation", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_maj", type="datetime", precision=0, scale=0, nullable=false, options={"default"="CURRENT_TIMESTAMP"}, unique=false)
     */
    private $dateMaj = 'CURRENT_TIMESTAMP';

    /**
     * @var \Adherents\Entity\VcApec
     *
     * @ORM\ManyToOne(targetEntity="Adherents\Entity\VcApec", inversedBy="minicv")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="apec", referencedColumnName="id", nullable=true)
     * })
     */
    private $apec;

    /**
     * @var \Adherents\Entity\VcDispo
     *
     * @ORM\ManyToOne(targetEntity="Adherents\Entity\VcDispo", inversedBy="minicv")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dispo", referencedColumnName="id", nullable=true)
     * })
     */
    private $dispo;

    /**
     * @var \Adherents\Entity\VcMobilite
     *
     * @ORM\ManyToOne(targetEntity="Adherents\Entity\VcMobilite", inversedBy="minicv")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mobilite", referencedColumnName="id", nullable=true)
     * })
     */
    private $mobilite;

    /**
     * @var \Adherents\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Adherents\Entity\User", inversedBy="minicv")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="rowid", nullable=true)
     * })
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="\Adherents\Entity\VcComp", inversedBy="minicv")
     * @ORM\JoinTable(name="vc_cv_comp",
     *      joinColumns={@ORM\JoinColumn(name="minicv", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="comp", referencedColumnName="id")}
     *      )
     */
    private $comp;

    /**
     * @ORM\ManyToMany(targetEntity="\Adherents\Entity\VcCompBis", inversedBy="minicv")
     * @ORM\JoinTable(name="vc_cv_compbis",
     *      joinColumns={@ORM\JoinColumn(name="minicv", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="compbis", referencedColumnName="id")}
     *      )
     */
    private $compBis;

    /**
     * @ORM\ManyToMany(targetEntity="\Adherents\Entity\VcSecteur", inversedBy="minicv")
     * @ORM\JoinTable(name="vc_cv_secteur",
     *      joinColumns={@ORM\JoinColumn(name="minicv", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="secteur", referencedColumnName="id")}
     *      )
     */
    private $secteur;

    /**
     * @ORM\ManyToMany(targetEntity="\Adherents\Entity\VcSavoiretreList", inversedBy="minicv")
     * @ORM\JoinTable(name="vc_cv_savoiretre",
     *      joinColumns={@ORM\JoinColumn(name="minicv", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="savoiretre", referencedColumnName="id")}
     *      )
     */
    private $se;

    /**
     * @ORM\ManyToMany(targetEntity="\Adherents\Entity\VcContrat", inversedBy="minicv")
     * @ORM\JoinTable(name="vc_cv_contrat",
     *      joinColumns={@ORM\JoinColumn(name="minicv", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contrat", referencedColumnName="id")}
     *      )
     */
    private $contrat;


    public function __construct()
    {
        $this->comp = new ArrayCollection();
        $this->compBis = new ArrayCollection();
        $this->secteur = new ArrayCollection();
        $this->se = new ArrayCollection();
        $this->contrat = new ArrayCollection();
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
     * Set profil.
     *
     * @param int $profil
     *
     * @return VcMinicv
     */
    public function setProfil($profil)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil.
     *
     * @return int
     */
    public function getProfil()
    {
        return $this->profil;
    }

    public static function getProfilList()
    {
        return [
            self::PROFIL_IS_PRIMARY => "Primaire",
            self::PROFIL_IS_SECONDARY => "Secondaire"
        ];
    }

    /**
     * Returns user profil as string.
     * @return string
     */
    public function getProfilType()
    {
        $list = self::getProfilList();
        if (isset($list[$this->profil])) {
            return $list[$this->profil];
        }

        return 'Unknown';
    }

    /**
     * Set intitule.
     *
     * @param string|null $intitule
     *
     * @return VcMinicv
     */
    public function setIntitule($intitule = null)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule.
     *
     * @return string|null
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set experiencePoste.
     *
     * @param string|null $experiencePoste
     *
     * @return VcMinicv
     */
    public function setExperiencePoste($experiencePoste = null)
    {
        $this->experiencePoste = $experiencePoste;

        return $this;
    }

    /**
     * Get experiencePoste.
     *
     * @return string|null
     */
    public function getExperiencePoste()
    {
        return $this->experiencePoste;
    }

    /**
     * Set experienceTotal.
     *
     * @param string|null $experienceTotal
     *
     * @return VcMinicv
     */
    public function setExperienceTotal($experienceTotal = null)
    {
        $this->experienceTotal = $experienceTotal;

        return $this;
    }

    /**
     * Get experienceTotal.
     *
     * @return string|null
     */
    public function getExperienceTotal()
    {
        return $this->experienceTotal;
    }

    /**
     * Set mobiliteSource.
     *
     * @param string|null $mobiliteSource
     *
     * @return VcMinicv
     */
    public function setMobiliteSource($mobiliteSource = null)
    {
        $this->mobiliteSource = $mobiliteSource;

        return $this;
    }

    /**
     * Get mobiliteSource.
     *
     * @return string|null
     */
    public function getMobiliteSource()
    {
        return $this->mobiliteSource;
    }

    /**
     * Set formation.
     *
     * @param string|null $formation
     *
     * @return VcMinicv
     */
    public function setFormation($formation = null)
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * Get formation.
     *
     * @return string|null
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * Set complet.
     *
     * @param int $complet
     *
     * @return VcMinicv
     */
    public function setComplet($complet)
    {
        $this->complet = $complet;

        return $this;
    }

    /**
     * Get complet.
     *
     * @return int
     */
    public function getComplet()
    {
        return $this->complet;
    }

    /**
     * Returns possible complete statuses as array.
     * @return array
     */
    public static function getCompletStatusList()
    {
        return [
            self::PROFIL_IS_COMPLETE => "Profil complet",
            self::PROFIL_INCOMPLETE => "Profil incomplet"
        ];
    }

    /**
     * Returns profile status as string.
     * @return string
     */
    public function getCompletStatus()
    {
        $list = self::getCompletStatusList();
        if (isset($list[$this->complet])) {
            return $list[$this->complet];
        }

        return 'Unknown';
    }

    /**
     * Set valid.
     *
     * @param int $valid
     *
     * @return VcMinicv
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid.
     *
     * @return int
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Returns possible valid statuses as array.
     * @return array
     */
    public static function getValidStatusList()
    {
        return [
            self::PROFIL_IS_VALID => "Profil validé",
            self::PROFIL_INVALID => "Profil incomplet"
        ];
    }

    /**
     * Returns profile validation status as string.
     * @return string
     */
    public function getValidStatus()
    {
        $list = self::getValidStatusList();
        if (isset($list[$this->valid])) {
            return $list[$this->valid];
        }

        return 'Unknown';
    }

    /**
     * Set publish.
     *
     * @param int $publish
     *
     * @return VcMinicv
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get publish.
     *
     * @return int
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Returns possible publish statuses as array.
     * @return array
     */
    public static function getPublishStatusList()
    {
        return [
            self::PROFIL_IS_PUBLIC => "Profil public",
            self::PROFIL_IS_PRIVATE => "Profil privé"
        ];
    }

    /**
     * Returns profile validation status as string.
     * @return string
     */
    public function getPublishStatus()
    {
        $list = self::getPublishStatusList();
        if (isset($list[$this->publish])) {
            return $list[$this->publish];
        }

        return 'Unknown';
    }

    /**
     * Set dateCreation.
     *
     * @param \DateTime|null $dateCreation
     *
     * @return VcMinicv
     */
    public function setDateCreation($dateCreation = null)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation.
     *
     * @return \DateTime|null
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateMaj.
     *
     * @param \DateTime $dateMaj
     *
     * @return VcMinicv
     */
    public function setDateMaj($dateMaj)
    {
        $this->dateMaj = $dateMaj;

        return $this;
    }

    /**
     * Get dateMaj.
     *
     * @return \DateTime
     */
    public function getDateMaj()
    {
        return $this->dateMaj;
    }

    /**
     * Set apec.
     *
     * @param \Adherents\Entity\VcApec|null $apec
     *
     * @return VcMinicv
     */
    public function setApec(\Adherents\Entity\VcApec $apec = null)
    {
        $this->apec = $apec;
        $apec->addMinicv($this);
        return $this;
    }

    /**
     * Get apec.
     *
     * @return \Adherents\Entity\VcApec|null
     */
    public function getApec()
    {
        return $this->apec;
    }

    /**
     * Set dispo.
     *
     * @param \Adherents\Entity\VcDispo|null $dispo
     *
     * @return VcMinicv
     */
    public function setDispo(\Adherents\Entity\VcDispo $dispo = null)
    {
        $this->dispo = $dispo;
        $dispo->addMinicv($this);
        return $this;
    }

    /**
     * Get dispo.
     *
     * @return \Adherents\Entity\VcDispo|null
     */
    public function getDispo()
    {
        return $this->dispo;
    }

    /**
     * Set mobilite.
     *
     * @param \Adherents\Entity\VcMobilite|null $mobilite
     *
     * @return VcMinicv
     */
    public function setMobilite(\Adherents\Entity\VcMobilite $mobilite = null)
    {
        $this->mobilite = $mobilite;
        $mobilite->addMinicv($this);
        return $this;
    }

    /**
     * Get mobilite.
     *
     * @return \Adherents\Entity\VcMobilite|null
     */
    public function getMobilite()
    {
        return $this->mobilite;
    }

    /**
     * Set user.
     *
     * @param \Adherents\Entity\User|null $mobilite
     *
     * @return VcMinicv
     */

    public function setUser(\Adherents\Entity\User $user = null)
    {
        $this->user = $user;
        $user->addMinicv($this);
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

    /**
     * Get competence.
     *
     * @return VcComp
     */
    public function getComp()
    {
        return $this->comp;
    }

    /**
     * add competence.
     *
     * @param  VcComp
     *
     */
    public function addComp($comp)
    {
        $this->comp[] = $comp;
        $comp->addMinicv($this);
    }

    public function removeComp($comp)
    {
        if (!$this->comp->contains($comp)) {
            return;
        }
        $this->comp->removeElement($comp);
        $comp->removeMinicv($this);
    }

    /**
     * Get competence Bis.
     *
     * @return VcCompBis
     */
    public function getCompBis()
    {
        return $this->compBis;
    }

    /**
     * add competence bis.
     *
     * @param  VcCompBis
     *
     */
    public function addCompBis($compBis)
    {
        $this->compBis[] = $compBis;
        $compBis->addMinicv($this);
    }

    public function removeCompBis($compBis)
    {
        if (!$this->compBis->contains($compBis)) {
            return;
        }
        $this->compBis->removeElement($compBis);
        $compBis->removeMinicv($this);
    }

    /**
     * Get secteur.
     *
     * @return VcSecteur
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * add secteur.
     *
     * @param  VcSecteur
     *
     */
    public function addSecteur($secteur)
    {
        $this->secteur[] = $secteur;
        $secteur->addMinicv($this);
    }

    public function removeSecteur($secteur)
    {
        if (!$this->secteur->contains($secteur)) {
            return;
        }
        $this->secteur->removeElement($secteur);
        $secteur->removeMinicv($this);
    }

    /**
     * Get savoiretre.
     *
     * @return VcSavoiretreList
     */
    public function getSavoirEtre()
    {
        return $this->se;
    }

    /**
     * add savoiretre.
     *
     * @param  VcSavoiretreList
     *
     */
    public function addSavoirEtre($se)
    {
        $this->se[] = $se;
        $se->addMinicv($this);
    }

    public function removeSavoirEtre($se)
    {
        if (!$this->se->contains($se)) {
            return;
        }
        $this->se->removeElement($se);
        $se->removeMinicv($this);
    }

    /**
     * Get contrat.
     *
     * @return VcContrat
     */

    public function getContrat()
    {
        return $this->contrat;
    }

    /**
     * add contrat.
     *
     * @param  VcContrat
     *
     */
    public function addContrat($contrat)
    {
        $this->contrat[] = $contrat;
        $contrat->addMinicv($this);
    }

    public function removeContrat($contrat)
    {
        if (!$this->contrat->contains($contrat)) {
            return;
        }
        $this->contrat->removeElement($contrat);
        $contrat->removeMinicv($this);
    }
}
