<?php
  include('../../config.php');

  $p = $_POST;
  $data['success'] = false;
  $data['reload'] = false;
  $permission = User::getPermission('points');

  if(!isset($_SESSION['verify_authentication_cartesian_key']) || !password_verify('cartesian_'.date('Y-m-d').'_login', $_SESSION['verify_authentication_cartesian_key'])){
    $data['reload'] = true;
    die(json_encode($data));
  }

  $name_page = 'points';
  $data['result'] = '';

  if((is_numeric($p['pg']) && $p['pg'] > 0) || ($p['pg'] == 'deleted' && LOGGED_ID2 == 1)){
    $info = $p['pg'] == 'deleted' && LOGGED_ID2 == 1 ? Db::selectAllDeleted('points.data', 'name', 'ASC') : Db::selectLimited('points.data', (($p['pg'] - 1) * 50), 50, 'last_att', 'ASC');
  }
  else{
    die(json_encode($data));
  }
  
  foreach($info as $key => $value){
    $user_att = $value['user_last_att'] != 0 ? Db::selectId('system.users', $value['user_last_att'], true) : ['name' => '<i>Sem dados</i>'];
    $name_user_att = '';
    if(strlen($user_att['name']) > 15 && $value['user_last_att'] != 0){
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
    
    $data['result'] .= '<tr id="'.$value['id'].'">';

    if($permission == 1){
      if($p['pg'] == 'deleted' && LOGGED_ID2 == 1){
        $data['result'] .= '<td class="restore"><a href="'.INCLUDE_PATH.$name_page.'?pg='.$p['pg'].'&restore='.$value['id'].'" title="Restaurar" action="restore"><i class="fas fa-history"></i></a></td>';
      }
      else{
        $data['result'] .= '<td class="delete"><a href="'.INCLUDE_PATH.$name_page.'?pg='.$p['pg'].'&delete='.$value['id'].'" title="Excluir" action="delete"><i class="far fa-times-circle"></i></a></td>';
      }
      $data['result'] .= '<td class="n30">'.$value['name'].'</td>';
    }
    else{
      $data['result'] .= '<td class="w30">'.$value['name'].'</td>';
    }

    $data['result'] .= '<td class="w20">'.number_format($value['x'], 2, ',', '.').'%</td>';

    $data['result'] .= '<td class="w20">'.number_format($value['y'], 2, ',', '.').'%</td>';

    $data['result'] .= $value['user_last_att'] != 0 ? '<td class="w30">'.date('d/m/Y', strtotime($value['last_att'])).' às '.date('H:i', strtotime($value['last_att'])).' por '.$name_user_att.'</td>' : '<td class="w24">'.$name_user_att.'</td>';
    $data['result'] .= '</tr>';

    $data['success'] = true;
  }

  die(json_encode($data));
?>