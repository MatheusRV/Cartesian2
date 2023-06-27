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

    public static function contact($p, $edit = false){
      if($edit){
        $value = Db::selectId('contacts.data', $p['id']);
      }

      $key = 'type';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Tipo inválido.';
      }

      $key = 'relation';
      if(isset($p[$key]) && is_numeric($p[$key]) && isset(Variable::$relation[$p[$key]])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Relação inválida.';
      }

      $key = 'name';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ $_SESSION['notifications'] .= $_SESSION['notifications'] == '' ? '"Nome"' : ', "Nome"'; }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um nome.';
      }

      $key = 'cpf';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ $_SESSION['notifications'] .= $_SESSION['notifications'] == '' ? '"CPF/CNPJ"' : ', "CPF/CNPJ"'; }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = ''; }
        else{ $info[] = ''; }
      }

      $key = 'agent';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ $_SESSION['notifications'] .= $_SESSION['notifications'] == '' ? '"Representante Legal"' : ', "Representante Legal"'; }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'cpf2';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ $_SESSION['notifications'] .= $_SESSION['notifications'] == '' ? '"CPF Representante"' : ', "CPF  Representante"'; }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = ''; }
        else{ $info[] = ''; }
      }

      $key = 'rg';
      if(isset($p[$key]) && $p[$key] != '' && $p['relation'] == 20){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = ''; }
        else{ $info[] = ''; }
      }

      $key = 'birtday';
      if(isset($p[$key]) && $p[$key] != '' && $p['relation'] == 20){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = '0000-00-00'; }
        else{ $info[] = '0000-00-00'; }
      }

      $key = 'address';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ $_SESSION['notifications'] .= $_SESSION['notifications'] == '' ? '"Endereço"' : ', "Endereço"'; }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = ''; }
        else{ $info[] = ''; }
      }

      $key = 'fone';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ $_SESSION['notifications'] .= $_SESSION['notifications'] == '' ? '"Celular"' : ', "Celular"'; }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = ''; }
        else{ $info[] = ''; }
      }

      $key = 'fone2';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ $_SESSION['notifications'] .= $_SESSION['notifications'] == '' ? '"Telefone"' : ', "Telefone"'; }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = ''; }
        else{ $info[] = ''; }
      }

      $key = 'email';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ $_SESSION['notifications'] .= $_SESSION['notifications'] == '' ? '"E-mail"' : ', "E-mail"'; }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = ''; }
        else{ $info[] = ''; }
      }

      $key = 'data_payment';
      if(isset($p[$key]) && $p[$key] != '' && $p['relation'] == 10){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = ''; }
        else{ $info[] = ''; }
      }

      $key = 'comments';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = ''; }
        else{ $info[] = ''; }
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
        $info[] = 0; 
      }

      return $info;
    }

    public static function sale($p, $edit = false){
      if($edit){ $value = Db::selectId('sales.data', $p['id']); }

      $key = 'n_ov';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Nº OV"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite o Nº OV.';
      }

      $key = 'date';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Data da Venda"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite a data da venda.';
      }

      $key = 'id_client';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('contacts.data', $p[$key]) != false){
          if($edit && $value[$key] != $p[$key]){ 
            $text = '"Cliente"';
            $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
          }

          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Escolha um cliente válido.';
        }
      }
      else{
        return 'Escolha um cliente.';
      }

      $key = 'id_provider';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('contacts.data', $p[$key]) != false){
          if($edit && $value[$key] != $p[$key]){ 
            $text = '"Fornecedor"';
            $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
          }

          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if($p[$key] == 0){
          if($edit && $value[$key] != 0){ 
            $text = '"Fornecedor"';
            $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
          }

          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Escolha um fornecedor válido.';
        }
      }
      else if($edit){ 
        if($edit && $value[$key] != 0){ 
          $text = '"Fornecedor"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        $info[$key] = 0; 
      }
      else{ $info[] = 0; }

      $key = 'module_power';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Potência do Módulo"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ 
        if($edit && $value[$key] != 0){ 
          $text = '"Potência do Módulo"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        $info[$key] = 0; 
      }
      else{ $info[] = 0; }

      $key = 'module_qtd';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Qtd. de Módulos"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ 
        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Qtd. de Módulos"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        $info[$key] = 0; 
      }
      else{ $info[] = 0; }

      /*$key = 'module_maker';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ 
          //$text = '"Qtd. de Módulos"';
          //$_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ 
        if($edit && $value[$key] != $p[$key]){ 
          //$text = '"Qtd. de Módulos"';
          //$_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        $info[$key] = ''; 
      }
      else{ $info[] = ''; }

      $key = 'module_model';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ 
          //$text = '"Qtd. de Módulos"';
          //$_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ 
        if($edit && $value[$key] != $p[$key]){ 
          //$text = '"Qtd. de Módulos"';
          //$_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        $info[$key] = ''; 
      }
      else{ $info[] = ''; }/**/

      if(!$edit){
        $info[] = '';
        $info[] = '';
      }

      $key = 'type_structure';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Tipo de Estrutura"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ 
        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Tipo de Estrutura"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        $info[$key] = ''; 
      }
      else{ $info[] = ''; }

      $key = 'qtd_inverter';
      if(isset($p[$key]) && is_numeric($p[$key]) && $p[$key] > 0){

        $id_inverter = '';
        $inverter_power = '';
        $inverter_current = '';
        $inverter_mppt = '';

        for($i = 1; $i <= $p[$key]; $i++){
          if((isset($p['id_inverter_'.$i]) && $p['id_inverter_'.$i] != 0) || (isset($p['inverter_power_'.$i]) && $p['inverter_power_'.$i] != '')){

            $key2 = 'id_inverter_'.$i;
            if(isset($p[$key2]) && is_numeric($p[$key2])){
              $id_inverter .= $id_inverter == '' ? $p[$key2] : '||'.$p[$key2];
            }
            else{
              $id_inverter .= $id_inverter == '' ? '0' : '||0';
            }

            $key2 = 'inverter_power_'.$i;
            if(isset($p[$key2]) && $p[$key2] != ''){
              $inverter_power .= $inverter_power == '' ? $p[$key2] : '||'.$p[$key2];
            }
            else{
              $inverter_power .= $inverter_power == '' ? '-' : '||-';
            }
            
            /*$key2 = 'inverter_current_'.$i;
            if(isset($p[$key2]) && $p[$key2] != ''){
              $inverter_current .= $inverter_current == '' ? $p[$key2] : '||'.$p[$key2];
            }
            else{
              $inverter_current .= $inverter_current == '' ? '-' : '||-';
            }

            $key2 = 'inverter_mppt_'.$i;
            if(isset($p[$key2]) && $p[$key2] != ''){
              $inverter_mppt .= $inverter_mppt == '' ? $p[$key2] : '||'.$p[$key2];
            }
            else{
              $inverter_mppt .= $inverter_mppt == '' ? '-' : '||-';
            }/**/
          }
        }

        if($edit){
          if($value['id_inverter'] != $id_inverter || $value['inverter_power'] != $inverter_power){ 
            $text = '"Informações do(s) Inversor(es)"';
            $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
          }

          $info['id_inverter'] = $id_inverter;
          $info['inverter_power'] = $inverter_power;

          //$info['inverter_current'] = $inverter_current;
          //$info['inverter_mppt'] = $inverter_mppt;
        }
        else{
          $info[] = $id_inverter;
          $info[] = $inverter_power;
          $info[] = $inverter_current;
          $info[] = $inverter_mppt;
        }
      }
      else if($edit){ 
        $info['id_inverter'] = 0;
        $info['inverter_power'] = '';
        //$info['inverter_current'] = '';
        //$info['inverter_mppt'] = '';
      }
      else{
        $info[] = 0;
        $info[] = '';
        $info[] = '';
        $info[] = '';
      }

      $key = 'value';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');
        
        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Total da Venda (R$)"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ 
        if($edit && $value[$key] != 0){ 
          $text = '"Total da Venda (R$)"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        $info[$key] = 0; 
      }
      else{ $info[] = 0; }

      $key = 'qtd_parcel';
      if(isset($p[$key]) && is_numeric($p[$key])){
        $values = '';
        $dates = '';

        for($i = 1; $i <= $p[$key]; $i++){
          $isset_value = true;
          $isset_date = true;

          $key2 = 'value_parcel_'.$i;
          if(isset($p[$key2]) && $p[$key2] != ''){
            $p[$key2] = str_replace(".","",$p[$key2]);
            $p[$key2] = strtr($p[$key2], ",", ".");
            $p[$key2] = number_format($p[$key2], 2, '.', '');

            $values .= $values == '' ? $p[$key2] : '||'.$p[$key2];
          }
          else{
            $isset_value = false;
          }

          $key2 = 'date_parcel_'.$i;
          if(isset($p[$key2]) && $p[$key2] != ''){
            $dates .= $dates == '' ? $p[$key2] : '||'.$p[$key2];
          }
          else{
            $isset_date = false;
          }

          if(!$isset_value && $isset_date){
            $values .= $values == '' ? '0.00' : '||0.00';
          }
          else if($isset_value && !$isset_date){
            $dates .= $dates == '' ? '0000-00-00': '||0000-00-00';
          }
        }

        if($edit && ($value['value_parcel'] != $values || $value['date_parcel'] != $dates)){ 
          $text = '"Parcelas"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ 
          $info['value_parcel'] = $values;
          $info['date_parcel'] = $dates;
        }
        else{ 
          $info[] = $values;
          $info[] = $dates; 
        }
      }
      else if(!$edit){
        $info[] = '0.00';
        $info[] = '0000-00-00';
      }

      $key = 'commission';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Comissão"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = 0; }
      else{ $info[] = 0; }

      $key = 'comments';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Observações"';
          $_SESSION['notification'] .= $_SESSION['notification'] == '' ? $text : ', '.$text;
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      if($edit){
        $info['last_att'] = date('Y-m-d H:i:s');
        $info['user_last_att'] = LOGGED_ID2;
        $info['id'] = $p['id']; 
      }
      else{
        $info[] = date('Y-m-d H:i:s');
        $info[] = LOGGED_ID2;
        $info[] = 0;
        $info[] = 0; 
      }

      if($edit){
        $purchase = Db::selectVar('purchases.data', 'id_sale', $p['id']);

        if($purchase != false){
          $info_purchase = []; 

          $key = 'billing_client';
          if(isset($p[$key]) && is_numeric($p[$key])){
            $info_purchase[$key] = $p[$key];
          }

          $key = 'date_arrival_material';
          if(isset($p[$key]) && $p[$key] != ''){
            $info_purchase[$key] = $p[$key];
          }

          $info_purchase['id'] = $purchase['id'];

          Db::update('purchases.data', $info_purchase);
        }
      }


      return $info;
    }

    public static function purchase($p, $edit = false){
      if($edit){
        $value = Db::selectId('purchases.data', $p['id']);
      }

      $key = 'id_sale';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(!$edit){ 
          $info[] = $p[$key]; 

          $sale = Db::selectId('sales.data', $p['id_sale'], true);
          $key = 'auxiliary_date';
          if($edit){ $info[$key] = $sale['date']; }
          else{ $info[] = $sale['date']; }
        }
      }
      else if(!$edit){
        return 'Erro ao buscar id da venda.';
      }

      $key = 'billing_client';
      if(isset($p[$key]) && is_numeric($p[$key]) && $edit){
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = 0;
      }

      $key = 'number_request';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = '';
      }

      $key = 'value_provider';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');
        
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = 0;
      }

      $key = 'data_payment';
      if(isset($p[$key]) && $edit){
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $provider = Db::selectId('contacts.data', Db::selectId('sales.data', $p['id_sale'], true)['id_provider'], true);
        $info[] = $provider != false ? $provider['data_payment'] : '';
      }

      $key = 'qtd_parcel_provider';
      if(isset($p[$key]) && is_numeric($p[$key]) && $edit){
        $values = '';
        $dates = '';

        for($i = 1; $i <= $p[$key]; $i++){
          $isset_value = true;
          $isset_date = true;

          $key2 = 'value_parcel_provider_'.$i;
          if(isset($p[$key2]) && $p[$key2] != ''){
            $p[$key2] = str_replace(".","",$p[$key2]);
            $p[$key2] = strtr($p[$key2], ",", ".");
            $p[$key2] = number_format($p[$key2], 2, '.', '');

            $values .= $values == '' ? $p[$key2] : '||'.$p[$key2];
          }
          else{
            $isset_value = false;
          }

          $key2 = 'date_parcel_provider_'.$i;
          if(isset($p[$key2]) && $p[$key2] != ''){
            $dates .= $dates == '' ? $p[$key2] : '||'.$p[$key2];
          }
          else{
            $isset_date = false;
          }

          if(!$isset_value && $isset_date){
            $values .= $values == '' ? '0.00' : '||0.00';
          }
          else if($isset_value && !$isset_date){
            $dates .= $dates == '' ? '0000-00-00': '||0000-00-00';
          }
        }

        $info['value_parcel_provider'] = $values;
        $info['date_parcel_provider'] = $dates;
      }
      else if(!$edit){
        $info[] = '0.00';
        $info[] = '0000-00-00';
      }

      $key = 'company_freight';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = '';
      }

      $key = 'value_freight';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = 0;
      }

      $key = 'date_arrival_material';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        if($edit && $value[$key] != $p[$key]){ 
          $text = '"Previsão de Chegada dos Materiais"';
          $_SESSION['not'] .= $_SESSION['not'] == '' ? $text : ', '.$text;
        }

        $info[$key] = $p[$key];
      }
      else if(!$edit){
        if($edit && $value[$key] != '0000-00-00'){ 
          $text = '"Previsão de Chegada dos Materiais"';
          $_SESSION['not'] .= $_SESSION['not'] == '' ? $text : ', '.$text;
        }

        $info[] = '0000-00-00';
      }

      /*$key = 'billing';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = 0;
      }

      $key = 'value';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = 0;
      }

      $key = 'qtd_parcel';
      if(isset($p[$key]) && is_numeric($p[$key]) && $edit){
        $values = '';
        $dates = '';

        for($i = 1; $i <= $p[$key]; $i++){
          $isset_value = true;
          $isset_date = true;

          $key2 = 'value_parcel_'.$i;
          if(isset($p[$key2]) && $p[$key2] != ''){
            $p[$key2] = str_replace(".","",$p[$key2]);
            $p[$key2] = strtr($p[$key2], ",", ".");
            $p[$key2] = number_format($p[$key2], 2, '.', '');

            $values .= $values == '' ? $p[$key2] : '||'.$p[$key2];
          }
          else{
            $isset_value = false;
          }

          $key2 = 'date_parcel_'.$i;
          if(isset($p[$key2]) && $p[$key2] != ''){
            $dates .= $dates == '' ? $p[$key2] : '||'.$p[$key2];
          }
          else{
            $isset_date = false;
          }

          if(!$isset_value && $isset_date){
            $values .= $values == '' ? '0.00' : '||0.00';
          }
          else if($isset_value && !$isset_date){
            $dates .= $dates == '' ? '0000-00-00': '||0000-00-00';
          }
        }

        $info['value_parcel'] = $values;
        $info['date_parcel'] = $dates;
      }
      else if(!$edit){
        $info[] = '0.00';
        $info[] = '0000-00-00';
      }/**/

      $key = 'comments';
      if(isset($p[$key]) && $edit){
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = '';
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
        $info[] = 0; 
      }

      return $info;
    }

    public static function project($p, $edit = false){
      $key = 'id_sale';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(!$edit){ 
          $info[] = $p[$key]; 

          $sale = Db::selectId('sales.data', $p['id_sale'], true);
          $key = 'auxiliary_date';
          if($edit){ $info[$key] = $sale['date']; }
          else{ $info[] = $sale['date']; }
        }
      }
      else if(!$edit){
        return 'Erro ao buscar id da venda.';
      }

      $key = 'coordinates';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'elevation';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'inclination';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'angular_steering';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'dealership';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'qtd_uc';
      if(isset($p[$key]) && is_numeric($p[$key]) && $p[$key] > 0){

        $uc = '';
        $credits = '';

        for($i = 1; $i <= $p[$key]; $i++){
          if((isset($p['uc_'.$i]) && $p['uc_'.$i] != '') || (isset($p['credits_'.$i]) && $p['credits_'.$i] != '')){

            $key2 = 'uc_'.$i;
            if(isset($p[$key2]) && $p[$key2] != ''){
              $uc .= $uc == '' ? $p[$key2] : '|%|'.$p[$key2];
            }
            else{
              $uc .= $uc == '' ? '-' : '|%|-';
            }

            $key2 = 'credits_'.$i;
            if(isset($p[$key2]) && $p[$key2] != ''){
              $credits .= $credits == '' ? $p[$key2] : '|%|'.$p[$key2];
            }
            else{
              $credits .= $credits == '' ? '-' : '|%|-';
            }
          }
        }

        if($edit){ 
          $info['uc'] = $uc;
          $info['credits'] = $credits;
        }
        else{
          $info[] = $uc;
          $info[] = $credits;
        }
      }
      else if($edit){ 
        $info['uc'] = '';
        $info['credits'] = '';
      }
      else{
        $info[] = '';
        $info[] = '';
      }

      $key = 'consumption_class';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'source';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'circuit_breaker';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'installed_load';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'art_code';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'comments';
      if(isset($p[$key]) && $edit){
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = '';
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
        $info[] = 0; 
      }

      $info_sale = [];
      $key = 'module_maker';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info_sale[$key] = $p[$key]; }
        else{ $info_sale[$key] = $p[$key]; }
      }
      else if($edit){ $info_sale[$key] = ''; }
      else{ $info_sale[$key] = ''; }

      $key = 'module_model';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info_sale[$key] = $p[$key]; }
        else{ $info_sale[$key] = $p[$key]; }
      }
      else if($edit){ $info_sale[$key] = ''; }
      else{ $info_sale[$key] = ''; }

      $key = 'qtd_inverters';
      if(isset($p[$key]) && is_numeric($p[$key]) && $p[$key] > 0){
        $inverter_current = '';
        $inverter_mppt = '';

        for($i = 1; $i <= $p[$key]; $i++){
          if((isset($p['inverter_current_'.$i]) && $p['inverter_current_'.$i] != '') || (isset($p['inverter_mppt_'.$i]) && $p['inverter_mppt_'.$i] != '')){
            $key2 = 'inverter_current_'.$i;
            if(isset($p[$key2]) && $p[$key2] != ''){
              $inverter_current .= $inverter_current == '' ? $p[$key2] : '||'.$p[$key2];
            }
            else{
              $inverter_current .= $inverter_current == '' ? '-' : '||-';
            }

            $key2 = 'inverter_mppt_'.$i;
            if(isset($p[$key2]) && $p[$key2] != ''){
              $inverter_mppt .= $inverter_mppt == '' ? $p[$key2] : '||'.$p[$key2];
            }
            else{
              $inverter_mppt .= $inverter_mppt == '' ? '-' : '||-';
            }
          }
        }

        if($edit){ 
          $info_sale['inverter_current'] = $inverter_current;
          $info_sale['inverter_mppt'] = $inverter_mppt;
        }
      }

      $info_sale['id'] = $p['id_sale'];

      Db::update('sales.data', $info_sale);

      return $info;
    }

    public static function operational($p, $edit = false){
      $key = 'id_sale';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(!$edit){ 
          $info[] = $p[$key]; 

          $sale = Db::selectId('sales.data', $p['id_sale'], true);
          $key = 'auxiliary_date';
          $info[] = $sale['date'];
        }
      }
      else if(!$edit){
        return 'Erro ao buscar id da venda.';
      }

      $key = 'date_arrival_material';
      if(isset($p[$key]) && $p[$key] != '' && $edit){
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = '0000-00-00';
      }

      $key = 'comments';
      if(isset($p[$key]) && $edit){
        $info[$key] = $p[$key];
      }
      else if(!$edit){
        $info[] = '';
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
        $info[] = 0; 
      }

      return $info;
    }

    public static function bill($p, $edit = false){
      $key = 'id_sale';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Selecione uma venda.';
      }

      if(isset($p[$key]) && $p[$key] != 0){
        $key = 'folder';
        if(isset($p[$key]) && $p[$key] != ''){
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Selecione um tipo de conta.';
        }
      }
      else if($edit){ $info[$key] = 0; }
      else{ $info[] = 0; }

      $key = 'description';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Insira uma descrição.';
      }

      $key = 'value';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');
        
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = 0; }
      else{ $info[] = 0; }

      if(isset($p['status'])){
        $key = 'discount';
        if(isset($p[$key]) && $p[$key] != ''){
          $p[$key] = str_replace(".","",$p[$key]);
          $p[$key] = strtr($p[$key], ",", ".");
          $p[$key] = number_format($p[$key], 2, '.', '');
          
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }

        $key = 'fine';
        if(isset($p[$key]) && $p[$key] != ''){
          $p[$key] = str_replace(".","",$p[$key]);
          $p[$key] = strtr($p[$key], ",", ".");
          $p[$key] = number_format($p[$key], 2, '.', '');
          
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }

        $key = 'value_payment';
        if(isset($p[$key]) && $p[$key] != ''){
          $p[$key] = str_replace(".","",$p[$key]);
          $p[$key] = strtr($p[$key], ",", ".");
          $p[$key] = number_format($p[$key], 2, '.', '');
          
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }
      }
      else if($edit){ 
        $info['discount'] = 0; 
        $info['fine'] = 0; 
        $info['value_payment'] = 0; 
      }
      else{ 
        $info[] = 0; 
        $info[] = 0; 
        $info[] = 0; 
      }

      $key = 'due_date';
      if(isset($p[$key]) && $p[$key] != '0000-00-00' && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Insira uma data de vencimento.';
      }

      if(isset($p['status'])){
        $key = 'payment_date';
        if(isset($p[$key]) && $p[$key] != '0000-00-00' && $p[$key] != ''){
          if($edit){ 
            $info[$key] = $p[$key]; 
            $info['auxiliary_date'] = $p[$key]; 
          }
          else{ 
            $info[] = $p[$key]; 
            $info[] = $p[$key]; 
          }
        }
        else{
          return 'Insira uma data de pagamento.';
        }
      }
      else{
        if($edit){ 
          $info['payment_date'] = '0000-00-00'; 
          $info['auxiliary_date'] = $p['due_date']; 
        }
        else{ 
          $info[] = '0000-00-00'; 
          $info[] = $p['due_date']; 
        }
      }

      $key = 'type_payment';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escolha uma forma de pagamento válida.';
      }

      $key = 'data_payment';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'id_bank';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escolha um banco válido.';
      }

      if(isset($p['status'])){
        if($edit){ $info['status'] = 1; }
        else{ $info[] = 1; }
      }
      else if(!$edit){
        $info[] = 0;
      }

      $key = 'comments';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      if($edit){
        $info['last_att'] = date('Y-m-d H:i:s');
        $info['user_last_att'] = LOGGED_ID2;
        $info['id'] = $p['id']; 
      }
      else{
        $info[] = date('Y-m-d H:i:s');
        $info[] = LOGGED_ID2;
        $info[] = 0;
        $info[] = 0; 
      }

      return $info;
    }

    public static function financial($p, $edit = false){
      $key = 'id_sale';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Selecione uma venda.';
      }

      if(isset($p[$key]) && $p[$key] != 0){
        $key = 'folder';
        if(isset($p[$key]) && $p[$key] != ''){
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Selecione um tipo de conta.';
        }
      }
      else if($edit){ $info[$key] = 0; }
      else{ $info[] = 0; }

      $key = 'type';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Selecione um tipo válido.';
      }

      $key = 'description';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{
          $sale = Db::selectId('sales.data', $p['id_sale']);
          if($sale != false){
            $client = Db::selectId('contacts.data', $sale['id_client']);
            if($client != false){
              $info[] =  isset($p['is_frequent']) && isset($p['time']) && is_numeric($p['time']) && isset($p['frequency']) && is_numeric($p['frequency']) && $p['frequency'] >= 2 ? $p[$key].' - '.$client['name'].' - '.$p['actual_frequency'].'/'.$p['frequency'] : $p[$key].' - '.$client['name'];
            }
            else{
              $info[] =  isset($p['is_frequent']) && isset($p['time']) && is_numeric($p['time']) && isset($p['frequency']) && is_numeric($p['frequency']) && $p['frequency'] >= 2 ? $p[$key].' - '.$p['actual_frequency'].'/'.$p['frequency'] : $p[$key];
            }
          }
          else{
            $info[] =  isset($p['is_frequent']) && isset($p['time']) && is_numeric($p['time']) && isset($p['frequency']) && is_numeric($p['frequency']) && $p['frequency'] >= 2 ? $p[$key].' - '.$p['actual_frequency'].'/'.$p['frequency'] : $p[$key];
          }
        }
      }
      else{
        return 'Insira uma descrição.';
      }

      $key = 'value';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');
        
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = 0; }
      else{ $info[] = 0; }

      if(isset($p['status'])){
        $key = 'discount';
        if(isset($p[$key]) && $p[$key] != ''){
          $p[$key] = str_replace(".","",$p[$key]);
          $p[$key] = strtr($p[$key], ",", ".");
          $p[$key] = number_format($p[$key], 2, '.', '');
          
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }

        $key = 'fine';
        if(isset($p[$key]) && $p[$key] != ''){
          $p[$key] = str_replace(".","",$p[$key]);
          $p[$key] = strtr($p[$key], ",", ".");
          $p[$key] = number_format($p[$key], 2, '.', '');
          
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }

        $key = 'value_payment';
        if(isset($p[$key]) && $p[$key] != ''){
          $p[$key] = str_replace(".","",$p[$key]);
          $p[$key] = strtr($p[$key], ",", ".");
          $p[$key] = number_format($p[$key], 2, '.', '');
          
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }
      }
      else if($edit){ 
        $info['discount'] = 0; 
        $info['fine'] = 0; 
        $info['value_payment'] = 0; 
      }
      else{ 
        $info[] = 0; 
        $info[] = 0; 
        $info[] = 0; 
      }

      $key = 'competency_date';
      if(isset($p['isset_competency']) && isset($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = '0000-00-00'; }
      else{ $info[] = '0000-00-00'; }

      $key = 'due_date';
      if(isset($p[$key]) && $p[$key] != '0000-00-00' && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Insira uma data de vencimento.';
      }

      if(isset($p['status'])){
        $key = 'payment_date';
        if(isset($p[$key]) && $p[$key] != '0000-00-00' && $p[$key] != ''){
          if($edit){ 
            $info[$key] = $p[$key]; 
            $info['auxiliary_date'] = $p[$key]; 
          }
          else{ 
            $info[] = $p[$key]; 
            $info[] = $p[$key]; 
          }
        }
        else{
          return 'Insira uma data de pagamento.';
        }
      }
      else{
        if($edit){ 
          $info['payment_date'] = '0000-00-00'; 
          $info['auxiliary_date'] = $p['due_date']; 
        }
        else{ 
          $info[] = '0000-00-00'; 
          $info[] = $p['due_date']; 
        }
      }

      $key = 'id_type_payment';
      if(isset($p[$key]) && is_numeric($p[$key]) && $p[$key] > 0){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escolha uma forma de pagamento válida.';
      }

      $key = 'data_payment';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'id_category_'.$p['type'];
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info['id_category'] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escolha uma categoria.';
      }

      $key = 'id_bank';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escolha um banco válido.';
      }

      if(isset($p['status'])){
        if($edit){ $info['status'] = 1; }
        else{ $info[] = 1; }
      }
      else if(!$edit){
        $info[] = 0;
      }

      $key = 'comments';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      if($edit){
        $info['last_att'] = date('Y-m-d H:i:s');
        $info['user_last_att'] = LOGGED_ID2;
        $info['id'] = $p['id']; 
      }
      else{
        $info[] = date('Y-m-d H:i:s');
        $info[] = LOGGED_ID2;
        $info[] = 0;
        $info[] = 0; 
      }

      return $info;
    }

    public static function note($p, $edit = false){
      $key = 'note';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escreva uma nota.';
      }

      $key = 'color';
      if(isset($p[$key]) && is_numeric($p[$key]) && isset(Variable::$noteBgColor[$p[$key]])){

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if(!$edit){ $info[] = 0; }

      if($edit){
        $info['last_att'] = date('Y-m-d H:i:s');
        $info['user_last_att'] = LOGGED_ID2;
        $info['id'] = $p['id']; 
      }
      else{
        $info[] = LOGGED_ID2;
        $info[] = date('Y-m-d H:i:s');
        $info[] = LOGGED_ID2;
        $info[] = 0;
      }

      return $info;
    }

    public static function equipament($p, $edit = false){
      $key = 'type';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if(!$edit){
        return 'Tipo de equipamento inválido.';
      }

      $key = 'id_provider';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('contacts.data', $p[$key]) != false){
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if(!$edit){
          return 'Fornecedor inválido.';
        }
      }
      else if(!$edit){
        return 'Fornecedor inválido.';
      }

      $key = 'description';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escreva o nome do modelo.';
      }

      $key = 'maker';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'power';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if(!$edit){ $info[] = 0; }

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

    public static function bank($p, $edit = false){
      $key = 'type';
      if($edit){ $info[$key] = 1; }
      else{ $info[] = 1; }

      $key = 'bank';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info['label'] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escreva o nome do banco.';
      }

      $key = 'holder';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'holder_cpf';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'agent';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'agent_cpf';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'bank';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'bank_code';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'agency';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'type_account';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'account';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'pix';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'initial_balance';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = 0; }
      else{ $info[] = 0; }

      $key = 'date_initial_balance';
      if(isset($p[$key]) && $p[$key] != '0000-00-00' && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = '0000-00-00'; }
      else{ $info[] = '0000-00-00'; }

      $key = 'comments';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; } 

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

    public static function type_payment($p, $edit = false){
      $key = 'label';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escreva a descrição.';
      }

      $key = 'extra_field';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if(!$edit){
        return 'Campo extra inválido.';
      }

      $key = 'comments';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; } 

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

    public static function category($p, $edit = false){
      $key = 'type';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if(!$edit){
        return 'Tipo de categoria inválido.';
      }

      $key = 'code';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; }

      $key = 'label';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escreva a descrição.';
      }

      $key = 'comments';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else if($edit){ $info[$key] = ''; }
      else{ $info[] = ''; } 

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

    public static function contactUC($p, $edit = false){

      $key = 'id_client';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('contacts.data', $p[$key]) != false){
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Escolha um cliente válido.';
        }
      }
      else{
        return 'Escolha um cliente.';
      }

      $key = 'uc';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectVar('contacts.ucs', 'uc', number_format($p[$key],0,'','')) == false){
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if(Db::selectId('contacts.ucs', $p['id'])['uc'] == $p['uc'] && $edit){
          $info[$key] = $p[$key];
        }
        else{
          return 'Já existe uma UC cadastrada com este número.';
        }
      }
      else{
        return 'Digite um número válido para a UC.';
      }

      $a4 = false;
      $key = 'id_class';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('budgets.min_tax_class', $p[$key]) != false){
          if($p[$key] == 11 || $p[$key] == 12){
            $a4 = true;
          }

          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Selecione uma categoria válida para a UC.';
        }
      }
      else{
        return 'Selecione uma categoria para a UC.';
      }

      $key = 'less_tax';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(mb_strpos($p[$key], ',') != false){
          $p[$key] = strtr($p[$key], ",", ".");
        }

        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }
      }

      $key = 'value_icms_less';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(mb_strpos($p[$key], ',') != false){
          $p[$key] = strtr($p[$key], ",", ".");
        }

        $value = $p[$key];

        if($a4){
          $key2 = 'value_p';
          if(isset($p[$key2]) && is_numeric($p[$key2])){
            if(mb_strpos($p[$key2], ',') != false){
              $p[$key2] = strtr($p[$key2], ",", ".");
            }

            $value .= '||'.$p[$key2];
          }
          else{
            return 'Digite um numero válido para a Demanda Contratada Ponta R$/kWh.';
          }
        }

        if($edit){ $info[$key] = $value; }
        else{ $info[] = $value; }
      }
      else{
        return 'Digite um número válido para a Alicota ICMS Reduzida.';
      }

      $key = 'value_icms_upper';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(mb_strpos($p[$key], ',') != false){
          $p[$key] = strtr($p[$key], ",", ".");
        }

        $value = $p[$key];

        if($a4){
          $key2 = 'value_fp';
          if(isset($p[$key2]) && is_numeric($p[$key2])){
            if(mb_strpos($p[$key2], ',') != false){
              $p[$key2] = strtr($p[$key2], ",", ".");
            }

            $value .= '||'.$p[$key2];
          }
          else{
            return 'Digite um numero válido para a Demanda Contratada FP R$/kWh.';
          }
        }

        if($edit){ $info[$key] = $value; }
        else{ $info[] = $value; }
      }
      else{
        return 'Digite um número válido para a Alicota ICMS Maior.';
      }

      $key = 'demand';
      if($a4){
        $demand = '';
        if(isset($p[$key.'_p']) && is_numeric($p[$key.'_p'])){
          if(mb_strpos($p[$key.'_p'], ',') != false){
            $p[$key.'_p'] = strtr($p[$key], ",", ".");
          }
          
          $demand = $p[$key.'_p'];
        }
        else{
          return 'Digite um valor para a Demanda Ponta.';
        }

        if(isset($p[$key.'_fp']) && is_numeric($p[$key.'_fp'])){
          if(mb_strpos($p[$key.'_fp'], ',') != false){
            $p[$key.'_fp'] = strtr($p[$key.'_fp'], ",", ".");
          }
          
          $demand .= '||'.$p[$key.'_fp'];
        }
        else{
          return 'Digite um valor para a Demanda Fora Ponta.';
        }

        if($demand != ''){
          if($edit){ $info[$key] = $demand; }
          else{ $info[] = $demand; }
        }
        else{
          return 'Erro ao obter a demanda da UC.';
        }
      }
      else{
        if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }
      }
      

      $key = 'address';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{ $info[] = '';}

      $key = 'year';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Selecione um ano válido';
      }

      $key = 'consumption';
      if($a4){
        $consumption = '';
        if(isset($p[$key.'_p']) && is_numeric($p[$key.'_p'])){
          if(mb_strpos($p[$key.'_p'], ',') != false){
            $p[$key.'_p'] = strtr($p[$key], ",", ".");
          }
          
          $consumption = $p[$key.'_p'];
        }
        else{
          return 'Digite um valor para a Consumo Ponta.';
        }

        if(isset($p[$key.'_fp']) && is_numeric($p[$key.'_fp'])){
          if(mb_strpos($p[$key.'_fp'], ',') != false){
            $p[$key.'_fp'] = strtr($p[$key.'_fp'], ",", ".");
          }
          $consumption .= '||'.$p[$key.'_fp'];
        }
        else{
          return 'Digite um valor para a Consumo Fora Ponta.';
        }

        if($consumption != ''){
          if($edit){ $info[$key] = $consumption;}
          else{ $info[] = $consumption; }
        }
        else{
          return 'Erro ao obter o consumo da UC.';
        }
      }
      else{
        if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }
      }

      foreach(Variable::$months as $k => $v){
        $key = $v[0];
        if(isset($p[$key]) && is_numeric($p[$key])){
          $$key = $p[$key];

          if($edit){ $info[$key] = $$key; }
          else{ $info[] = $$key; }
        }
        else{
          return 'Digite um número válido para o mês de '.ucfirst($v[1]).'.';
        }
      }

      if($edit){
        $info['deleted'] = '';
        $info['id'] = $p['id'];
      }
      else{ $info[] = 0; }

      return $info;
    }

    public static function budgets($p, $r, $edit = false){
      $edit = isset($p['new_review']) ? false : $edit;
      $a4 = isset($r['a4']) && $r['a4'] == true ? true : false;

      if($edit){
        $notification = '';
        $old = Db::selectId('budgets.data', $p['id']);
      }

      $key = 'id_client';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('contacts.data', $p[$key]) != false){
          if($edit){ 
            $info[$key] = $p[$key];
            if($info[$key] != $old[$key]){
              $notification .= $notification == '' ? 'cliente' : ', cliente';
            }
          }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Escolha um cliente válido.';
        }
      }
      else{
        return 'Escolha um cliente.';
      }

      $key = 'id_sale';
      if($edit){
        $info[$key] = $old[$key];
      }
      else{
        $info[] = 0;
      }
      
      $key = 'proposal';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(isset($p['new_review'])){
          $info[] = $p[$key];
        }
        else if(Db::selectVar('budgets.data', 'proposal', $p[$key]) == false){
          if($edit){ 
            $info[$key] = $p[$key];
            if($info[$key] != $old[$key]){
              $notification .= $notification == '' ? 'proposta' : ', proposta';
            }
          }
          else{ $info[] = $p[$key]; }
        }
        else if($edit && isset($p['id']) && is_numeric($p['id'])){
          if($old['proposal'] == $p[$key]){
            $info[$key] = $p[$key];
            if($info[$key] != $old[$key]){
              $notification .= $notification == '' ? 'proposta' : ', proposta';
            }
          }
          else{
            return 'Já existe uma proposta com este número';
          }
        }
      }
      else{
        return 'Digite um número válido para a proposta.';
      }

      $key = 'review';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(isset($p['new_review'])){
          $sql = MySql::connect()->prepare("SELECT MAX(review) as max FROM `budgets.data` WHERE proposal = ? LIMIT 1");
          $sql->execute([$p['proposal']]);
          $last = $sql->fetch();

          $info[] =  $last['max'] + 1;
        }
        else{ $info[$key] = $p[$key]; }
      }
      else{ $info[] = 0; }

      $key = 'selected_review';
      if(isset($p[$key]) && $edit){
        $info[$key] = 1;
      }
      else{
        if($edit){ $info[$key] = 0; }
        else{ $info[] = 0; }
      }

      $key = 'latitude';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'latitude' : ', latitude';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um número válido para a latitude.';
      }

      $key = 'longitude';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'longitude' : ', longitude';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um número válido para a longitude.';
      }

      $key = 'inclination';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');
        
        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'inclinação' : ', inclinação';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um número válido para a inclination.';
      }

      $key = 'radiation';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ 
          $info[$key] = $p[$key];
          if($p[$key] != $old[$key]){
            $notification .= $notification == '' ? 'radiação' : ', radiação';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um número válido para o índice médio de radiação norte.';
      }

      $key = 'id_dealership';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('budgets.dealerships', $p[$key]) != false){
          if($edit){ 
            $info[$key] = $p[$key];
            if($info[$key] != $old[$key]){
              $notification .= $notification == '' ? 'distribuidor' : ', distribuidor';
            }
          }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Escolha um distribuidor válido.';
        }
      }
      else{
        return 'Escolha um distribuidor.';
      }

      $key = 'uc_generator';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('contacts.ucs', $p[$key]) != false){
          if($edit){ 
            $info[$key] = $p[$key];
            if($info[$key] != $old[$key]){
              $notification .= $notification == '' ? 'UC geradora' : ', UC geradora';
            }
          }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Escolha uma UC Geradora válida.';
        }
      }
      else{
        return 'Escolha uma UC Geradora.';
      }
      
      $ucParticipant = '';
      if(isset($p['qtd_participants']) && $p['qtd_participants'] > 0){
        for($i = 1; $i <= $p['qtd_participants']; $i++){
          if(isset($p['uc_participant_'.$i]) && is_numeric($p['uc_participant_'.$i])){
            $ucParticipant .= $ucParticipant == '' ? $p['uc_participant_'.$i] : '||'.$p['uc_participant_'.$i];
          }
        }
        if($edit){ $info['uc_participant'] = $ucParticipant; }
        else{ $info[] = $ucParticipant; }
      }
      else if($edit){ $info['uc_participant'] = $ucParticipant; }
      else{ $info[] = $ucParticipant; }

      $key = 'id_cons_profile';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('budgets.net_consumption', $p[$key]) != false){
          if($edit){ 
            $info[$key] = $p[$key];
            if($info[$key] != $old[$key]){
              $notification .= $notification == '' ? 'perfil de consumo' : ', perfil de consumo';
            }
          }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Escolha um perfil de consumo válido.';
        }
      }
      else{
        return 'Escolha um perfil de consumo.';
      }

      $key = 'contracted_demand';
      if($a4){
        if(isset($p[$key]) && $p[$key] != ''){
          $p[$key] = str_replace(".","",$p[$key]);
          $p[$key] = strtr($p[$key], ",", ".");
          $p[$key] = number_format($p[$key], 2, '.', '');

          if($edit){ 
            $info[$key] = $p[$key];
            if($info[$key] != $old[$key]){
              $notification .= $notification == '' ? 'demanda contratada  com gerador' : ', demanda contratada  com gerador';
            }
          }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Digite um número válido para a demanda contratada.';
        }
      }
      else{
        if($edit){  $info[$key] = 0; }
        else{ $info[] = 0; }
      }

      $key = 'selected_generator';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'gerador selecionado' : ', gerador selecionado';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{ $info[] = '';}

      $key = 'id_support_type';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'tipo de suporte' : ', tipo de suporte';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Escolha um tipo de suporte.';
      }

      $key = 'module_power';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'potência do módulo' : ', potência do módulo';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um número válido para a potência do módulo.';
      }

      $key = 'brands';
      $key1 = 'brand_1';
      $key2 = 'brand_2';
      $brands = '';
      if(isset($p[$key1]) && $p[$key1] != ''){
        $brands .= $p[$key1];
      }
      if(isset($p[$key2]) && $p[$key2] != ''){
        $brands .= $brands != '' ? '|%|'.$p[$key2] : $p[$key2]; 
      }
      if($edit){ $info[$key] = $brands; }
      else{ $info[] = $brands; }

      $key = 'id_provider';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(Db::selectId('contacts.data', $p[$key]) != false){
          if($edit){ 
            $info[$key] = $p[$key];
            if($info[$key] != $old[$key]){
              $notification .= $notification == '' ? 'fornecedor' : ', fornecedor';
            }
          }
          else{ $info[] = $p[$key]; }
        }
        else{
          return 'Escolha um Fornecedor válido.';
        }
      }
      else{
        return 'Escolha um Fornecedor.';
      }

      $key = 'inverters';
      $inverters = '';
      for($i = 1; $i <= 2; $i++){
        if(isset($p['inverter_'.$i]) && is_numeric($p['inverter_'.$i]) && $p['inverter_'.$i] > 0){
          $inverters .= $inverters != '' ? '|%|'.$p['inverter_'.$i] : $p['inverter_'.$i];
          if(isset($p['qtd_inverter_'.$i]) && is_numeric($p['qtd_inverter_'.$i])){
            $inverters .= '||'.$p['qtd_inverter_'.$i];
          }
          else{ $inverters .= '||'; }
        }
        else{
          return 'Escolha um inversor '.$i.' válido.';
        }
      }
      if($edit){ 
        $info[$key] = $inverters;
        if($info[$key] != $old[$key]){
          $notification .= $notification == '' ? 'inversores' : ', inversores';
        }
      }
      else{ $info[] = $inverters; }

      $key = 'painel_direction';
      $painelDirection = '';
      if(isset($p[$key.'_1']) && $p[$key.'_1'] != ''){
        $p[$key.'_1'] = str_replace("°","",$p[$key.'_1']);
        $painelDirection .=  $p[$key.'_1'];
      }
      else{
        return 'Digite uma direção para o Painel 1';
      }

      if(isset($p[$key.'_2']) && $p[$key.'_2'] != ''){
        $p[$key.'_2'] = str_replace("°","",$p[$key.'_2']);
        $painelDirection .= '||'.$p[$key.'_2'];
      }
      else{
        $painelDirection .=  '||';
      }

      if(isset($p[$key.'_3']) && $p[$key.'_3'] != ''){
        $p[$key.'_3'] = str_replace("°","",$p[$key.'_3']);
        $painelDirection .= '||'.$p[$key.'_3'];
      }
      else{
        $painelDirection .=  '||';
      }

      if($edit){ 
        $info[$key] = $painelDirection;
        if($info[$key] != $old[$key]){
          $notification .= $notification == '' ? 'direção dos paineis' : ', direção dos paineis';
        }
      }
      else{ $info[] = $painelDirection; }


      $key = 'module_division';
      $moduleDivision = '';
      if(isset($p[$key.'_2']) && $p[$key.'_2'] != ''){
        if(isset(explode('||', $painelDirection)[1])){
          $p[$key.'_2'] = str_replace(".","",$p[$key.'_2']);

          $moduleDivision .= $p[$key.'_2'];
        }
        else{
          return 'Insira a divisão de módulos do Painel 2.';
        }
      }
      if(isset($p[$key.'_3']) && is_numeric($p[$key.'_3'])){
        if(isset(explode('||', $painelDirection)[2])){
          $p[$key.'_3'] = str_replace(".","",$p[$key.'_3']);
          
          $moduleDivision .= '||'.$p[$key.'_3'];
        }
        else{
          return 'Insira a divisão de módulos do Painel 3.';
        }
      }

      if($edit){ 
        $info[$key] = $moduleDivision;
        if($info[$key] != $old[$key]){
          $notification .= $notification == '' ? 'divisão dos módulos' : ', divisão dos módulos';
        }
      }
      else{ $info[] = $moduleDivision; }


      $key = 'generator_price';
      if(isset($p[$key]) && $p[$key] != '' ){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'preço do gerador' : ', preço do gerador';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite o valor do gerador.';
      }

      $key = 'generator_discount';
      if(isset($p[$key]) && $p[$key] != '' ){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'desconto do gerador' : ', desconto do gerador';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite a porcentagem de desconto do gerador.';
      }

      $key = 'weg_ticket';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'fatura direto WEG' : ', fatura direto WEG';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite o valor da Fatura direto WEG.';
      }

      $key = 'freight';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'frete CIF' : ', frete CIF';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite o valor do frete do gerador.';
      }

      $key = 'install';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'instalação' : ', instalação';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite o valor da instalação do gerador.';
      }

      $key = 'transport';
      if(isset($p[$key]) && $p[$key] != ''){
        $p[$key] = str_replace(".","",$p[$key]);
        $p[$key] = strtr($p[$key], ",", ".");
        $p[$key] = number_format($p[$key], 2, '.', '');

        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'transporte' : ', transporte';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite o valor do transporte do gerador.';
      }

    //-------------Results---------------//
      $key = 'turnKeyValue';
      if($edit){ $info[$key] = number_format($r[$key],2,'.',''); }
      else{ $info[] = number_format($r[$key],2,'.',''); }

      $key = 'finalPay';
      if($edit){ $info[$key] = number_format($r[$key],2,'.',''); }
      else{ $info[] = number_format($r[$key],2,'.',''); }

      $key = 'entryPay';
      if($edit){ $info[$key] = number_format($r[$key],2,'.',''); }
      else{ $info[] = number_format($r[$key],2,'.',''); }

      $key = 'realInvestmentReturn';
      if($edit){ $info[$key] = $r[$key]; }
      else{ $info[] = $r[$key]; }

      $key = 'savingsBalanceReturn';
      if($edit){ $info[$key] = number_format($r[$key],2,'.',''); }
      else{ $info[] = number_format($r[$key],2,'.',''); }

      $key = 'apportion';
      if($edit){ $info[$key] = $r[$key]; }
      else{ $info[] = $r[$key]; }

      $key = 'prop_power';
      if($edit){ $info[$key] = $r[$key]; }
      else{ $info[] = $r[$key]; }
    //-----------Final Data-------------//
      $key = 'comments';
      if(isset($p[$key]) && $p[$key] != ''){
        if($edit){ 
          $info[$key] = $p[$key];
          if($info[$key] != $old[$key]){
            $notification .= $notification == '' ? 'observações' : ', observações';
          }
        }
        else{ $info[] = $p[$key]; }
      }
      else if(!$edit){ $info[] = ''; }

      $key = 'created_date';
      if(!$edit){ $info[] = date('Y-m-d'); }

      if($edit){
        $info['last_att'] = date('Y-m-d H:i:s');
        $info['user_last_att'] = LOGGED_ID2;
        if($notification != ''){
          $info['notification'] = $old['notification'] != '0' ? $old['notification'].'|%|'.LOGGED_ID2.'||<p>- <b>'.Db::selectId('system.users', LOGGED_ID2)['name'].'</b> alterou os campos de <b>'.$notification.'</b> em '.date('d/m/Y').' às '.date('H:i:s').'</p>' : LOGGED_ID2.'||<p>- <b>'.Db::selectId('system.users', LOGGED_ID2)['name'].'</b> alterou os campos de <b>'.$notification.'</b> em '.date('d/m/Y').' às '.date('H:i:s').'</p>';
        }
        $info['id'] = $p['id'];
      }
      else{
        $info[] = LOGGED_ID2;
        $info[] = date('Y-m-d H:i:s');
        $info[] = LOGGED_ID2;
        $info[] = 0;
        $info[] = 0;
      }

      return $info;
    }

    public static function settings($p){
      $settings = Db::selectAll('budgets.variables');
      $generation = Db::selectAll('budgets.generation');
      $probNetCons = Db::selectAll('budgets.net_consumption');
      $flags = Db::selectAll('budgets.flags');

      $info = [];

      if(!isset($p['confirm_new_settings'])){
        return 'Selecione a caixa de confirmação para realizar a alteração.';
      }
      else{
        if(isset($p['change_variables'])){
          $table = 'variables';
          $i = 0;
          foreach($settings as $k => $v){
            $key = $v['var'];
            $info[$table][$i]['id'] = $v['id'];

            if(isset($p[$key]) && is_numeric($p[$key])){
              if(mb_strpos($p[$key], ',') != false){
                $p[$key] = strtr($p[$key], ",", ".");
              }

              $info[$table][$i]['value'] = $p[$key];
            }
            else{
              return 'Erro durante a importação de dados.';
            }
            $i++;
          }
        }
        
        if(isset($p['change_generation'])){
          $table = 'generation';
          $i = 0;
          foreach($generation as $k => $v){
            $key = 'generation_'.$v['id'];
            $info[$table][$i]['id'] = $v['id'];

            if(isset($p[$key]) && is_numeric($p[$key])){
              if(mb_strpos($p[$key], ',') != false){
                $p[$key] = strtr($p[$key], ",", ".");
              }

              $info[$table][$i]['value'] = $p[$key];
            }
            else{
              return 'Erro durante a importação de dados.';
            }
            $i++;
          }
        }

        if(isset($p['change_net'])){
          $table = 'net_consumption';
          $i = 0;
          foreach($probNetCons as $k => $v){
            $key = 'net_'.$v['id'];
            $info[$table][$i]['id'] = $v['id'];

            if(isset($p[$key]) && is_numeric($p[$key])){
              if(mb_strpos($p[$key], ',') != false){
                $p[$key] = strtr($p[$key], ",", ".");
              }

              $info[$table][$i]['value'] = $p[$key];
            }
            else{
              return 'Erro durante a importação de dados.';
            }
            $i++;
          }
        }

        if(isset($p['change_flags'])){
          $table = 'flags';
          $i = 0;
          foreach($probNetCons as $k => $v){
            $key = 'flag_'.$v['id'];
            $info[$table][$i]['id'] = $v['id'];

            if(isset($p[$key]) && is_numeric($p[$key])){
              if(mb_strpos($p[$key], ',') != false){
                $p[$key] = strtr($p[$key], ",", ".");
              }

              $info[$table][$i]['value'] = $p[$key];
            }
            $i++;
          }
        }
      }

      return $info;
    }

    public static function dealership($p, $edit = false){
      $info = [];

      $key = 'name';
      if(isset($p[$key]) && $p[$key] != ''){
        if(Db::selectVar('budgets.dealerships', 'name', $p[$key], true) == false){
          if($edit){ $info[$key] = $p[$key]; }
          else{ $info[] = $p[$key]; }
        }
        else if($edit && Db::selectVar('budgets.dealerships', 'name', $p[$key], true)['id'] == $p['id']){
          $info[$key] = $p[$key];
        }
        else{
          return 'Já existe um distribuidor cadastrado com este nome.';
        }
      }
      else{
        return 'Digite um nome para o distribuidor.';
      }

      $key = 'tusd_a';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(mb_strpos($p[$key], ',') != false){
          $p[$key] = strtr($p[$key], ",", ".");
        }
        
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um número válido para TUSD A.';
      }

      $key = 'tusd_b';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(mb_strpos($p[$key], ',') != false){
          $p[$key] = strtr($p[$key], ",", ".");
        }
        
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um número válido para TUSD B.';
      }

      $key = 'te';
      if(isset($p[$key]) && is_numeric($p[$key])){
        if(mb_strpos($p[$key], ',') != false){
          $p[$key] = strtr($p[$key], ",", ".");
        }
        
        if($edit){ $info[$key] = $p[$key]; }
        else{ $info[] = $p[$key]; }
      }
      else{
        return 'Digite um número válido para TE.';
      }

      if($edit){
        $info['last_att'] = date('Y-m-d H:i:s');
        $info['user_last_att'] = LOGGED_ID2;
        $info['id'] = $p['id'];
      }
      else{
        $info[] = date('Y-m-d');
        $info[] = LOGGED_ID2;
        $info[] = date('Y-m-d H:i:s');
        $info[] = LOGGED_ID2;
        $info[] = 0;
      }

      return $info;
    }
  }
?>