<?php
  include('../../config.php');

  $p = $_POST;
  $data['success'] = false;
  $permission = User::getPermission('cartesian');

  if(!isset($_SESSION['verify_authentication_cartesian_key']) || !password_verify('cartesian_'.date('Y-m-d').'_login', $_SESSION['verify_authentication_cartesian_key'])){
    die(json_encode($data));
  }
  else if(!$permission){
    die(json_encode($data));
  }

  $info = GetInfo::lastPoint($p);
  if(is_array($info)){
    if(Db::update('position.data', $info)){ $data['success'] = true; }
  }

  die(json_encode($data));
?>