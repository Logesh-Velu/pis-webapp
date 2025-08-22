<?php
$whitelist = array('127.0.0.1', "::1",'localhost');
if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    //local
    define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'aimm_pis');
}else{
    //server
    define('DB_USERNAME', 'digitos4_demousr');
	define('DB_PASSWORD', 'BXqbmN2.}9=6');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'digitos4_pis');
}
?>