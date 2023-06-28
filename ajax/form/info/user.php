<?php
  include('../../../config.php');

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
  
  $data['result'] = '';
  $data['form'] = '';
  $name_post = 'user';

  if(isset($p['id'])){
    $result = false;

    if(defined('LOGGED_ID2')){
      if($p['id'] != 1){
        if($permission == 1 || LOGGED_ID2 == $p['id']){
          $result = true;
        }
      }
      else if(LOGGED_ID2 == 1){
        $result = true;
      }
    }

    if($result){
      $value = Db::selectId('system.users', $p['id']);
      if($value != false){
        $data['form'] = 'info-'.$value['id'];

        $data['result'] .= '<aside class="container info-'.$value['id'].'" style="display:  none;"><div class="outline">
          <div class="box">
            <button class="close-form" title="Fechar"><i class="fas fa-times"></i></button>
            <h2>'.$value['name'].'</h2>

            <div class="content">';

        $data['result'] .= '<p><b>Nome:</b> '.$value['name'].'</p>';

        $data['result'] .= '<p><b>Usuário:</b> '.$value['user'].'</p>';
        
        $data['result'] .= '<p><b>Cargo:</b> '.User::$office[$value['office']].'</p>';

        $getPermission = 1;
        $variable = '';
        for($i = 0; $i < count(Variable::$permissions); $i++){ $variable .= $variable == '' ? '1' : '||1'; }
        $getPermission = $value['permission'] == $variable ? 0 : $getPermission;
        $variable = '';
        for($i = 0; $i < count(Variable::$permissions); $i++){ $variable .= $variable == '' ? '0' : '||0'; }
        $getPermission = $value['permission'] == $variable ? 2 : $getPermission;
      
        $data['result'] .= '<p><b>Permissões:</b> '.Variable::$generalPermission[$getPermission].'</p>';

        if($getPermission == 1){
          $getPermission = [];
          $getPermission = explode('||', $value['permission']);

          $data['result'] .= '<div class="parcial-permissions2">';

          $data['result'] .= $getPermission[0] == 1 ? '<p><i class="fas fa-folder-open"></i> Arquivos</p>' : '';
          $data['result'] .= $getPermission[1] == 1 ? '<p><i class="fas fa-users-cog"></i> Usuários</p>' : '';
          $data['result'] .= $getPermission[2] == 1 ? '<p><i class="fas fa-user-friends"></i> Contatos</p>' : '';
          $data['result'] .= $getPermission[3] == 1 ? '<p><i class="fas fa-comments-dollar"></i> Vendas</p>' : '';
          $data['result'] .= $getPermission[4] == 1 ? '<p><i class="fas fa-shopping-cart"></i> Compras</p>' : '';
          $data['result'] .= $getPermission[5] == 1 ? '<p><i class="far fa-file-alt"></i> Projetos</p>' : '';
          $data['result'] .= $getPermission[6] == 1 ? '<p><i class="fas fa-toolbox"></i> Operacional</p>' : '';
          $data['result'] .= $getPermission[9] == 1 ? '<p><i class="fas fa-calculator"></i> Orçamentos</p>' : '';
          $data['result'] .= $getPermission[7] == 1 ? '<p><i class="fas fa-cash-register"></i> Contas a Pagar</p>' : '';
          $data['result'] .= $getPermission[8] == 1 ? '<p><i class="far fa-comment-dots"></i> Recados</p>' : '';

          $data['result'] .= '</div><!--parcial-permissions-->';
        }

        $data['result'] .= '<div class="center"><button class="open-form" id="'.$value['id'].'" type_form="'.$name_post.'" title="Editar">Editar</button></div><!--center-->
            </div><!--content-->
          </div><!--box-->
        </div><!--outline--></aside><!--container-->';

        $data['success'] = true;
      }
    }
  }

  die(json_encode($data));
?>