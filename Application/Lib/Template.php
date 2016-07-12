<?php
namespace Application\Lib;
class Template
{
    private $params = array();
    function __construct($params) 
    {
        $this->params = $params;    
    }
    public function show($view_name)
    {
         $path = __SITE_PATH . '/Application/Views/' . $view_name . '.php';

        if (file_exists($path) == false)
        {
                throw new Exception('Template not found in '. $path);
                return false;
        }      
        $parameters =  $this->params;
        include ($path);
    }
    public function main($include_view_name)
    {
      
        $path = __SITE_PATH . '/Application/Views/main.php';
        if (file_exists($path) == false)
        {
            throw new Exception('Template not found in '. $path);
            return false;
        }
        $view_name = __SITE_PATH . '/Application/Views/'.$include_view_name.'.php';
        $parameters=  $this->params;
        include ($path);
    }
}
