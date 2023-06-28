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
        $data['form'] = 'edit-'.$value['id'];

        $data['result'] .= '<aside class="container edit-'.$value['id'].'" style="display: none;"><div class="outline">
          <form method="post" name="edit-form-'.$name_post.'" id="edit-form-'.$name_post.'-'.$value['id'].'">
            <h2>Editar</h2>

            <p class="error"></p>

            <label>Nome:<b class="required">*</b></label>
            <input type="text" name="name" required autocomplete="off" value="'.$value['name'].'">

            <hr>

            <label>Usuário:<b class="required">*</b></label>
            <input type="text" name="user" required autocomplete="off" value="'.$value['user'].'">

        <label>Alterar senha:</label>';
        if(LOGGED_ID2 == $value['id']){
          $data['result'] .= '<input style="margin-bottom: 0;" type="password" name="last_password" autocomplete="off" placeholder="Senha Atual">';
        }
        $data['result'] .= '<input type="password" name="password" autocomplete="off" placeholder="Nova Senha">
            <hr>';

        if($value['id'] != 1 && $permission == 1){
          $data['result'] .= '<label>Cargo:<b class="required">*</b></label>
              <select name="office">';

          foreach(User::$office as $key2 => $value2){
            $data['result'] .= $key2 == $value['office'] ? '<option class="error" value="'.$key2.'" selected>'.$value2.'</option>' : '<option value="'.$key2.'">'.$value2.'</option>' ;
          }
          $data['result'] .= '</select>

          <label>Permissão:<b class="required">*</b></label>
          <select name="permission" onchange="verifyPermission()">';

          $access = Db::selectId('system.users', LOGGED_ID2)['permission'];

          $getPermission0 = 1;
          $variable = '';
          for($i = 0; $i < count(Variable::$permissions); $i++){ $variable .= $variable == '' ? '1' : '||1'; }
          $getPermission0 = $access == $variable ? 0 : $getPermission0;

          $getPermission = 1;
          $variable = '';
          for($i = 0; $i < count(Variable::$permissions); $i++){ $variable .= $variable == '' ? '1' : '||1'; }
          $getPermission = $value['permission'] == $variable ? 0 : $getPermission;
          $variable = '';
          for($i = 0; $i < count(Variable::$permissions); $i++){ $variable .= $variable == '' ? '0' : '||0'; }
          $getPermission = $value['permission'] == $variable ? 2 : $getPermission;

          if($getPermission0 == 0){
            $data['result'] .= $getPermission == 0 ? '<option value="0" selected title="Tem acesso a todo o sistema.">Administrador Total</option>' : '<option value="0" title="Tem acesso a todo o sistema.">Administrador Total</option>';
          }

          $data['result'] .= $getPermission == 1 ? '<option value="1" selected title="Selecione quais páginas o usuário poderá gerenciar.">Administrador Parcial</option>' : '<option value="1" title="Selecione quais páginas o usuário poderá gerenciar.">Administrador Parcial</option>';

          $data['result'] .= $getPermission == 2 ? '<option value="2" selected title="O usuário poderá somente ver as informações das páginas.">Somente Vizualizar</option>' : '<option value="2" title="O usuário poderá somente ver as informações das páginas.">Somente Vizualizar</option>';
          $data['result'] .= '</select>';

          $data['result'] .= $getPermission == 1 ? '<div class="parcial-permissions flex" title="Selecionar quais páginas o usuário poderá gerenciar.">' : '<div style="display: none;" class="parcial-permissions flex" title="Selecionar quais páginas o usuário poderá gerenciar.">';

          $getPermission = [];
          $getPermission = explode('||', $value['permission']);

          $access = explode('||', $access);

          $data['result'] .= '<label class="w100">Selecionar Permissões:</label>';

          if($access[0] == 1){
            $data['result'] .= '<div class="w33 padding group-checkbox">';
            $data['result'] .= $getPermission[0] == 1 ? '<input type="checkbox" name="permission0" id="permission0'.$value['id'].'" checked>' : '<input type="checkbox" name="permission0" id="permission0'.$value['id'].'">';
            $data['result'] .= '<label for="permission0'.$value['id'].'"><i class="fas fa-home"></i> Home</label>
                </div><!--width-->';
          }
          
          if($access[1] == 1){
            $data['result'] .= '<div class="w33 padding group-checkbox">';
            $data['result'] .= $getPermission[1] == 1 ? '<input type="checkbox" name="permission1" id="permission1'.$value['id'].'" checked>' : '<input type="checkbox" name="permission1" id="permission1'.$value['id'].'">';
            $data['result'] .= '<label for="permission1'.$value['id'].'"><i class="fas fa-crosshairs"></i> Pontos</label>
                </div><!--width-->';
          }
          
          if($access[2] == 1){
            $data['result'] .= '<div class="w33 padding group-checkbox">';
            $data['result'] .= $getPermission[2] == 1 ? '<input type="checkbox" name="permission2" id="permission2'.$value['id'].'" checked>' : '<input type="checkbox" name="permission2" id="permission2'.$value['id'].'">';
            $data['result'] .= '<label for="permission2'.$value['id'].'"><i class="fas fa-users-cog"></i> Usuários</label>
                </div><!--width-->';
          }
          
          $data['result'] .= '</div><!--parcial-permissions-->';
        }

        $data['result'] .= '<div class="center"><input type="submit" name="edit-'.$name_post.'" value="Atualizar"></div><!--center-->
            <input type="hidden" name="id" value="'.$value['id'].'">
            <button class="close-form" title="Fechar"><i class="fas fa-times"></i></button>
          </form>
        </div><!--outline--></aside><!--container-->';

        $data['success'] = true;
      }
    }
  }

  die(json_encode($data));
?>