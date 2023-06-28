<?php
  include('../../../config.php');

  $p = $_POST;
  $data['success'] = false;
  $data['reload'] = false;
  $permission = User::getPermission('points');

  if(!isset($_SESSION['verify_authentication_cartesian_key']) || !password_verify('cartesian_'.date('Y-m-d').'_login', $_SESSION['verify_authentication_cartesian_key'])){
    $data['reload'] = true;
    die(json_encode($data));
  }
  else if(!$permission){
    die(json_encode($data));
  }

  $data['notification'] = false;
  $data['result'] = '';
  $data['form'] = '';
  $name_post = 'points';

  if(isset($p['id'])){
    $value = Db::selectId('points.data', $p['id']);
    if($value != false){
      $data['form'] = 'info-'.$value['id'];

      $data['result'] .= '<aside class="container info-'.$value['id'].' center" style="display:  none;"><div class="outline">
        <div class="box">
          <button class="close-form" title="Fechar"><i class="fas fa-times"></i></button>
          <h2>'.$value['name'].'</h2>

          <div class="content">';

      $data['result'] .= '<p><b>X:</b> '.number_format($value['x'],2,',','.').'%</p>';
      $data['result'] .= '<p><b>Y:</b> '.number_format($value['y'],2,',','.').'%</p>';

      $data['result'] .= '<hr>';

      $user_att = $value['user_last_att'] != 0 ? Db::selectId('system.users', $value['user_last_att'], true) : ['name' => '<i>Sem dados</i>'];
      $name_user_att = '';
      if($value['user_last_att'] != 0 && strlen($user_att['name']) > 15){
        foreach(explode(' ', $user_att['name']) as $key2 => $name){
          if(strlen($name_user_att.' '.$name) > 15){
            break;
          }

          $name_user_att .=  $name_user_att == '' ? $name : ' '.$name;
        }
      }
      else{
        $name_user_att = $user_att['name'];
      }

      $data['result'] .= '<p><b>Última Atualização:</b> '.date('d/m/Y', strtotime($value['last_att'])).' às '.date('H:i', strtotime($value['last_att'])).' por '.$name_user_att.'</p>';

      $data['result'] .= '<hr><div class="center"><button class="open-form" id="'.$value['id'].'" type_form="'.$name_post.'" title="Editar">Editar</button></div><!--center-->
          </div><!--content-->
        </div><!--box-->
      </div><!--outline--></aside><!--container-->';

      $data['success'] = true;
    }
  }

  die(json_encode($data));
?>