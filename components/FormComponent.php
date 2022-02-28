<?php
class FormComponent extends component {


    public function __construct() {
        
    }


    function createRegisterForm($vals=null, $errors=null) {
        $username = '';
        $email = '';
        $password = '';

        $vals = array_filter($vals);

        
        extract($vals,EXTR_PREFIX_ALL, 'u');
        if(isset($u_username)) {
        $username = $u_username;
        }
        if(isset($u_email)) {
        $email = $u_email;
        }
        if(isset($u_password)) {
        $password = $u_password;
        }
        

        $username_error = '';
        $email_error = '';
        $password_error = '';

        $err_u = '';
        $err_e = '';
        $err_p = '';

        $errors = array_filter(($errors));

        
        extract($errors, EXTR_PREFIX_ALL, 'error');
        if(isset($error_username)) {
            $username_error = $error_username;
            $err_u = 'invalid entry';
        }
        if(isset($error_email)) {
            $email_error = $error_email;
            $err_e = 'invalid entry';
        }

        if(isset($error_password)) {
            $password_error = $error_password;
            $err_p = 'invalid entry';
        }


        return <<<"HTML"
            <form method="post" action="index.php?route=register" class="form" id="register_form">
            <h1 class="form__title">Register</h1>
            <div class="form__container">
                <div class="form__input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="{$username}">
                    <span class="form__error" data-error="{$username_error}">{$err_u}</span>
                </div>
                <div class="form__input">
                    <label for="email">E-Mail</label>
                    <input type="text" name="email" id="email" value="{$email}">
                    <span class="form__error" data-error="{$email_error}">{$err_e}</span>
                </div>
                <div class="form__input">
                    <label for="password">Password</label>
                    <input type="text" name="password" id="password" value="{$password}">
                    <span class="form__error" data-error="{$password_error}">{$err_p}</span>
                </div>
            </div>
            <input class="form__submit" type="submit" name="register" value="register" >
           </form>
        HTML;
    }

    function createLoginForm($vals=null, $errors=null) {
        $email = '';
        $password = '';

        $vals = array_filter($vals);

        
        extract($vals,EXTR_PREFIX_ALL, 'u');
        if(isset($u_email)) {
        $email = $u_email;
        }
        if(isset($u_password)) {
        $password = $u_password;
        }
        
        $email_error = '';
        $password_error = '';

        $err_e = '';
        $err_p = '';

        $errors = array_filter(($errors));

        
        extract($errors, EXTR_PREFIX_ALL, 'error');

        if(isset($error_email)) {
            $email_error = $error_email;
            $err_e = 'invalid entry';
        }

        if(isset($error_password)) {
            $password_error = $error_password;
            $err_p = 'invalid entry';
        }

        return <<<"HTML"
            <form method="post" action="index.php?route=login" class="form" id="register_form">
            <h1 class="form__title">Login</h1>
            <div class="form__container">
                <div class="form__input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="{$email}">
                    <span class="form__error" data-error="{$email_error}">{$err_e}</span>
                </div>
                <div class="form__input">
                    <label for="password">Password</label>
                    <input type="text" name="password" id="password" value="{$password}">
                    <span class="form__error" data-error="{$password_error}">{$err_p}</span>
                </div>
            </div>
            <input class="form__submit" type="submit" name="login" value="login" >
           </form>
        HTML;
    }


}