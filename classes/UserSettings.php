<?php
class UserSettings extends User implements JsonSerializable {


    public function __construct() {
        
    }


    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }
}