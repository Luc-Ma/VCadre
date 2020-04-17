<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;

class MinicvP2Form extends Form
{
    /**
     * Constructor.
     */
    public function __construct($edit=false)
    {

        // Define form name
        parent::__construct('minicvp2');

        // Set POST method for this form
        $this->setAttribute('method', 'post');
        $this->edit = $edit;
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        $submitvalue = $this->edit ? "Enregistrer" : "Suivant";
        //  exp
        $this->add([
            'type'  => 'text',
            'name' => 'xp',
            'options' => [
                'label' => 'Expérience du poste (en mois ou année) :  ',
                'placeholder' => '30 ans',
            ],
        ]);
        //  exp total
        $this->add([
            'type'  => 'text',
            'name' => 'xptot',
            'options' => [
                'label' => 'Expérience totale (en mois ou année) :  ',
            ],
        ]);
        // description
        $this->add([
            'type'  => 'textarea',
            'name' => 'formation',
            'options' => [
                'label' => 'Vos formations : ',
            ],
        ]);
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
        $inputFilter->add([
            'name' => 'xp',
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
        $inputFilter->add([
            'name' => 'xptot',
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
        $inputFilter->add([
            'name' => 'formation',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'max' => 600,
                    ],
                ],
            ],
        ]);
    }
}
/*
 */
