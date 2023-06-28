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

  $data['result'] = '';
  $data['form'] = '';
  $name_post = 'points';

  if(isset($p['id'])){
    $value = Db::selectId('points.data', $p['id']);
    if($value != false){
      $data['form'] = 'edit-'.$value['id'];

      $data['result'] .= '<aside class="container edit-'.$value['id'].' center" style="display: none;"><div class="outline">
        <form method="post" name="edit-form-'.$name_post.'" id="edit-form-'.$name_post.'-'.$value['id'].'">
          <h2>Adicionar Usuário</h2>

          <p class="error"></p>

          <div class="flex">

            <div class="w100 padding">
              <label>Nome:<b class="required">*</b></label>
              <input type="text" name="name" placeholder="Ex: Ponto Teste" value="'.$value['name'].'" required autocomplete="off">
            </div><!--w100-->

            <div class="w50 padding">
              <label>X:<b class="required">*</b></label>
              <input class="money" type="text" name="x" placeholder="Ex: 10,00" value="'.number_format($value['x'],2,',','.').'" required autocomplete="off">
            </div><!--w50-->

            <div class="w50 padding">
              <label>Y:<b class="required">*</b></label>
              <input class="money" type="text" name="y" placeholder="Ex: 10,00" value="'.number_format($value['y'],2,',','.').'" required autocomplete="off">
            </div><!--w50-->

          </div><!--flex-->
          
          <hr>

          <div class="center"><input type="submit" name="edit-'.$name_post.'" value="Atualizar"></div><!--center-->
          <input type="hidden" name="id" value="'.$value['id'].'">
          <button class="close-form" title="Fechar"><i class="fas fa-times"></i></button>
        </form>
      </div><!--outline--></aside><!--container-->';

      $data['success'] = true;
    }
  }

  die(json_encode($data));
?>