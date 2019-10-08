<?php
namespace Adherents\Form\Auth;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('user');

        $this->add([
            'name' => 'USERNAME',
            'type' => 'text',
            'options' => [
                'label' => 'Utilisateur',
            ],
        ]);
        $this->add([
            'name' => 'PASS',
            'type' => 'password',
            'options' => [
                'label' => 'Mot de passe',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Se connecter',
                'id'    => 'submitbutton',
            ],
        ]);
        $this->add([
            'type'  => 'checkbox',
            'name' => 'remember_me',
            'options' => [
                'label' => 'Se souvenir de moi',
            ],
        ]);
        $this->add([
            'type'  => 'hidden',
            'name' => 'redirect_url'
        ]);
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
}
