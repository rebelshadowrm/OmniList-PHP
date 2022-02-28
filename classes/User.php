<?php
class User implements JsonSerializable {
    protected $id;
    protected $alias;
    protected $login_email;
    // email by default is set to login_email
    protected $email;
    protected $logged_in_at;
    protected $registered_at;
    //futher things like settings and so on


    public function __construct($id, $alias, $login_email, $logged_in_at, $registered_at, $email=null) {
        $this->id = $id;
        $this->alias = $alias;
        $this->login_email = $login_email;
        if($email == null) {
        $this->email = $login_email;
        } else {
            $this->email = $email;
        }
        $this->logged_in_at = $logged_in_at;
        $this->registered_at = $registered_at;
    }

    public function getId() {
        return $this->id;
    }

    public function getAlias() {
        return $this->alias;
    }

    public function getLoginEmail() {
        return $this->login_email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getLoggedInAt() {
        return $this->logged_in_at;
    }

    public function getRegisteredAt() {
        return $this->registered_at;
    }


    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }
}