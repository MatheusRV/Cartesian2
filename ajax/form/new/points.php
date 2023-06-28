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

  $name_post = 'points';
  $data['form'] = 'new-'.$name_post;

  $data['result'] = '<aside class="container new-'.$name_post.' center" style="display: none"><div class="outline">
    <form method="post" name="new-form-'.$name_post.'" id="new-form-'.$name_post.'">
      <h2>Adicionar Usuário</h2>

      <p class="error"></p>

      <div class="flex">

        <div class="w100 padding">
          <label>Nome:<b class="required">*</b></label>
          <input type="text" name="name" placeholder="Ex: Ponto Teste" required autocomplete="off">
        </div><!--w100-->

        <div class="w50 padding">
          <label>X:<b class="required">*</b></label>
          <input class="money" type="text" name="x" placeholder="Ex: 10,00" required autocomplete="off">
        </div><!--w50-->

        <div class="w50 padding">
          <label>Y:<b class="required">*</b></label>
          <input class="money" type="text" name="y" placeholder="Ex: 10,00" required autocomplete="off">
        </div><!--w50-->

      </div><!--flex-->

      <div class="center"><input type="submit" name="new-'.$name_post.'" value="Adicionar"></div><!--center-->
      <button class="close-form" title="Fechar"><i class="fas fa-times"></i></button>
    </form>
  </div><!--outline--></aside><!--container-->';


  $data['success'] = true;

  die(json_encode($data));
?>