<?php
//Get Environtment variable and set contants accordingly
$env = (getenv("APPLICATION_ENV")!='') ? getenv("APPLICATION_ENV") : 'default';
switch ($env) 
{
    case 'DEV_WASEEM':
        $host = 'localhost';
        $user = 'root';
        $password = 'test';
        $database = 'mgf_test';                
        break;
    default :
        $host = 'localsshost';
        $user = 'root';
        $password = 'test';
        $database = 'mgf_test';
}

define('HOST', $host);
define('USER_NAME', $user);
define('PASSWORD', $password);
define('DATABASE_NAME', $database);
define('MGF_API_URL', 'http://www.mgf.ltd.uk/software-test/api.php');
define('MGF_CREDENTIALS', serialize(array('mgf'=>'userData','apiKey'=>'123455678qwertyui')));


//Set Base URL
define('HTTP_TYPE', getProtocol());
define('BASE_URL', HTTP_TYPE . "://" . $_SERVER['HTTP_HOST']);

//Set default Controller and action
define('DEFAULT_CONTROLLER', 'users');
define('DEFAULT_ACTION', 'index');

$db_prefix = 'dvo_';
define("USERS_TABLE", $db_prefix . "users");


//Protocol function
function getProtocol() 
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) 
    {
        return $_SERVER['HTTP_X_FORWARDED_PROTO'];
    } 
    else 
    {
        return !empty($_SERVER['HTTPS']) ? "https" : "http";
    }
}
?>
