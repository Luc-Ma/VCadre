<?php
namespace Adherents\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcSecteur;

class SecteurForm extends Form
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
        parent::__construct('secteur');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function getArraySecteur()
    {
        return [
            VcSecteur::TYPE_PRIMAIRE => "Primaire",
            VcSecteur::TYPE_SECONDAIRE => "Secondaire",
            VcSecteur::TYPE_TERTIARE => "Tertiare"
        ];
    }

    protected function addElements()
    {
        //  intitulé
        $this->add([
            'type'  => 'text',
            'name' => 'nom',
            'options' => [
                'label' => 'Intitulé :  ',
            ],
        ]);
        //metier
        $this->add([
            'type' => 'select',
            'name' => 'type',
            'options' => [
                'label' => 'Selectionez un secteur ',
                'value_options' => $this->getArraySecteur(),
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
            'name' => 'nom',
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
            'name' => 'type',
            'required' => true,
        ]);
    }
}
/*
 */
