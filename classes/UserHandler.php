<?php

class UserHandler {
    protected $alias;
    protected $login_email;
    private $password;
    protected $hashedPassword;
    protected $id;
    protected $db;
    protected $logged_in_at;
    protected $registered_at;
    protected $email;



    public function __construct($db) {
        $this->db = $db;
    }

    public function getUser() {
        //Todo: add more to the user creation to reflect DB
        return new User(
            $this->id,
            $this->alias,
            $this->login_email,
            $this->logged_in_at,
            $this->registered_at,
            $this->email
        );
    }

    public function getUserById($id) {
        try {
            $query = <<<SQL
                select u.login_email, us.user_email, us.user_alias,
                 us.prefers_privacy, u.logged_in_at, u.registered_at
                from user u
                inner join user_settings us
                on u.user_id = us.user_id
                where u.user_id = :id
            SQL;
            $statement = $this->db->prepare($query);
            $statement->bindParam(":id", $id);
            $statement->execute();
            $res = $statement->fetch(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            // user not found
            if(!$res) {
                return false;
            }
            // user prefers privacy
            // this is set to false by default, but in production would likely be true
            if($res['prefers_privacy']) {
                return false;
            }

            $alias = $res['user_alias'];
            $logged_in_at = $res['logged_in_at'];
            $registered_at = $res['registered_at'];
            $login_email = $res['login_email'];
            $email = $res['user_email'];

            return new User($id, $alias, $login_email, $logged_in_at, $registered_at, $email);

        } catch(PDOException $e) {
            throw new PDOException($e);
            die();
        }
    }

    public function validateCreateUser($alias, $login_email, $password) {
        $validation = new Validate($this->db);
        $errors = $validation->isValidRegister($alias, $login_email, $password);
        $errors = array_filter($errors);
        //validation code here
        if(!$errors) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $id = $this->createUser($alias, $login_email, $password);
            if($id) {
                $this->id = $id;
                return '';
            }
        }
        return $errors;
    }

    private function createUser($alias, $login_email, $password) {
        try {
            $query = <<<SQL
                call createUser(:login_email, :password, :alias)             
            SQL;
            $statement = $this->db->prepare($query);

            // bind values
            $statement->bindParam(":login_email", $login_email);
            $statement->bindParam(":password", $password);
            $statement->bindParam(":alias", $alias);
            
            // execute query
            if($statement->execute()) {
                $this->alias = $alias;
                $this->login_email = $login_email;
                $this->email = $login_email;
                // create dates so we don't have to query again
                // maybe update SP to return these values?
                $this->registered_at = date('dmY');
                $this->logged_in_at = date('dmY');

                $res = $statement->fetch(PDO::FETCH_ASSOC);
                $statement->closeCursor();
                return $res['user_id'];
            }
            return false;
        } catch(PDOException $e) {
            throw new PDOException($e);
            die();
        }
    }

    public function loginUser($login_email, $password) {
        try {
            $this->password = $password;
            $validation = new validate($this->db);
            $valid = $validation->isValidLogin($password, $login_email);

            if($valid) {

                $query = <<<SQL
                    select u.user_id, u.login_email, u.password, us.user_alias,
                     u.registered_at, u.logged_in_at, us.user_email
                    from user u
                    inner join user_settings us
                    on u.user_id = us.user_id
                    where u.login_email = :login_email
                SQL;
                $statement = $this->db->prepare($query);

                // bind values
                $statement->bindParam(":login_email", $login_email);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $statement->closeCursor();
                //if username exists
                if($result) {
                    $this->hashedPassword = $result['password'];
                    if($this->verifyPassword()) {
                        $this->login_email = $result['login_email'];
                        $this->email = $result['user_email'];
                        $this->alias = $result['user_alias'];
                        $this->id = $result['user_id'];
                        $this->registered_at = $result['registered_at'];

                        //Todo: create SP for updating logged_in_at, get new date
                        $this->logged_in_at = date('dmY');
                        return true;
                    }
                }
            }
            return false;
        } catch (PDOException $e) {
            throw new PDOException($e);
            die();
        }
    }

    private function verifyPassword() {
        return password_verify($this->password, $this->hashedPassword);
    }

}