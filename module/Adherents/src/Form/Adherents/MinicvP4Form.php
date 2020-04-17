<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcComp;

class MinicvP4Form extends Form
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
        parent::__construct('minicvp4');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function getArrayComp()
    {
        $mycomps = [];

        $comps = $this->entityManager->getRepository(VcComp::class)->findAll();

        foreach ($comps as $comp) {
            $mycomps[$comp->getId()] = $comp->getNom();
        }

        return $mycomps;
    }

    protected function addElements()
    {
        $submitvalue = $this->edit ? "Enregistrer" : "Suivant";
        for ($i = 0; $i < $this->config['Adherents']['options']['competence']; $i++) {
            $this->add([
                'type' => 'select',
                'name' => 'comp'.$i,
                'options' => [
                    'label' => 'Selectionnez une compétence : ',
                    'value_options' => $this->getArrayComp(),
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
