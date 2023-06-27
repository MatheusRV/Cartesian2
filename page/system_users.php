<?php
  /*----------VARIABLES----------*/
    $name_page = 'system_users';
    $name_table = 'system.users';
    $name_post = 'user';
    $permission = User::getPermission($name_page);
  /*------------------------------*/


  /*----------PAGES----------*/
    $qtdPg = Variable::qtdPg($name_table);
    $pg = Variable::pg($qtdPg);
    if($pg == 'location'){
      header('Location: '.INCLUDE_PATH.$name_page);
      die();
    }
  /*------------------------------*/


  /*----------FORMS----------*/
    if(isset($_GET['delete']) && $_GET['delete'] > 1 && $permission == 1){
      $info = [];
      $info['last_att'] = date('Y-m-d H:i:s');
      $info['user_last_att'] = LOGGED_ID2;
      $info['deleted'] = 1;
      $info['id'] = $_GET['delete'];

      if(Db::update($name_table, $info)){
        $_SESSION['delete'] = true;

        header('Location: '.INCLUDE_PATH.$name_page.'?pg='.$pg);
        die();
      }
    }

    if(isset($_GET['restore']) && $_GET['restore'] > 0 && $permission == 1){
      $info = [];
      $info['last_att'] = date('Y-m-d H:i:s');
      $info['user_last_att'] = LOGGED_ID2;
      $info['deleted'] = 0;
      $info['id'] = $_GET['restore'];

      if(Db::update($name_table, $info)){
        $_SESSION['restore'] = true;

        header('Location: '.INCLUDE_PATH.$name_page.'?pg='.$pg);
        die();
      }
    }
  /*------------------------------*/


  /*----------POPUP----------*/
    Variable::popup();
  /*------------------------------*/
?>

<article class="message-load">
  <p>Carregando...</p>
</article><!--message-load-->

<section class="form"></section>

<section class="outside w100">
  <div class="inside">
    <h1><i class="fas fa-users-cog"></i> Usuários</h1>
    
    <?php if($permission == 1){ ?>
    <button class="open-form" id="<?= $name_post ?>" title="Adicionar Usuário">Adicionar</button>
    <?php } ?>

    <div class="overflow">
      <table class="<?= $name_page ?>" page="<?= $pg ?>">
        <thead>
          <tr class="w100">
            <?php if($permission == 1){ ?>
              <th class="delete"></th>
              <th class="n30">Nome</th>
            <?php }else{ ?>
              <th class="w30">Nome</th>
            <?php } ?>
            <th class="w20">Cargo</th>
            <th class="w20">Permissão</th>
            <th class="w30"><?= $pg == 'deleted' ? 'Deletado em' : 'Última Atualização' ?></th>
          </tr>
        </thead>

        <tbody class="<?= $name_post ?>"></tbody>
      </table>
    </div><!--overflow-->

    <script>
      function verifyPermission(){
        for(var i = 0; i < $('[name=permission]').length; i++){
          if($('[name=permission]').eq(i).find('option:selected').attr('value') == 1){
            $('.parcial-permissions').eq(i).slideDown(100);
          }
          else{
            $('.parcial-permissions').eq(i).slideUp(100);
          }
        }
      }
    </script>

    <?= Variable::printButtonPages($pg, $qtdPg, $name_page); ?>
  </div><!--inside-->
</section><!--outside-->