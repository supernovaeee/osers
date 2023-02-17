<?php
class Subject
{
    public $code;
    public $name;
    public $lecturer;
    public $venue;
    public $type;

    function __construct($code, $name, $lecturer, $venue, $type)
    {
        $this->code = $code;
        $this->name = $name;
        $this->lecturer = $lecturer;
        $this->venue = $venue;
        $this->type = $type;
    }
    function get_code()
    {
        return $this->code;
    }
    function get_name()
    {
        return $this->name;
    }
    function get_lecturer()
    {
        return $this->lecturer;
    }
    function get_venue()
    {
        return $this->venue;
    }
    function get_type()
    {
        return $this->type;
    }
    public function display()
    {
        echo $this->code . ' (' . $this->name . ')' . '<br>Lecturer: ' . $this->lecturer . '<br>Venue: ' . $this->venue . '<br>Status: ' . $this->type . '<br>' . '<br>';
    }
}
?>