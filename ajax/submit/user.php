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
  else if(!$permission && LOGGED_ID2 != $p['id']){
    die(json_encode($data));
  }

  if(isset($p['new-user']) && $permission == 1){
    $info = GetInfo::user($p); 

    if(is_array($info)){
      if(Db::insert('system.users', $info)){
        $data['success'] = true;
      }
    }
    else{
      $data['message'] = $info;
    }
  }

  if(isset($p['edit-user']) && ($permission == 1 || LOGGED_ID2 == $p['id'])){
    $info = GetInfo::user($p, true);

    if(is_array($info)){
      if(Db::update('system.users', $info)){
        if(isset($_SESSION['new_password']) && $_SESSION['new_password'] == true){
          unset($_SESSION['new_password']);
          $data['reload'] = true;
        }
        
        $data['success'] = true;
      }
    }
    else if(isset($p['id']) && is_numeric($p['id'])){
      $data['message'] = $info;
    }
  }

  die(json_encode($data));
?>