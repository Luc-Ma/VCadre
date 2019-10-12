<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcApec;
use Adherents\Entity\VcMetier;
use Adherents\Entity\VcContrat;
use Adherents\Entity\VcMobilite;
use Adherents\Entity\VcComp;
use Adherents\Entity\VcCompBis;

class MinicvP1Form extends Form
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    private $config = null;
    /**
     * Constructor.
     */
    public function __construct($entityManager, $config)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
        // Define form name
        parent::__construct('minicv');

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

    private function getArrayCompBis()
    {
        $mycomps = [];

        $comps = $this->entityManager->getRepository(VcCompBis::class)->findAll();

        foreach ($comp as $comp) {
            $mycomps[$comp->getId()] = $comp->getNom();
        }

        return $mycomps;
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
        //  exp
        $this->add([
            'type'  => 'text',
            'name' => 'xp',
            'options' => [
                'label' => 'Expérience du poste :  ',
            ],
        ]);
        //  exp total
        $this->add([
            'type'  => 'text',
            'name' => 'xptot',
            'options' => [
                'label' => 'Expérience totale :  ',
            ],
        ]);
        //contrat
        $this->add([
            'type' => 'MultiCheckbox',
            'name' => 'contrat',
            'options' => [
                'label' => 'Contrat : ',
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
                'label' => 'Votre location :  ',
            ],
        ]);
        // compétence
        for ($i = 0; $i < $this->config['Adherents']['options']['competence'] ; $i++) {
            $this->add([
                'type' => 'select',
                'name' => 'comp'.$i,
                'options' => [
                    'label' => 'Selectionnez une compétence : ',
                    'value_options' => $this->getArrayComp(),
                ],
            ]);
        }
        // compétence complémentaire
        for ($i = 0; $i < $this->config['Adherents']['options']['competenceBis'] ; $i++) {
            $this->add([
                'type' => 'select',
                'name' => 'compBis'.$i,
                'options' => [
                    'label' => 'Selectionnez une compétence complémentaire : ',
                    'value_options' => $this->getArrayCompBis(),
                ],
            ]);
        }
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Envoyer',
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
                        'min' => 1,
                        'max' => 255,
                    ],
                ],
            ],
        ]);
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
