<?php
class FormController extends Controller {
    

    function __construct(\Model $model) {
        parent::__construct($model);
      
        if ($_GET['route'] === 'login'){
            $this->login();
        }
        if ($_GET['route'] === 'register') {
            $this->register();
        }

        
    }


    public function login() {
        if(isset($_POST['login'])) {
            $email = filter_input(INPUT_POST, 'email');
            $password = filter_input(INPUT_POST, 'password');
            $this->loginUser($email, $password);
        } else {
            $this->loginForm();
        }
    }

    public function register() {
        if(isset($_POST['register'])) {
            $alias = filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');
            $login_email = filter_input(INPUT_POST, 'email');

            $this->registerUser($alias, $login_email, $password);
        } else {
            $this->registerForm();
        }
    }

    private function loginUser($login_email, $password) {
        $database = new Database();
        $db = $database->getConnection();
        $user = new UserHandler($db);
        $valid = $user->loginUser($login_email, $password);

        if($valid) {
            $_SESSION['user'] = $user->getUser();
            //Maybe profile?
            header('Location: index.php');
        } 
            
        //non specific error
        $email_error = 'invalid email';
        $password_error = 'invalid password';
        $this->model->generateLoginForm(
            $login_email, $password,
            $email_error, $password_error
            );
        


    }
    private function registeruser( $alias, $login_email, $password) {
        $database = new database();
        $db = $database->getConnection();
        $user = new UserHandler($db);

        //define and empty
        $username_error = '';
        $password_error = '';
        $email_error = '';

        //call validation
        $errors = $user->validateCreateUser($alias,$login_email,$password);

        if (!$errors) {
            //maybe profile?
            $_SESSION['user'] = $user->getUser();
            header('Location: index.php');
        }

        if(is_array($errors)) {
            
            extract($errors, EXTR_PREFIX_ALL, 'error');

            $username_error .= isset($error_username) ?
                                implode('', $error_username) : '';

            $email_error .= isset($error_email) ?
                                implode('', $error_email) : '';

            $password_error .= isset($error_password) ?
                                implode('', $error_password) : '';
            
        }

        $this->model->generateRegisterForm(
            $alias, $login_email, $password,
            $username_error, $email_error, $password_error
        );

    }

    private function loginForm() {
        $this->model->generateLoginForm();
    }
    private function registerForm() {
        $this->model->generateRegisterForm();
    }


}