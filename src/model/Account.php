<?php

class Account{

    private $name;
    private $login;
    private $password;
    private $status;

    public function __construct($name, $login, $password, $status){
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
        $this->status = $status;
    }

    public function getName(){
        return $this->name;
    }

    public function getLogin(){
        return $this->login;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getStatus(){
        return $this->status;
    }
}

?>