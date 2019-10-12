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

    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
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

        foreach ($comp as $comp) {
            $mycomps[$comp->getId()] = $comp->getNom();
        }

        return $mycomps;
    }

    protected function addElements()
    {
        $this->add([
            'type' => 'select',
            'name' => 'comp',
            'options' => [
                'label' => 'Selectionnez une compétence : ',
                'value_options' => $this->getArrayComp(),
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
