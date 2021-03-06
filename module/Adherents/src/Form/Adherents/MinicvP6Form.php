<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcSecteur;

class MinicvP6Form extends Form
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;
    private $config;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $config,$edit=false)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
        $this->edit = $edit;
        // Define form name
        parent::__construct('minicvp6');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function getArraySecteur()
    {
        $mysecteurs = [];

        $secteurs = $this->entityManager->getRepository(VcSecteur::class)->findAll();

        foreach ($secteurs as $secteur) {
            $mysecteurs[$secteur->getId()] = $secteur->getNom();
        }

        return $mysecteurs;
    }

    protected function addElements()
    {
        $submitvalue = $this->edit ? "Enregistrer" : "Suivant";
        for ($i = 0; $i < $this->config['Adherents']['options']['secteur']; $i++) {
            $this->add([
                'type' => 'select',
                'name' => 'secteur'.$i,
                'options' => [
                    'label' => 'Selectionnez un secteur : ',
                    'value_options' => $this->getArraySecteur(),
                ],
            ]);
        }

        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => $submitvalue,
                'id' => 'submit',
            ],
        ]);
    }

    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = $this->getInputFilter();
    }
}
/*
 */
