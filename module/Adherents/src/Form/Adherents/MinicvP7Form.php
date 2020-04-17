<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcSavoiretreList;

class MinicvP7Form extends Form
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
        parent::__construct('minicvp7');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function getArraySe()
    {
        $myses = [];

        $ses = $this->entityManager->getRepository(VcSavoiretreList::class)->findAll();

        foreach ($ses as $se) {
            $myses[$se->getId()] = $se->getNom();
        }

        return $myses;
    }

    protected function addElements()
    {
        $submitvalue = $this->edit ? "Enregistrer" : "Suivant";
        for ($i = 0; $i < $this->config['Adherents']['options']['savoiretre']; $i++) {
            $this->add([
                'type' => 'select',
                'name' => 'se'.$i,
                'options' => [
                    'label' => 'Selectionnez un savoiretre : ',
                    'value_options' => $this->getArraySe(),
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
