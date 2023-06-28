<?php
  class GetInfo{
    public static function user($p, $edit = false){
      $key = 'user';
      if(isset($p[$key]) && $p[$key] != ''){
        $sql = MySql::connect()->prepare("SELECT * FROM `system.users` WHERE user = ?");
        $sql->execute([$p[$key]]);

        if($sql->rowCount() == 0){
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if($sql->rowCount() == 1){
          $sql = $sql->fetch();

          if($edit && $p['id'] == $sql['id']){
            $info[$key] = $p[$key];
          }
          else{
            return 'Este nome de usuário já existe.';
          }
        }
        else{
          return  'Este nome de usuário já existe.';
        }
      }
      else{
        return 'Digite um nome de usuário.';
      }

      $key = 'password';
      if(isset($p[$key]) && $p[$key] != '' && strlen($p[$key]) >= 8){
        if($edit){
          if(LOGGED_ID2 == $p['id']){
            if(password_verify($p['last_password'], LOGGED_PASSWORD)){
              $info[$key] = password_hash($p[$key], PASSWORD_DEFAULT, array('cost' => 9));
              $_SESSION['new_password'] = true;
            }
            else{
              return 'A senha atual digitada está incorreta.';
            }
          }
          else{
            $info[$key] = password_hash($p[$key], PASSWORD_DEFAULT, array('cost' => 9));
          }
        }
        else{
          $info[] = password_hash($p[$key], PASSWORD_DEFAULT, array('cost' => 9));
        }
      }
      else if(!$edit){
        return 'Digite uma senha como pelo menos 8 dígitos.';
      }

      $key = 'name';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um nome.';
      }

      $permission = User::getPermission('system_users');
      $key = 'office';
      if(isset($p[$key]) && is_numeric($p[$key]) && isset(User::$office[$p[$key]]) && $permission == 1){
        if($edit){ if($p['id'] != 1){ $info[$key] = $p[$key]; } }
        else{ $info[] = $p[$key]; }
      }
      else if($p['id'] != 1 && $permission == 1){
        return 'Cargo inválido.';
      }

      $access = Db::selectId('system.users', LOGGED_ID2)['permission'];
      $getPermission = 1;
      $variable = '';
      for($i = 0; $i < count(Variable::$permissions); $i++){ $variable .= $variable == '' ? '1' : '||1'; }
      $getPermission = $access == $variable ? 0 : $getPermission;
      $access = explode('||', $access);

      $key = 'permission';
      if(isset($p[$key]) && is_numeric($p[$key]) && $permission == 1){
        $permission = '';
        for($i = 0; $i < count(Variable::$permissions); $i++){
          if($getPermission == 0 && $p[$key] == 0){
            $permission .= $permission == '' ? '1' : '||1';
          }
          else if($p[$key] == 2){
            $permission .= $permission == '' ? '0' : '||0';
          }
          else{
            $parcial = isset($p['permission'.$i]) && isset($access[$i]) && $access[$i] == 1 ? 1 : 0;
            $permission .= $permission == '' ? $parcial : '||'.$parcial;
          }
        }

        if($edit){ if($p['id'] != 1){ $info[$key] = $permission; } }
        else{ $info[] = $permission; }
      }
      else if($p['id'] != 1 && $permission == 1){
        return 'Permissão inválida.';
      }

      if($edit){
        $info['last_att'] = date('Y-m-d H:i:s');
        $info['user_last_att'] = LOGGED_ID2;
        $info['id'] = $p['id']; 
      }
      else{
        $info[] = date('Y-m-d H:i:s');
        $info[] = LOGGED_ID2;
        $info[] = 0; 
      }

      return $info;
    }

    public static function lastPoint($p){
      $info = [];
      $info['id_user'] = LOGGED_ID2;

      $key = 'x';
      if(isset($p[$key]) && is_numeric($p[$key])){ $info[$key] = $p[$key]; }
      else return 'X não é válido';

      $key = 'y';
      if(isset($p[$key]) && is_numeric($p[$key])){ $info[$key] = $p[$key]; }
      else return 'Y não é válido';

      $info['data'] = date('Y-m-d H:i:s');
      $info['id'] = 1;

      return $info;
    }

    public static function points($p, $edit = false){
      $info = [];

      $key = 'id_user';
      if($edit){ $info[$key] = LOGGED_ID2; }
      else{ $info[] = LOGGED_ID2; }

      $key = 'name';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else return 'Digite um nome para o ponto';

      $key = 'x';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');
        
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else return 'Digite um valor válido para X.';

      $key = 'y';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');
        
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else return 'Digite um valor válido para Y.';

      if($edit){
        $info['last_att'] = date('Y-m-d H:i:s');
        $info['user_last_att'] = LOGGED_ID2;
        $info['id'] = $p['id']; 
      }
      else{
        $info[] = date('Y-m-d H:i:s');
        $info[] = LOGGED_ID2;
        $info[] = 0; 
      }

      return $info;
    }
  }
?>