<?php
namespace Adherents\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;

class DispoForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('dispo');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        //  intitulé
        $this->add([
            'type'  => 'text',
            'name' => 'dispo',
            'options' => [
                'label' => 'Disponibilité :  ',
            ],
        ]);
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Ajouter',
                'id' => 'submit',
            ],
        ]);
        //prevention
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                'timeout' => 600
                ]
            ],
        ]);
    }

    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name' => 'dispo',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 255,
                    ],
                ],
            ],
        ]);
    }
}
/*
 */
