<?php
  /*----------VARIABLES----------*/
    $name_page = 'home';
    $name_table = 'home.data';
    $name_post = 'home';
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
    <h1><i class="far fa-comment-dots"></i> Recados</h1>

    <button class="open-form" id="note" title="Adicionar Contato">Adicionar</button>

    <div class="notes-content flex" page="<?= $pg ?>">
    </div><!--notes-content-->

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