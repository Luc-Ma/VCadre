<?php
namespace Adherents\Helper;

use Zend\View\Helper\AbstractHelper;

use Adherents\Entity\VcMetier;
use Adherents\Entity\VcSavoiretre;

/**
 * This view helper class displays login informatioon
 */
class MinicvFormHelper extends AbstractHelper
{
    /**
     * entityManager; service.
     * @var array
     */
    private $entityManager;

    private $config;

    public function __construct($entityManager, $config)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    public function renderMetier($type = "comp")
    {
        $metiers = $this->entityManager->getRepository(VcMetier::class)->findAll();

        $value = "";
        foreach ($metiers as $metier) {
            $value .= '<option value="'.$metier->getId().'">'.$metier->getNom().'</option>';
        }
        $data = '<div class="row">';
        $option = ($type == "comp") ? "competence" : "competenceBis" ;

        for ($i = 0; $i < $this->config['Adherents']['options'][$option]; $i++) {
            $data .= '<div class="col">';
            $data .= "Selectionnez un métier : ";
            $data .= '<select id='.$i.' class="selectpicker m'.$option.'">';
            $data .= '<option selected disable value="0">Selectionnez un métier</option>';
            $data .= $value;
            $data .= '</select><br />';
            $data .= 'Selectionnez une compétence ';
            if ($type != "comp") {
                $data .= "secondaire ";
            }
            $data .= 'associée : <br />';
            $data .= '<select name="'.$type.$i.'" id="c'.$i.'" class="selectpicker '.$option.'">';
            $data .= '</select>';
            $data .= '</div>';
        }
        $data .= '</div>';
        return $data;
    }
    public function renderSecteur()
    {
        $data = '<div class="row">';

        for ($i = 0; $i < $this->config['Adherents']['options']['secteur']; $i++) {
            $data .= '<div class="col">';
            $data .= "Selectionnez un secteur cible : ";
            $data .= '<select id='.$i.' class="selectpicker msecteur">';
            $data .= '<option selected disable>options</option>';
            $data .= '<option value="0">Primaire</option>';
            $data .= '<option value="1">Secondaire</option>';
            $data .= '<option value="2">Tertiaire</option>';
            $data .= '</select><br />';
            $data .= 'Selectionnez un secteur cible ';
            $data .= '<select name="secteur'.$i.'" id="c'.$i.'" class="selectpicker secteur">';
            $data .= '</select>';
            $data .= '</div>';
        }
        $data .= '</div>';
        return $data;
    }

    public function renderSe($type = "comp")
    {
        $ses = $this->entityManager->getRepository(VcSavoiretre::class)->findAll();

        $value = "";
        foreach ($ses as $se) {
            $value .= '<option value="'.$se->getId().'">'.$se->getNom().'</option>';
        }
        $data = '<div class="row">';
        $split = true;
        for ($i = 0; $i < $this->config['Adherents']['options']['savoiretre']; $i++) {
            $data .= '<div class="col-6 col-sm-3">';
            $data .= "Selectionnez une catégorie : ";
            $data .= '<select id='.$i.' class="selectpicker mse">';
            $data .= '<option selected disable value="0">catégories</option>';
            $data .= $value;
            $data .= '</select><br />';
            $data .= 'Selectionnez un savoir être ';
            $data .= 'associée : <br />';
            $data .= '<select name="se'.$i.'" id="c'.$i.'" class="selectpicker savoiretre">';
            $data .= '</select>';
            $data .= '</div>';
            if($split && $i > 2) {
                    $data .= "<div class=\"w-100\"></div>";
                    $split = false;
            }

        }
        $data .= '</div>';
        return $data;
    }
}
