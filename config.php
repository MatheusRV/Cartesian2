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

  /*----------DATABASE----------*/
  //define('BROKER_ADDRESS', getHostByName(getHostName()));
  define('BROKER_ADDRESS', '192.168.0.122');
  define('BROKER_PORT', 1883);
  define('BROKER_CLIENT', 'system');
  define('AUTHORIZATION_USERNAME', 'matheus');
  define('AUTHORIZATION_PASSWORD', 'luma2023');
  /*------------------------------*/


  User::logged();
?>