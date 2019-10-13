<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcApec;

class MinicvP1Form extends Form
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
        parent::__construct('minicvp1');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function getArrayApec()
    {
        $myapecs = [];

        $apecs = $this->entityManager->getRepository(VcApec::class)->findAll();

        foreach ($apecs as $apec) {
            $myapecs[$apec->getId()] = $apec->getIntitule();
        }

        return $myapecs;
    }

    protected function addElements()
    {
        //apec
        $this->add([
            'type' => 'select',
            'name' => 'apec',
            'options' => [
                'label' => 'Selectionnez un Apec : ',
                'value_options' => $this->getArrayApec(),
            ],
        ]);
        //  intitulé
        $this->add([
            'type'  => 'text',
            'name' => 'intitule',
            'options' => [
                'label' => 'Intitulé :  ',
            ],
        ]);

        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Suivant',
                'id' => 'submit',
            ],
        ]);
    }

    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => 'intitule',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 255,
                    ],
                ],
            ],
        ]);
    }
}
/*
 */
