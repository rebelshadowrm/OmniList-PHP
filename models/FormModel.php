<?php 
class FormModel extends Model {
    public $HTMLform = '';
    public $formType;
    protected $formComponent;

    public function __construct() {
        $this->formComponent = new FormComponent();
    }

    public function generateRegisterForm(
        $alias = null, $login_email = null, $password = null,
        $username_error = null, $email_error = null, $password_error = null
         ) {
        $this->formType = 'register';

        $vals = [
            'username' => $alias,
            'email' => $login_email,
            'password' => $password
        ];

        $errors = [
            'username' => $username_error,
            'password' => $password_error,
            'email' => $email_error
        ];

        
        $this->HTMLform = $this->formComponent->createRegisterForm($vals, $errors);
    }

    public function generateLoginForm(
        $email = null, $password = null,
        $email_error = null, $password_error = null
        ) {
        $this->formType = 'login';

        $vals = [
            'email' => $email,
            'password' => $password
        ];

        $errors = [
            'email' => $email_error,
            'password' => $password_error
        ];

        $this->HTMLform = $this->formComponent->createLoginForm($vals, $errors);
    }

}