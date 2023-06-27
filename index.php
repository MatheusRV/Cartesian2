<?php include('config.php'); ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Cartesian</title>


    <!----------STYLES---------->
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="<?= INCLUDE_PATH ?>css/jquery-ui.css" rel="stylesheet">
    <link href="<?= INCLUDE_PATH ?>css/all.min.css" rel="stylesheet">
    <link href="<?= INCLUDE_PATH ?>css/style.css" rel="stylesheet">
    <link rel="shortcut icon" type="image-x/png" href="file/logo/logo-icon.png">
    <!------------------------------>


    <!----------SETTINGS---------->
    <meta http-equiv="X-UA-Compatible" content="IE-Edge">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"> 
    <!------------------------------>


    <!----------SCRIPTS---------->
    <script src="<?= INCLUDE_PATH ?>js/jquery.js"></script>
    <script src="<?= INCLUDE_PATH ?>js/jquery-ui.js"></script>
    <script src="<?= INCLUDE_PATH ?>js/mask.js"></script>
    <!------------------------------>
  </head>

  <body>
	  <?php 
  	  if(!defined('LOGGED') || LOGGED == false){
  		  include('page/login.php');
  	  }
  	  else{
  		  include('page/main.php');
  	  }
	  ?>

    <!----------SCRIPTS---------->
    <script src="<?= INCLUDE_PATH ?>js/function.js"></script>
    <script src="<?= INCLUDE_PATH ?>js/main.js"></script>
    <script src="<?= INCLUDE_PATH ?>js/form.js"></script>
    <script src="<?= INCLUDE_PATH ?>js/search.js"></script>
    <!------------------------------>
  </body>
</html>