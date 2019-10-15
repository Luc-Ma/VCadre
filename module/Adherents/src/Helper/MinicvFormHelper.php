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
}
