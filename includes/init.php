<?php
include 'config.php';
$controller	=	'';
$action		=	'';
function __autoload($class_name) 
{
    $filename = $class_name . '.php';
    $filename = str_replace('\\', '/', $filename);
    $file = __SITE_PATH . '/'. $filename;
    if (file_exists($file) == false)
    {
        return false;
    }
    include ($file);
}

$route = (empty($_GET['route'])) ? '' : $_GET['route'];
$parts  =   array();
if (empty($route))
{
        $controller 	= DEFAULT_CONTROLLER;
        $action 	= DEFAULT_ACTION;
}
else
{
        /*** get the parts of the route ***/
        $parts = explode('/', $route);
        $controller = $parts[0];
        if(isset($parts[1]))
        {
            $action = $parts[1];
        }
        else
        {
            $action 	= DEFAULT_ACTION;
        }
}

$class  =   'Application\\Controllers\\'.$controller;
if (is_callable(array($class, $action)) == false)
{
    echo 'Invalid Controller';exit;
}

$obj    =   new $class($parts);
$obj->$action();
?>
