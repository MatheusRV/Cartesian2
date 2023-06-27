<?php
  /*----------GENERAL----------*/
  session_start();
  ob_start();

  date_default_timezone_set('America/Sao_Paulo');

  $auto_load = function($class){
    if(file_exists(DIR.'/class/'.$class.'.php')){
      include('class/'.$class.'.php');
    }
  };

  spl_autoload_register($auto_load);
  /*------------------------------*/

  
  /*----------CONSTANTS----------*/
  define('INCLUDE_PATH','http://localhost/Projetos/Cartesian/');
  define('DIR',__DIR__); /*/
  /*------------------------------*/


  /*----------DATABASE----------*/
  define('HOST', 'localhost');
  define('USER', 'root');
  define('PASSWORD', '');
  define('DATABASE', 'cartesian');/*/
  /*------------------------------*/


  User::logged();
?>