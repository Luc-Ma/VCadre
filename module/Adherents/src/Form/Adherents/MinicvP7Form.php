<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Adherents\Entity\VcSecteur;

class MinicvP6Form extends Form
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
        $this->add([
            'type' => 'select',
            'name' => 'se',
            'options' => [
                'label' => 'Selectionnez un savoiretre : ',
                'value_options' => $this->getArraySe(),
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
