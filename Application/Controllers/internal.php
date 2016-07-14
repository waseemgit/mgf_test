<?php
namespace Application\Controllers;
use Application\AbstractClasses\users;

class internal extends users
{
    private  $type = 'internal';
    
    public function get_html_data() 
    {
        return parent::get_html_data($this->type);
    }
}