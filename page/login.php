<?php
  /*----------VARIABLES----------*/
  $alert = 0;
  /*------------------------------*/


  /*----------LOGIN----------*/
  if(isset($_POST['login']) && isset($_POST['user']) && isset($_POST['password'])){
    if(User::login($_POST) == false){
      $alert = 1;
    }
  }
  /*------------------------------*/
?>

<section class="login" style="background-image: url('file/bg_login/<?= $picture ?>.png');">
  <div class="overlay1"></div>

  <div class="single">
    <div class="center">
      <img id="logo" src="file/logo/cartesian-logo.svg">
    </div>

    <form method="post">
      <?php if($alert == 1){ ?>
        <p class="login-error">Usuário e/ou Senha incorretos.</p>
      <?php } ?>
      
      <label>Usuário:</label>
      <input class="w100" type="text" name="user" required value="<?= $alert == 1 ? $_POST['user'] : '' ?>">

      <label>Senha:</label>
      <input class="w100" type="password" name="password" required value="<?= $alert == 1 ? $_POST['password'] : '' ?>">

      <div class="center">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember" class="checkbox">Lembrar-me</label>
      </div><!--center-->

      <div class="center"><input type="submit" name="login" value="Entrar"></div><!--center-->
    </form>
  </div><!--single-->
</section><!--login-->