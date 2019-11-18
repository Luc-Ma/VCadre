<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcContrat;
use Adherents\Entity\VcMobilite;
use Adherents\Entity\VcDispo;

class MinicvP3Form extends Form
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
        parent::__construct('minicvp3');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }


    private function getArrayContrat()
    {
        $mycontrats = [];

        $contrats = $this->entityManager->getRepository(VcContrat::class)->findAll();

        foreach ($contrats as $contrat) {
            $mycontrats[$contrat->getId()] = $contrat->getType();
        }

        return $mycontrats;
    }

    private function getArrayDispo()
    {
        $mydispos = [];

        $dispos = $this->entityManager->getRepository(VcDispo::class)->findAll();

        foreach ($dispos as $dispo) {
            $mydispos[$dispo->getId()] = $dispo->getDispo();
        }

        return $mydispos;
    }

    private function getArrayMob()
    {
        $mymobs = [];

        $mobs = $this->entityManager->getRepository(VcMobilite::class)->findAll();

        foreach ($mobs as $mob) {
            $mymobs[$mob->getId()] = $mob->getMobilite();
        }

        return $mymobs;
    }

    protected function addElements()
    {

        //contrat
        $this->add([
            'type' => 'MultiCheckbox',
            'name' => 'contrat',
            'options' => [
                'label' => 'Selectionnez un type de contrat (plusieur choix possible)  : ',
                'value_options' => $this->getArrayContrat()
            ],
        ]);
        //dispo
        $this->add([
            'type' => 'select',
            'name' => 'dispo',
            'options' => [
                'label' => 'Selectionnez votre disponibilité : ',
                'value_options' => $this->getArrayDispo(),
            ],
        ]);
        //dispo
        $this->add([
            'type' => 'select',
            'name' => 'mob',
            'options' => [
                'label' => 'Selectionnez votre mobilité : ',
                'value_options' => $this->getArrayMob(),
            ],
        ]);
        //  mobilité source
        $this->add([
            'type'  => 'text',
            'name' => 'source',
            'options' => [
                'label' => 'Votre localisation :  ',
                'placeholder' => 'votre ville ou grande ville la plus proche',
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
            'name' => 'source',
            'required' => false,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 255,
                    ],
                ],
            ],
        ]);
    }
}
/*
 */
