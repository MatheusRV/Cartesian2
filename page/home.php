<?php
  /*----------VARIABLES----------*/
    $name_page = 'cartesian';
    $name_table = 'cartesian.data';
    $name_post = 'cartesian';

    $startP = MySql::connect()->prepare("SELECT * FROM `position.data` WHERE id = 1");
    if($startP->execute() && $startP->rowCount() == 1){
      $startP = $startP->fetch();
    }
    else{
      $startP = ['x' => 0, 'y' => 0];
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
    <h1 style="margin-bottom: 0;"><i class="fas fa-home"></i> Bem Vindo(a), <?= $name ?>!</h1>
  </div><!--inside-->
</section><!--outside-->

<section class="outside w100 home">
  <div class="inside">
    <div class="w70 map">
      <span id="point" style="bottom: <?=$startP['y']?>%; left: <?=$startP['x']?>%"data-x="<?=$startP['x']?>%" data-y="<?=$startP['y']?>%"><i class="fas fa-crosshairs"></i></span>
    </div>
    <div class="w30 controls flex">
      <div class="buttons">
        <div class="reset"><img src="file/svg/arrow-rotate-left-solid.svg"></div>
        <div class="toggle"><span></span></div>
        <?php if(User::getPermission('points') == 1){?><div class="add-point" title="Registrar ponto"><img src="file/svg/plus-solid.svg"></div><?php } ?>
      </div>
      <div class="velocity">
        <span class="vel" id="cent">0.01</span>
        <span class="vel" id="dec">0.1</span>
        <span class="vel active" id="un">1</span>
        <span class="vel" id="five">5</span>
        <span class="vel" id="ten">10</span>
      </div>
      <div class="commands">
        <span class="bt" id="empty"></span>
        <span class="bt" id="up"><i class="fa fa-arrow-up"></i></span>
        <span class="bt" id="empty"></span>
        <span class="bt" id="left"><i class="fa fa-arrow-left"></i></span>
        <span class="bt" id="down"><i class="fa fa-arrow-down"></i></span>
        <span class="bt" id="right"><i class="fa fa-arrow-right"></i></span>
      </div>
    </div>

    <script src="https://cdn.tiny.cloud/1/zpjiw4hoos1kl80km8nw1gvz415gzk86b8wv7tpjjeviclgq/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
      tinymce.init({
        selector: '.text-edit',
        plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
      });
    </script>
  </div><!--inside-->
</section><!--outside-->