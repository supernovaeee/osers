<?php
class Subject
{
    public $code;
    public $name;
    public $venue;
    public $type;

    function __construct($code, $name, $venue, $type)
    {
        $this->code = $code;
        $this->name = $name;
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
        echo '<br>' . $this->code . ' (' . $this->name . ')' . '<br>Venue: ' . $this->venue . '<br>Status: ' . $this->type . '<br>';
    }
}
?>