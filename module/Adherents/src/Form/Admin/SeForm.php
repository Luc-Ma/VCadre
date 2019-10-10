<?php
namespace Adherents\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcSavoiretre;
use DoctrineModule\Validator\ObjectExists as ObjectExistsValidator;

class SeForm extends Form
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
        parent::__construct('selist');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    private function getArraySeCat()
    {
        $mycats = [];

        $seCats = $this->entityManager->getRepository(VcSavoiretre::class)->findAll();

        foreach ($seCats as $seCat) {
            $mycats[$seCat->getId()] = $seCat->getNom();
        }

        return $mycats;
    }

    protected function addElements()
    {
        //  intitulé
        $this->add([
            'type'  => 'text',
            'name' => 'nom',
            'options' => [
                'label' => 'Intitulé du savoir être :  ',
            ],
        ]);
        //metier
        $this->add([
            'type' => 'select',
            'name' => 'secat',
            'options' => [
                'label' => 'Selectionez une catégorie : ',
                'value_options' => $this->getArraySeCat(),
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
            'name' => 'secat',
            'required' => true,
            'validators' => [
                [
                    'name' => ObjectExistsValidator::class,
                    'options' => [
                        'object_repository' => $this->entityManager->getRepository(VcSavoiretre::class),
                        'fields' => 'id',
                        'messages' => [
                            'noObjectFound' => 'La catégorie n\'exsite pas',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
/*
 */
