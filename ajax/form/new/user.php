<?php
  include('../../../config.php');

  $p = $_POST;
  $data['success'] = false;
  $data['reload'] = false;
  $permission = User::getPermission('system_users');

  if(!isset($_SESSION['verify_authentication_indusol_key']) || !password_verify('indusol_'.date('Y-m-d').'_show_demais', $_SESSION['verify_authentication_indusol_key'])){
    $data['reload'] = true;
    die(json_encode($data));
  }
  else if(!$permission){
    die(json_encode($data));
  }

  $name_post = 'user';
  $data['form'] = 'new-'.$name_post;

  $data['result'] = '<aside class="container new-'.$name_post.'" style="display: none"><div class="outline">
    <form method="post" name="new-form-'.$name_post.'" id="new-form-'.$name_post.'">
      <h2>Adicionar Usuário</h2>

      <p class="error"></p>

      <label>Nome:<b class="required">*</b></label>
      <input type="text" name="name" required autocomplete="off">

      <hr>

      <label>Usuário:<b class="required">*</b></label>
      <input type="text" name="user" required autocomplete="off">

      <label>Senha:<b class="required">*</b></label>
      <input type="password" name="password" required autocomplete="off">

      <hr>

      <label>Cargo:<b class="required">*</b></label>
      <select name="office">';
  foreach(User::$office as $key => $value){
    $data['result'] .= '<option value="'.$key.'">'.$value.'</option>';
  }

  $access = Db::selectId('system.users', LOGGED_ID2)['permission'];
  $getPermission = 1;
  $variable = '';
  for($i = 0; $i < count(Variable::$permissions); $i++){ $variable .= $variable == '' ? '1' : '||1'; }
  $getPermission = $access == $variable ? 0 : $getPermission;
  $access = explode('||', $access);

  $data['result'] .= '</select>

      <label>Permissão:<b class="required">*</b></label>
      <select name="permission" onchange="verifyPermission()">';

  $data['result'] .= $getPermission == 0 ? '<option value="0" title="Tem acesso a todo o sistema.">Administrador Total</option>' : '';

  $data['result'] .= '<option value="1" title="Selecione quais páginas o usuário poderá gerenciar.">Administrador Parcial</option>
        <option value="2" title="O usuário poderá somente ver as informações das páginas.">Somente Vizualizar</option>
      </select>';

  $data['result'] .= $getPermission == 1 ? '<div class="parcial-permissions flex" title="Selecionar quais páginas o usuário poderá gerenciar.">' : '<div style="display: none;" class="parcial-permissions flex" title="Selecionar quais páginas o usuário poderá gerenciar.">';

  $data['result'] .= '<label class="w100">Selecionar Permissões:</label>';
  
  $data['result'] .= $access[0] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission0" id="permission0">
          <label for="permission0"><i class="fas fa-folder-open"></i> Arquivos</label>
        </div><!--width-->' : '';

  $data['result'] .= $access[1] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission1" id="permission1">
          <label for="permission1"><i class="fas fa-users-cog"></i> Usuários</label>
        </div><!--width-->' : '';

  $data['result'] .= $access[2] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission2" id="permission2">
          <label for="permission2"><i class="fas fa-user-friends"></i> Contatos</label>
        </div><!--width-->' : '';

  $data['result'] .= $access[3] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission3" id="permission3">
          <label for="permission3"><i class="fas fa-comments-dollar"></i> Vendas</label>
        </div><!--width-->' : '';

  $data['result'] .= $access[4] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission4" id="permission4">
          <label for="permission4"><i class="fas fa-shopping-cart"></i> Compras</label>
        </div><!--width-->' : '';

  $data['result'] .= $access[5] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission5" id="permission5">
          <label for="permission5"><i class="far fa-file-alt"></i> Projetos</label>
        </div><!--width-->' : '';

  $data['result'] .= $access[6] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission6" id="permission6">
          <label for="permission6"><i class="fas fa-toolbox"></i> Operacional</label>
        </div><!--width-->' : '';

  $data['result'] .= $access[7] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission7" id="permission7">
          <label for="permission7"><i class="fas fa-cash-register"></i> Contas a Pagar</label>
        </div><!--width-->' : '';

  $data['result'] .= $access[8] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission8" id="permission8">
          <label for="permission8"><i class="far fa-comment-dots"></i> Recados</label>
        </div><!--width-->' : '';

  $data['result'] .= $access[9] == 1 ? '<div class="w33 padding group-checkbox">
          <input type="checkbox" name="permission9" id="permission9">
          <label for="permission9"><i class="fas fa-calculator"></i> Orçamentos</label>
        </div><!--width-->' : '';

  $data['result'] .= '</div><!--parcial-permissions-->

      <div class="center"><input type="submit" name="new-'.$name_post.'" value="Adicionar"></div><!--center-->
      <button class="close-form" title="Fechar"><i class="fas fa-times"></i></button>
    </form>
  </div><!--outline--></aside><!--container-->';


  $data['success'] = true;

  die(json_encode($data));
?>