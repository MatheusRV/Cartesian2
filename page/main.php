<?php
  /*----------CHECK CONFIG----------*/
  if(isset($auto_load) == null){
    include('config.php');
    header('Location: '.INCLUDE_PATH);
    die();
  }
  /*------------------------------*/


  /*----------LOGGOUT----------*/
  if(isset($_GET['loggout'])){
    User::loggout();
  }
  /*------------------------------*/


  /*----------VARIABLES----------*/
  $name = mb_strlen(LOGGED_NAME) <= 15 ? LOGGED_NAME : explode(' ', LOGGED_NAME)['0'];

  $page = isset($_GET['url']) ? $_GET['url'] : 'home';

  $_SESSION['verify_authentication_cartesian_key'] = password_hash('cartesian_'.date('Y-m-d').'_login', PASSWORD_DEFAULT, array('cost' => 9));

  $suboption = Variable::suboption($page);
  /*------------------------------*/
?>

<header>
  <i class="fas fa-bars btn-menu" title="Menu"></i>

  <a href="<?= INCLUDE_PATH ?>" class="logo" title="Home"></a><!--logo-->

  <div class="single"><a href="<?= INCLUDE_PATH ?>?loggout" title="Sair"><i class="fas fa-sign-out-alt"></i> <span>Sair</span></a></div><!--single-->
</header>

<nav>
  <div class="user">
    <i class="fas fa-times btn-menu" title="Fechar"></i>

    <h2><?= $name ?></h2>

    <h3><?= User::$office[LOGGED_OFFICE] ?></h3>
  </div><!--user-->

  <ul>
  	<li class="<?= $page == 'home' ? 'this-page' : '' ?>">
      <a href="<?= INCLUDE_PATH ?>"><i class="fas fa-home"></i> Home</a>
    </li>

    <?php if(User::getPermission('points') == 1){?>
      <li class="<?= $page == 'points' ? 'this-page' : '' ?>">
        <a href="<?= INCLUDE_PATH ?>"><i class="fas fa-crosshairs"></i> Pontos</a>
      </li>
    <?php } ?>

    <?php if(User::getPermission('system_users') == 1){?>
    <li class="<?= $page == 'system_users' ? 'this-page' : '' ?>">
      <a href="<?= INCLUDE_PATH ?>system_users"><i class="fas fa-users-cog"></i> Usuários</a>
    </li>
    <?php } ?>
  </ul>
</nav>

<main>
  <?php
    if(file_exists('page/'.$page.'.php')){
      include('page/'.$page.'.php');
    }
    else{
      header('Location: '.INCLUDE_PATH);
      die();
    }
  ?>
</main>