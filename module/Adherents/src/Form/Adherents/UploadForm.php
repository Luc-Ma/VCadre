<?php
namespace Adherents\Form\Adherents;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\Extension;
use Zend\Validator\File\FilesSize;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\UploadFile;

class UploadForm extends Form
{
    private $filename;

    /**
     * Constructor.
     */
    public function __construct($user)
    {
        // Define form name
        parent::__construct('upload');

        $this->filename = "cv_".$user->getLastname()."_".$user->getFirstname();
        $this->filename .= uniqid().".pdf";
        
        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        //fichier pdf cv
        $this->add([
            'type'  => 'file',
            'name' => 'cv',
            'options' => [
                'label' => 'Selectionnez votre cv (format pdf) : ',
            ],
        ]);
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
            'type'     => FileInput::class,
            'name' => 'cv',
            'required' => true,
            'validators' => [
                [
                    'name' => Extension::class,
                    'options' => [
                        'extension' => ['pdf'],
                        'case' => false,
                    ],
                ],
                [
                    'name' => FilesSize::class,
                    'options' => [
                        'min' => '1kB',
                        'max' => '5MB',
                    ],
                ],
                [
                    'name' => MimeType::class,
                    'options' => [
                        'mimeType' => 'application/pdf',
                    ],
                ],
                [
                    'name' => UploadFile::class
                ],
            ],
            'filters' => [
                [
                    'name' => RenameUpload::class,
                    'options' => [
                        'target' => './public/cv/'.$this->filename,
                        'overwrite' => false,
                        'randomize' => false,
                    ]
                ],
            ],
        ]);
    }
}
/*
 */
