<?php
namespace Adherents\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcMetier;
use DoctrineModule\Validator\ObjectExists as ObjectExistsValidator;

class CompForm extends Form
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
        parent::__construct('comp');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function getArrayMetier()
    {
        $mymetiers = [];

        $metiers = $this->entityManager->getRepository(VcMetier::class)->findAll();

        foreach ($metiers as $metier) {
            $mymetiers[$metier->getId()] = $metier->getNom();
        }

        return $mymetiers;
    }

    protected function addElements()
    {
        //  intitulé
        $this->add([
            'type'  => 'text',
            'name' => 'nom',
            'options' => [
                'label' => 'Intitulé de la compétence :  ',
            ],
        ]);
        //metier
        $this->add([
            'type' => 'select',
            'name' => 'metier',
            'options' => [
                'label' => 'Selectionez un métier : ',
                'value_options' => $this->getArrayMetier(),
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
            'name' => 'metier',
            'required' => true,
            'validators' => [
                [
                    'name' => ObjectExistsValidator::class,
                    'options' => [
                        'object_repository' => $this->entityManager->getRepository(VcMetier::class),
                        'fields' => 'id',
                        'messages' => [
                            'noObjectFound' => 'Le Metier n\'exsite pas',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
/*
 */
