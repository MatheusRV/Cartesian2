<?php
  class User{
    public static $office = [
      '0' => 'Administrador',
      '10' => 'Engenheiro',
      '20' => 'Funcionário',
      '30' => 'Estagiário'
    ];

    public static function logged(){
      if(isset($_SESSION)){
        $token = isset($_SESSION['token']) ? $_SESSION['token'] : 0;

        $info = MySql::connect()->prepare("SELECT * FROM `system.logged` WHERE token = ?");
        $info->execute([$token]);

        if($info->rowCount() == 1){
          $info = $info->fetch();

          if($info['remember'] == 1 && strtotime($info['last_action'].' + 24 hours') > strtotime(date('Y-m-d H:i:s'))){
            $info2 = MySql::connect()->prepare("SELECT * FROM `system.users` WHERE user = ? AND password = ?");
            $info2->execute([$info['user'],$info['password']]);

            if($info2->rowCount() == 1){
              $info2 = $info2->fetch();

              if($info['user'] == $info2['user'] && $info['password'] == $info2['password']){
                  define('LOGGED', true);
                  define('LOGGED_ID', $info['id']);
                  define('LOGGED_ID2', $info2['id']);
                  define('LOGGED_USER', $info2['user']);
                  define('LOGGED_PASSWORD', $info2['password']);
                  define('LOGGED_NAME', $info2['name']);
                  define('LOGGED_OFFICE', $info2['office']);
                  define('LOGGED_PERMISSION', $info2['permission']);

                  $dateTime = date('Y-m-d H:i:s');

                  $sql = MySql::connect()->prepare("UPDATE `system.logged` SET last_action = ? WHERE id = ?");
                  $sql->execute([$dateTime, $info['id']]);

                  unset($_SESSION['token']);
                  $session = [];
                  $session = $_SESSION;
                  session_destroy();
                  session_cache_expire(60*24);
                  session_start();
                  $_SESSION['token'] = $token;
                  foreach($session as $key => $value){
                    if($key != 'token'){
                      $_SESSION[$key] = $value;
                    }
                  }
              }
              else{
                $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE user = ?");
                $sql->execute([$info['user']]);

                $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE token = ?");
                $sql->execute([$token]);

                session_unset();
                session_destroy();

                define('LOGGED', false);
              }
            }
            else{
              $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE user = ?");
              $sql->execute([$info['user']]);

              $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE token = ?");
              $sql->execute([$token]);

              session_unset();
              session_destroy();

              define('LOGGED', false);
            }
          }
          else if($info['remember'] == 0 && strtotime($info['last_action'].' + 1 hours') > strtotime(date('Y-m-d H:i:s'))){
            $info2 = MySql::connect()->prepare("SELECT * FROM `system.users` WHERE user = ? AND password = ?");
            $info2->execute([$info['user'],$info['password']]);

            if($info2->rowCount() == 1){
              $info2 = $info2->fetch();

              if($info['user'] == $info2['user'] && $info['password'] == $info2['password']){
                  define('LOGGED', true);
                  define('LOGGED_ID', $info['id']);
                  define('LOGGED_ID2', $info2['id']);
                  define('LOGGED_USER', $info2['user']);
                  define('LOGGED_PASSWORD', $info2['password']);
                  define('LOGGED_NAME', $info2['name']);
                  define('LOGGED_OFFICE', $info2['office']);
                  define('LOGGED_PERMISSION', $info2['permission']);

                  $dateTime = date('Y-m-d H:i:s');

                  $sql = MySql::connect()->prepare("UPDATE `system.logged` SET last_action = ? WHERE id = ?");
                  $sql->execute([$dateTime, $info['id']]);

                  unset($_SESSION['token']);
                  $session = [];
                  $session = $_SESSION;
                  session_destroy();
                  session_cache_expire(60);
                  session_start();
                  $_SESSION['token'] = $token;
                  foreach($session as $key => $value){
                    if($key != 'token'){
                      $_SESSION[$key] = $value;
                    }
                  }
              }
              else{
                $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE user = ?");
                $sql->execute([$info['user']]);

                $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE token = ?");
                $sql->execute([$token]);

                session_unset();
                session_destroy();

                define('LOGGED', false);
              }
            }
            else{
              $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE user = ?");
              $sql->execute([$info['user']]);

              $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE token = ?");
              $sql->execute([$token]);

              session_unset();
              session_destroy();

              define('LOGGED', false);
            }
          }
          else{
            $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE user = ?");
            $sql->execute([$info['user']]);

            $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE token = ?");
            $sql->execute([$token]);

            session_unset();
            session_destroy();

            define('LOGGED', false);
          }
        }
        else if($info->rowCount() > 1){
          $info = $info->fetchAll();

          foreach($info as $key => $value){
            $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE user = ?");
            $sql->execute([$value['user']]);
          }

          $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE token = ?");
          $sql->execute([$token]);

          session_unset();
          session_destroy();

          define('LOGGED', false);
        }
        else{
          $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE token = ?");
          $sql->execute([$token]);

          session_unset();
          session_destroy();

          define('LOGGED', false);
        }
      }

      $dateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' - 1 hours'));

      $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE last_action < ? and remember = ?");
      $sql->execute([$dateTime, '0']);

      $dateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' - 24 hours'));

      $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE last_action < ? and remember = ?");
      $sql->execute([$dateTime, '1']);
    }

    public static function login($post){
      $info = MySql::connect()->prepare("SELECT * FROM `system.users` WHERE user = ?");
      $info->execute([$post['user']]);

      if($info->rowCount() == 1){
        $info = $info->fetch();
        
        if($info['user'] == $post['user'] && password_verify($post['password'], $info['password']) == 1){
          if(isset($post['remember'])){
            unset($_SESSION['token']);
            $session = [];
            $session = $_SESSION;
            session_destroy();
            session_cache_expire(60*24);
            session_start();
            foreach($session as $key => $value){
              if($key != 'token'){
                $_SESSION[$key] = $value;
              }
            }
          }
          else{
            unset($_SESSION['token']);
            $session = [];
            $session = $_SESSION;
            session_unset();
            session_destroy();
            session_cache_expire(60);
            session_start();
            foreach($session as $key => $value){
              if($key != 'token'){
                $_SESSION[$key] = $value;
              }
            }
          }

          $token = uniqid();
          $_SESSION['token'] = $token;

          $insert[] = $token;
          $insert[] = $info['user'];
          $insert[] = $info['password'];
          $insert[] = date('Y-m-d H:i:s');
          $insert[] = isset($post['remember']) ? '1' : '0';

          $sql = MySql::connect()->prepare("SELECT * FROM `system.logged`");
          $sql->execute();
          if($sql->rowCount() == 0){
            $sql = MySql::connect()->prepare("TRUNCATE TABLE `system.logged`");
            $sql->execute();
          }

          $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE user = ?");
          $sql->execute([$info['user']]);

          $sql = MySql::connect()->prepare("INSERT INTO `system.logged` VALUES (null,?,?,?,?,?)");
          $sql->execute($insert);

          $url = isset($_GET['url']) ? $_GET['url'] : '';
          $first = 0;

          foreach ($_GET as $key => $value) {
            if($key != 'url' && $first == 0){
              $url .= '?'.$key.'='.$value;
              $first ++;
            }
            else if($key != 'url'){
              $url .= '&'.$key.'='.$value;
            }
          }

          header('Location: '.INCLUDE_PATH.$url);
          die();

          return true;
        }
        else{
          return false;
        }
      }
      else{
        return false;
      }
    }

    public static function loggout(){
      $sql = MySql::connect()->prepare("DELETE FROM `system.logged` WHERE user = ?");
      $sql->execute([LOGGED_USER]);

      header('Location: '.INCLUDE_PATH);
      die();
    }

    public static function getPermission($page){
      if(defined('LOGGED_ID2')){
        $user = Db::selectId('system.users', LOGGED_ID2);
      }
      else return 0;

      if($page == 'cartesian') return explode('||', $user['permission'])[0];
      else if($page == 'points') return explode('||', $user['permission'])[1];
      else if($page == 'system_users') return explode('||', $user['permission'])[2];

      return 0;
    }
  }
?>