<?php
namespace Application\Controllers;
use Application\AbstractClasses\users;

class external extends users
{
    private  $type = 'external';
    
    public function get_html_data() 
    {
        return parent::get_html_data($this->type);
    }    
}