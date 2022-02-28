<?php
class Validate {
    private $symb = '&#10005; ';
    private $nL = '&#010;&#013;';
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function isValidRegister($alias, $login_email, $password){
        
        $formErrors = [];
        $formErrors['username'] = [];
        $formErrors['email'] = [];
        $formErrors['password'] = [];
        
        if($this->validAlias($alias)) {
            $formErrors['username'] = $this->validAlias($alias);
        }

        if($this->validEmail($login_email)) {
            $formErrors['email'] = $this->validEmail($login_email);
        }

        if($this->validPassword($password)) {
            $formErrors['password'] = $this->validPassword($password);
        }

        //return associative array with errors
        return $formErrors;
    }


    public function isValidLogin($password, $login_email) {
        try {
            //Check to see if the user is in the database
            $query = <<<SQL
                SELECT password 
                FROM user
                WHERE login_email = :login_email
            SQL;
            $statement = $this->db->prepare($query);
            $statement->bindValue(':login_email', $login_email);   
            $statement->execute();
            $userCredentials = $statement->fetch();
            $statement->closeCursor();      
            
            //guard clause
            //instantly return false if no records of login_email exist
            if (!$userCredentials) {
                return false;
            }
            
            //get password
            $hash = $userCredentials['password'];
            
            //Check password
            return password_verify($password, $hash);
        } catch (PDOException $e) {
            throw new PDOException($e);
            die();
        }
    } 



    public function validEmail($input) {
        $errors = [];
        
        if ($this->isEmpty($input)){
            array_push($errors, $this->isEmpty($input));
        }
        if($this->userExists($input)) {
            array_push($errors, $this->userExists($input));
        }
        if (filter_var($input, FILTER_VALIDATE_EMAIL) === false) {
            array_push($errors, $this->symb . "invalid email format" . $this->nL);
        } 
        return $errors;
    }




    public function validAlias($input) {
        $pattern = "/[\[<>;:\"\/\\|?*\/\]]/";
        $errors = [];
        
        if ($this->isEmpty($input)){
            array_push($errors, $this->isEmpty($input));
        }
        if($this->startWithLetter($input)) {
            array_push($errors, $this->symb . "must begin with a letter" . $this->nL);
        }
        if(strlen($input) < 4 || strlen($input) > 30){
            array_push($errors, $this->symb . "must be betweeen 4 and 30 characters" . $this->nL);
        }
        if(preg_match($pattern, $input)){
            array_push($errors, $this->symb . "Invalid characters" . $this->nL);  
        }
        return $errors;
        
    }
    
    public function validPassword($input) {
        $errors = [];
        
        if ($this->isEmpty($input)){
            array_push($errors, $this->isEmpty($input));
        }
        if(strlen($input) < 8) {
            array_push($errors, $this->symb . "must be at least 8 characters" . $this->nL);
        }
        if ($this->passwordComplexity($input)) {
            $new = $this->passwordComplexity($input);
            $errors = array_merge($errors, $new);
        }
        return $errors;
    }
    
    
    public function passwordComplexity($input) {
        $oneUppercase = "/[A-Z]{1,}/";
        $oneDigit = "/[0-9]{1,}/";
        $oneSpecial = "/\W{1,}/";
        $errors = [];
        
        if(!preg_match($oneUppercase, $input)) { 
            array_push($errors, $this->symb . "requires 1 uppercase letter" . $this->nL);
        }
        
        if (!preg_match($oneDigit, $input)) {
            array_push($errors, $this->symb . "requires 1 digit" . $this->nL);
        }
        
        if (!preg_match($oneSpecial, $input)) {
            array_push($errors, $this->symb . "requires one special character" . $this->nL);
        }
        return $errors;
    }

    
    //exist checks
    public function userExists($input) {
        try {
            //Check to see if the user is in the database
            $query = <<<SQL
                SELECT login_email 
                FROM user
                WHERE login_email = :login_email
            SQL;
            $statement = $this->db->prepare($query);
            $statement->bindValue(':login_email', $input);   
            $statement->execute();
            $result = $statement->fetch();
            $statement->closeCursor();

            if (!$result) {
                return '';
            }
            return $this->symb. "login_email already already in use" . $this->nL;    
        } catch (PDOException $e) {
            throw new PDOException($e);
            die();
        }
    }
    
    //utility functions
    public function isEmpty($input) {
        
        if (!empty($input)) {
            return '';
        }
        return $this->symb . "This is a required field" . $this->nL;
    }
    
    public function startWithLetter($input) {
        $pattern = "/^[a-zA-Z]/";
        if(preg_match($pattern, $input)) {
            return ''; 
        }
        return $this->symb . "Must begin with a letter" . $this->nL;
    }
    
}
