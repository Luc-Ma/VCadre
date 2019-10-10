<?php
namespace Adherents\Helper;

use Zend\View\Helper\AbstractHelper;
use Adherents\Entity\VcApec;
use Adherents\Entity\VcMetier;
use Adherents\Entity\VcComp;
use Adherents\Entity\VcCompBis;
use Adherents\Entity\VcSecteur;
use Adherents\Entity\VcSavoiretre;
use Adherents\Entity\VcSavoiretreList;

/**
 * This view helper class displays login informatioon
 */
class AdminHelper extends AbstractHelper
{
    /**
     * entityManager; service.
     * @var array
     */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function renderSeCat()
    {
        $ses = $this->entityManager->getRepository(VcSavoiretre::class)->findAll();


        $result = '<select id="ise" class="multis bg-transparent" name="se[]" multiple>';

        foreach ($ses as $se) {
            $result .= '<option value="'.$se->getId().'">';
            $result .= $se->getNom();
            $result .= '</option>';
        }

        $result .= '</select>';

        return $result;
    }

    public function renderSe()
    {
        $ses = $this->entityManager->getRepository(VcSavoiretreList::class)->findAll();


        $result = '<select id="ise" class="multis bg-transparent" name="se[]" multiple>';

        foreach ($ses as $se) {
            $result .= '<option value="'.$se->getId().'">';
            $result .= $se->getSavoiretre()->getNom()." => ".$se->getNom();
            $result .= '</option>';
        }

        $result .= '</select>';

        return $result;
    }

    public function renderSecteur()
    {
        $secteurs = $this->entityManager->getRepository(VcSecteur::class)->findAll();


        $result = '<select id="isecteur" class="multis bg-transparent" name="secteur[]" multiple>';

        foreach ($secteurs as $secteur) {
            $result .= '<option value="'.$secteur->getId().'">';
            $result .= $secteur->getTypeStatus()." => ".$secteur->getNom();
            $result .= '</option>';
        }

        $result .= '</select>';

        return $result;
    }

    public function renderComp()
    {
        $comps = $this->entityManager->getRepository(VcComp::class)->findAll();


        $result = '<select id="icomp" class="multis bg-transparent" name="comp[]" multiple>';

        foreach ($comps as $comp) {
            $result .= '<option value="'.$comp->getId().'">';
            $result .= $comp->getMetier()->getNom()." => ".$comp->getNom();
            $result .= '</option>';
        }

        $result .= '</select>';

        return $result;
    }

    public function renderCompBis()
    {
        $comps = $this->entityManager->getRepository(VcCompBis::class)->findAll();


        $result = '<select id="icompbis" class="multis bg-transparent" name="compbis[]" multiple>';

        foreach ($comps as $comp) {
            $result .= '<option value="'.$comp->getId().'">';
            $result .=  $comp->getMetier()->getNom()." => ".$comp->getNom();
            $result .= '</option>';
        }

        $result .= '</select>';

        return $result;
    }

    public function renderApec()
    {
        $apecs = $this->entityManager->getRepository(VcApec::class)->findAll();


        $result = '<select id="iapec" class="multis bg-transparent" name="apec[]" multiple>';

        foreach ($apecs as $apec) {
            $result .= '<option value="'.$apec->getId().'">';
            $result .= $apec->getIntitule();
            $result .= '</option>';
        }

        $result .= '</select>';

        return $result;
    }

    public function renderMetier()
    {
        $apecs = $this->entityManager->getRepository(VcMetier::class)->findAll();


        $result = '<select id="imetier" class="multis bg-transparent" name="metier[]" multiple>';

        foreach ($apecs as $apec) {
            $result .= '<option value="'.$apec->getId().'">';
            $result .= $apec->getNom();
            $result .= '</option>';
        }

        $result .= '</select>';

        return $result;
    }
}
