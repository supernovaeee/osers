<?php
class User
{
    public $ID;
    public $sID;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $password;
    public $type;

    function __construct($sID, $name, $surname, $phone, $email, $type, $password)
    {
        $this->sID = $sID;
        $this->name = $name;
        $this->surname = $surname;
        $this->phone = $phone;
        $this->email = $email;
        $this->type = $type;
        $this->password = $password;
    }
    function get_ID()
    {
        return $this->ID;
    }
    function get_sID()
    {
        return $this->sID;
    }
    function set_sID($sID)
    {
        $this->sID = $sID;
    }
    function get_name()
    {
        return $this->name;
    }
    function set_name($name)
    {
        $this->name = $name;
    }
    function get_surname()
    {
        return $this->surname;
    }
    function get_phone()
    {
        return $this->phone;
    }
    function get_email()
    {
        return $this->email;
    }
    function get_type()
    {
        return $this->type;
    }
    function get_password()
    {
        return $this->password;
    }
}
?>