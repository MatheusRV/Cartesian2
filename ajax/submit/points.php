<?php
  include('../../config.php');

  $p = $_POST;
  $data['success'] = false;
  $data['reload'] = false;
  $permission = User::getPermission('system_users');

  if(!isset($_SESSION['verify_authentication_cartesian_key']) || !password_verify('cartesian_'.date('Y-m-d').'_login', $_SESSION['verify_authentication_cartesian_key'])){
    $data['reload'] = true;
    die(json_encode($data));
  }
  else if(!$permission){
    die(json_encode($data));
  }

  if(isset($p['new-points']) && $permission == 1){
    $info = GetInfo::points($p); 

    if(is_array($info)){
      if(Db::insert('points.data', $info)){
        $data['success'] = true;
      }
    }
    else{
      $data['message'] = $info;
    }
  }

  if(isset($p['edit-points']) && $permission == 1){
    $info = GetInfo::points($p, true);

    if(is_array($info)){
      if(Db::update('points.data', $info)){
        $data['success'] = true;
      }
    }
    else if(isset($p['id']) && is_numeric($p['id'])){
      $data['message'] = $info;
    }
  }

  die(json_encode($data));
?>