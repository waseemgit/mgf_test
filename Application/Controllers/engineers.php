<?php
namespace Application\Controllers;
use Application\AbstractClasses\users;

class engineers extends users
{
    private  $type = 'engineers';
    
    public function get_html_data() 
    {
        return parent::get_html_data($this->type);
    }
}