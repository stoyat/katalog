<?php
session_start();

require "config.php";

//пути подключаемых файлов
set_include_path(get_include_path()
				.PATH_SEPARATOR.CONTROLLER
				.PATH_SEPARATOR.MODEL
				.PATH_SEPARATOR.LIB
				);
                
//автозагрузка классов
function __autoload ($class_name) {
    
   
    if(!include_once ($class_name.".php")) {
		
	echo $class_name.'Не правильный файл для подключения';
		}		
}

$obj = Route_Controller::get_instance();
$obj->route();







?>