<?php
//namespace includes\config; 
        session_start();
        
    
	
	
	#---------------------------DEFINING DATABASE CONSTANTS-----------------------------------------------------#
		
//        const HOST          = 'localhost';
//        const USER_NAME     = 'root';
//        const PASSWORD      = 'test';
//        const DATABASE_NAME = 'dvo_test';
	
        define('HOST', 'localhost');
        define('USER_NAME', 'root');
        define('PASSWORD', 'test');
        define('DATABASE_NAME', 'dvo_test');
        
	#-----------------------------------------------------------------------------------------------------------#
	
	
	#---------------------------DEFINING BASE URL CONSTANTS-----------------------------------------------------#
	
	define('HTTP_TYPE'	,	getProtocol());
	define('HTTP_ROOT'	, 	$_SERVER['HTTP_HOST']);
	define('HTTP_FOLDER'	, 	'/dvo_test1');
	define('BASE_URL'	,	HTTP_TYPE . "://" . HTTP_ROOT . HTTP_FOLDER);
	define('DOCUMENT_ROOT'	, 	$_SERVER['DOCUMENT_ROOT']);
	define('WEBSITE_ROOT'	,   	HTTP_FOLDER === '' ? DOCUMENT_ROOT : DOCUMENT_ROOT.HTTP_FOLDER);
	define('DEFAULT_CONTROLLER'		,'users');
	define('DEFAULT_ACTION'			,'login');
	#-----------------------------------------------------------------------------------------------------------#
	
	
	#----------------------DEFINING DATABASE TABLE NAMES CONSTANTS----------------------------------------------#
	
	$prefix	=	'dvo_';
	define("USERS_TABLE"	,	$prefix."users"	);
	#-----------------------------------------------------------------------------------------------------------#
	
	
	#-------------------------------------------------------------------------------------------------------#
	
	
	#---------------------DEFINING SOME FUNCTION USED IN WITHIN CONFIG FILE--------------------------------#
		
	function getProtocol() 
	{
            if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']))
            {
                    return $_SERVER['HTTP_X_FORWARDED_PROTO'];
            }
            else
            { 
                    return !empty($_SERVER['HTTPS']) ? "https" : "http";
            }
	}
	#------------------------------------------------------------------------------------------------------#
	

	
?>
