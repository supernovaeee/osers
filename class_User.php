<?php
class User
{
    public $sID;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $type;

    function __construct($sID, $name, $phone, $email, $type, $password)
    {
        $this->sID = $sID;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->type = $type;
        $this->password = $password;
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
    public function display()
    {
        return '<div class = "subjectItem">' . '<br>Student ID:' . $this->sID . '<br>Student Name:' . $this->name . '<br>Phone number:' . $this->phone . '<br>Email: ' . $this->email . ' </div>';
    }
}
?>