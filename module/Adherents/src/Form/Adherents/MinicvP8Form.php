<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcSavoiretreList;

class MinicvP8Form extends Form
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
        parent::__construct('minicvp8');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        //  intitulé
        $this->add([
            'type'  => 'textarea',
            'name' => 'infos',
            'options' => [
                'label' => 'Informations complémentaires :  ',
            ],
        ]);

        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Enregistrer',
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
