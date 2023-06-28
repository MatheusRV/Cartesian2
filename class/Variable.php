<?php
  class Variable{
    public static $relation = [
      '0' => 'Administrador',
      '10' => 'Engenheiro',
      '20' => 'Funcionário',
      '30' => 'Estagiário'
    ];

    public static $generalPermission = [ 
      '0' => 'Administrador Total',
      '1' => 'Administrador Parcial',
      '2' => 'Somente Vizualizar'
    ];

    public static $permissions = [ 
      '0' => 'Cartesiano',
      '1' => 'Pontos Registrados',
      '2' => 'Usuários'
    ];

    public static function removeAccent($string){
      return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"), $string);
    }
    
    public static function renameType($file){ 
      $type = explode('.', $file)[count(explode('.', $file))-1];

      $icon = '';

      if($type == 'png' || $type == 'jpg' || $type == 'jpeg'){
        $icon = '<i class="far fa-file-image"></i> ';
      }
      else if($type == 'pdf'){
        $icon = '<i class="far fa-file-pdf"></i> ';
      }
      else if($type == 'doc' || $type == 'docx' || $type == 'odt' || $type == 'txt'){
        $icon = '<i class="far fa-file-word"></i> ';
      }
      else if($type == 'csv' || $type == 'ods' || $type == 'xlsx' || $type == 'xls' || $type == 'xml'){
        $icon = '<i class="far fa-file-excel"></i> ';
      }
      else if($type == 'zip' || $type == 'rar' || $type == 'tar' || $type == 'z'){
        $icon = '<i class="far fa-file-archive"></i> ';
      }

      if($icon == ''){
        $file = '- '.$file;
      }
      else{
        $first = true;
        $name = $file;
        $file = $icon;
        for($i = 0; $i < (count(explode('.', $name))-1); $i++){
          if($first){
            $file .= explode('.', $name)[$i];
            $first = false;
          }
          else{
            $file .= '.'.explode('.', $name)[$i];
          }
        }
      }

      return $file;
    }

    public static function getFileHtml($file, $link, $redirect = true){ 
      $type = explode('.', $file)[count(explode('.', $file))-1];
      $return = '';
      
      if($type == 'png' || $type == 'jpg' || $type == 'jpeg'){
        $return = $redirect ? '<a class="layer" href="'.INCLUDE_PATH.'file/sale/'.$link.'" target="_blank" title="Abrir '.$file.'."><i class="far fa-file-image"></i></a><img src="'.INCLUDE_PATH.'file/sale/'.$link.'"><span class="delete" title="Deletar arquivo."><i class="fas fa-times-circle"></i></span>' : '<span class="layer" title="'.$file.'."><i class="far fa-file-image"></i></span><i class="far fa-file-image"></i>';
        return $return;
      }
      else if($type == 'pdf'){
        $return = $redirect ? '<a class="layer" href="'.INCLUDE_PATH.'file/sale/'.$link.'" target="_blank" title="Abrir '.$file.'."><i class="far fa-file-pdf"></i></a><i class="far fa-file-pdf"></i><span class="delete" title="Deletar arquivo."><i class="fas fa-times-circle"></i></span>' : '<span class="layer" title="'.$file.'."><i class="far fa-file-pdf"></i></span><i class="far fa-file-pdf"></i>';
        return $return;
      }
      else if($type == 'doc' || $type == 'docx' || $type == 'odt' || $type == 'txt'){
        $return = $redirect ? '<a class="layer" href="'.INCLUDE_PATH.'file/sale/'.$link.'" target="_blank" title="Baixar '.$file.'."><i class="far fa-file-word"></i></a><i class="far fa-file-word"></i><span class="delete" title="Deletar arquivo."><i class="fas fa-times-circle"></i></span>' : '<span class="layer" title="'.$file.'."><i class="far fa-file-word"></i></span><i class="far fa-file-word"></i>';
        return $return;
      }
      else if($type == 'csv' || $type == 'ods' || $type == 'xlsx' || $type == 'xls' || $type == 'xml'){
        $return = $redirect ? '<a class="layer" href="'.INCLUDE_PATH.'file/sale/'.$link.'" target="_blank" title="Baixar '.$file.'."><i class="far fa-file-excel"></i></a><i class="far fa-file-excel"></i><span class="delete" title="Deletar arquivo."><i class="fas fa-times-circle"></i></span>' : '<span class="layer" title="'.$file.'."><i class="far fa-file-excel"></i></span><i class="far fa-file-excel"></i>';
        return $return;
      }
      else if($type == 'zip' || $type == 'rar' || $type == 'tar' || $type == 'z'){
        $return = $redirect ? '<a class="layer" href="'.INCLUDE_PATH.'file/sale/'.$link.'" target="_blank" title="Baixar '.$file.'."><i class="far fa-file-archive"></i></a><i class="far fa-file-archive"></i><span class="delete" title="Deletar arquivo."><i class="fas fa-times-circle"></i></span>' : '<span class="layer" title="'.$file.'."><i class="far fa-file-archive"></i></span><i class="far fa-file-archive"></i>';
        return $return;
      }

      $return = $redirect ? '<a class="layer" href="'.INCLUDE_PATH.'file/sale/'.$link.'" target="_blank" title="Baixar '.$file.'."><i class="far fa-file"></i></a><i class="far fa-file"></i><span class="delete" title="Deletar arquivo."><i class="fas fa-times-circle"></i></span>' : '<span class="layer" title="'.$file.'."><i class="far fa-file"></i></span><i class="far far fa-file"></i>';
      return $return;
    }

    public static function delTree($dir){ 
      $files = array_diff(scandir($dir), array('.','..')); 
      foreach ($files as $file) { 
        (is_dir("$dir/$file")) ? Variable::delTree("$dir/$file") : unlink("$dir/$file"); 
      } 
      return rmdir($dir); 
    }

    public static function getContent($path){
      if(file_exists($path)){
        $directory = array_diff(scandir($path), array('..', '.'));
      }
      else{
        $directory = [];
      }

      return $directory;
    }

    public static function popup(){
      if(isset($_SESSION['add_new']) && $_SESSION['add_new'] == true){
        unset($_SESSION['add_new']);
        echo '<article class="message-success">
          <p>Adicionado com sucesso!</p>
        </article><!--message-success-->';
      }

      if(isset($_SESSION['edit']) && $_SESSION['edit'] == true){
        unset($_SESSION['edit']);
        echo '<article class="message-success">
          <p>Atualizado com sucesso!</p>
        </article><!--message-success-->';
      }

      if(isset($_SESSION['delete']) && $_SESSION['delete'] == true){
        unset($_SESSION['delete']);
        echo '<article class="message-success">
          <p>Deletado com sucesso!</p>
        </article><!--message-success-->';
      }

      if(isset($_SESSION['restore']) && $_SESSION['restore'] == true){
        unset($_SESSION['restore']);
        echo '<article class="message-success">
          <p>Restaurado com sucesso!</p>
        </article><!--message-success-->';
      }
    }

    public static function printButtonPages($pg, $qtdPg, $name_page){
      $deleted = $pg == 'deleted' ? true : false;
      $pg = $pg == 'deleted' ? 1 : $pg;
      $print = '<div class="pages">';

      /*----------SET PAGES----------*/
      if($pg > 2){
        if(($pg+2) <= $qtdPg){
          $extraPg1 = $pg - 3;
          $pages[] = $pg - 2;
          $pages[] = $pg - 1;
          $pages[] = $pg;
          $pages[] = ($pg + 1) <= $qtdPg ? ($pg + 1) : 0;
          $pages[] = ($pg + 2) <= $qtdPg ? ($pg + 2) : 0;
          $extraPg2 = $pg + 3;
        }
        else if(($pg+1) <= $qtdPg){
          $extraPg1 = $pg - 4;
          $pages[] = $pg - 3;
          $pages[] = $pg - 2;
          $pages[] = $pg - 1;
          $pages[] = $pg;
          $pages[] = ($pg + 1) <= $qtdPg ? ($pg + 1) : 0;
          $extraPg2 = $pg + 2;
        }
        else{
          $extraPg1 = $pg - 5;
          $pages[] = $pg - 4;
          $pages[] = $pg - 3;
          $pages[] = $pg - 2;
          $pages[] = $pg - 1;
          $pages[] = $pg;
          $extraPg2 = $pg + 1;
        }
      }
      else if($pg == 2){
        $extraPg1 = $pg - 2;
        $pages[] = $pg - 1;
        $pages[] = $pg;
        $pages[] = ($pg + 1) <= $qtdPg ? ($pg + 1) : 0;
        $pages[] = ($pg + 2) <= $qtdPg ? ($pg + 2) : 0;
        $pages[] = ($pg + 3) <= $qtdPg ? ($pg + 3) : 0;
        $extraPg2 = $pg + 4;
      }
      else if($pg == 1){
        $extraPg1 = $pg - 1;
        $pages[] = $pg;
        $pages[] = ($pg + 1) <= $qtdPg ? ($pg + 1) : 0;
        $pages[] = ($pg + 2) <= $qtdPg ? ($pg + 2) : 0;
        $pages[] = ($pg + 3) <= $qtdPg ? ($pg + 3) : 0;
        $pages[] = ($pg + 4) <= $qtdPg ? ($pg + 4) : 0;
        $extraPg2 = $pg + 5;
      }
      /*------------------------------*/


      /*----------PRINT BUTTONS----------*/
      if(($pg-1) > 0){
        $print .= '<a href="'.INCLUDE_PATH.$name_page.'?pg='.($pg-1).'" title="Página anterior."><i class="fas fa-angle-left"></i></a>';
      }

      if($extraPg1 > 0){
        $print .= '<a href="'.INCLUDE_PATH.$name_page.'?pg='.$extraPg1.'" title="Página '.$extraPg1.'."><i class="fas fa-ellipsis-h"></i></a>';
      }

      foreach($pages as $key => $value){
        if($value > 0){
          if($value == $pg && !$deleted){
            $print .= '<a class="page-selected" href="'.INCLUDE_PATH.$name_page.'?pg='.$value.'" title="Página '.$value.'.">'.$value.'</a>';
          }
          else{
            $print .= '<a href="'.INCLUDE_PATH.$name_page.'?pg='.$value.'" title="Página '.$value.'.">'.$value.'</a>';
          }
        }
      }

      if($extraPg2 <= $qtdPg){
        $print .= '<a href="'.INCLUDE_PATH.$name_page.'?pg='.$extraPg2.'" title="Página '.$extraPg2.'."><i class="fas fa-ellipsis-h"></i></a>';
      }

      if(LOGGED_ID2 == 1){
        $print .= $deleted ? '<a class="page-selected" href="'.INCLUDE_PATH.$name_page.'?pg=deleted" title="Itens Deletados."><i class="fas fa-trash"></i></a>' : '<a href="'.INCLUDE_PATH.$name_page.'?pg=deleted" title="Itens Deletados."><i class="fas fa-trash"></i></a>';
      }

      if(($pg+1) <= $qtdPg){
        $print .= '<a href="'.INCLUDE_PATH.$name_page.'?pg='.($pg+1).'" title="Página seguinte."><i class="fas fa-angle-right"></i></a>';
      }
      /*------------------------------*/

      $print .= '</div><!--pages-->';

      return $print;
    }

    public static function qtdPg($name_table, $perPg = 50){
      $qtdPg = MySql::connect()->prepare("SELECT id FROM `$name_table` WHERE deleted = 0");
      $qtdPg->execute();
      $qtdPg = $qtdPg->rowCount();

      return ceil($qtdPg / $perPg);
    }

    public static function pg($qtdPg){
      if(isset($_GET['pg'])){
        if(is_numeric($_GET['pg']) && $_GET['pg'] > 0 && $_GET['pg'] <= $qtdPg){
          $pg = (int)$_GET['pg'];
        }
        else if($_GET['pg'] == 'deleted' && LOGGED_ID2 == 1){
          $pg = 'deleted';
        }
        else{
          return 'location';
        }
      }
      else{
        $pg = 1;
      }

      return $pg;
    }

    public static function suboption($page){
      if($page == 'contacts'){
        return 'contacts';
      }
      else if($page == 'system_users'){
        return 'system';
      }
      else if($page == 'cash_flow' || $page == 'bills' || $page == 'financial_records'){
        return 'financial';
      }
      else if($page == 'all_budgets' || $page == 'contacts_ucs'){
        return 'budgets';
      }

      return '';
    }

    public static function dataVerify($id_sale, $sector){
      $sale = Db::selectId('sales.data', $id_sale, true);
      $client = Db::selectId('contacts.data', $sale['id_client'], true);

      $purchase = $sector > 20 ? Db::selectVar('purchases.data', 'id_sale', $id_sale, true) : Db::selectVar('purchases.data', 'id_sale', $id_sale);
      $project = $sector > 30 ? Db::selectVar('projects.data', 'id_sale', $id_sale, true) : Db::selectVar('projects.data', 'id_sale', $id_sale);
      $operational = $sector > 40 ? Db::selectVar('operational.data', 'id_sale', $id_sale, true) : Db::selectVar('operational.data', 'id_sale', $id_sale);
      
      $append = '';
      $issetSector = false; 

      if($sector == 10){
        /*----------*/
        $append0 = '';
        $isset = false;
        if($client['cpf'] == ''){
          $append0 .= $client['type'] == 0 ? '<p class="w50">- CPF</p>' : '<p class="w50">- CNPJ</p>';
          $isset = true;
        }
        /*----------*/
        $append0 .= $client['address'] == '' ? '<p class="w50">- Endereço</p>' : '';
        $isset = $client['address'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $client['fone'] == '' && $client['fone2'] == '' ? '<p class="w50">- Celular ou Telefone</p>' : '';
        $isset = $client['fone'] == '' && $client['fone2'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $client['email'] == '' ? '<p class="w50">- E-mail</p>' : '';
        $isset = $client['email'] == '' ? true : $isset;
        /*----------*/
        $path = DIR.'/file/client/'.$client['id'].'/documents';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Documentos</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $append .= $isset ? '<div class="comments flex"><p class="w100"><b>Do Cliente:</b></p>'.$append0.'</div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>' : '<div class="comments"><p class="w100"><b>Do Cliente:</b> <i>Nenhum dado faltante.</i></p></div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>';
        /*----------*/


        /*----------*/
        $append0 = '';
        $class = $sector == 10 ? 'highlight-' : '';
        $isset = false;
        /*----------*/
        $append0 .= $sale['type_structure'] == '0' ? '<p>- Tipo de Estrutura</p>' : '';
        $isset = $sale['type_structure'] == '0' ? true : $isset;
        /*----------
        $append0 .= $sale['module_model'] == '' ? '<p>- Modelo do Módulo</p>' : '';
        $isset = $sale['module_model'] == '' ? true : $isset;
        /*----------
        $append0 .= $sale['module_maker'] == '' ? '<p>- Fabricante do Módulo</p>' : '';
        $isset = $sale['module_maker'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $sale['module_power'] == 0 ? '<p>- Potência do Módulo</p>' : '';
        $isset = $sale['module_power'] == 0 ? true : $isset;
        /*----------*/
        $append0 .= $sale['module_qtd'] == 0 ? '<p>- Qtd. de Módulos</p>' : '';
        $isset = $sale['module_qtd'] == 0 ? true : $isset;
        /*----------*/
        $append0 .= $sale['id_provider'] == 0 ? '<p>- Fornecedor</p>' : '';
        $isset = $sale['id_provider'] == 0 ? true : $isset;
        /*----------*/
        $append0 .= $sale['id_inverter'] == 0 || $sale['id_inverter'] == '' ? '<p>- Modelo Inversor</p>' : '';
        $isset = $sale['id_inverter'] == 0 || $sale['id_inverter'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $sale['inverter_power'] == '' ? '<p>- Potência Inversor</p>' : '';
        $isset = $sale['inverter_power'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $sale['value'] == 0 ? '<p>- Valor Total (R$)</p>' : '';
        $isset = $sale['value'] == 0 ? true : $isset;
        /*----------*/
        $append0 .= $sale['value_parcel'] == '0.00' && $sale['date_parcel'] == '0000-00-00' ? '<p>- Parcelas (R$)</p>' : '';
        $isset = $sale['value_parcel'] == '0.00' && $sale['date_parcel'] == '0000-00-00' ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/010/016';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Proposta</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/010/012';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Contrato</p>' : '';
        //$isset = count($directory) == 0 ? true : $isset;
        /*----------*
        $path = DIR.'/file/sale/'.$sale['id'].'/020';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Levantamento Fotográfico Inicial</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/120/123';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Boleto para o Cliente</p>' : '';
        //$isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/120/124';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Comprovante de Pagamento do Cliente</p>' : '';
        //$isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $append .= $isset ? '<div class="comments '.$class.'"><p class="w100"><b>Setor de Vendas:</b></p>'.$append0.'</div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>' : '<div class="comments '.$class.'"><p class="w100"><b>Setor de Vendas:</b> <i>Nenhum dado faltante.</i></p></div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>';
        $issetSector = $sector == 10 && $isset ? true : $issetSector;
        /*----------*/
      }


      /*----------*/
      if($sector == 20 && $purchase == 0){
        $append .= '<div class="comments"><p class="w100"><b>Setor de Compras:</b> <i>Não encaminhado</i></p></div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>';
      }
      else if($sector == 20){
        /*----------*/
        $append0 = '';
        $class = $sector == 20 ? 'highlight-' : '';
        $isset = false;
        $path = DIR.'/file/sale/'.$sale['id'].'/030';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 && $purchase['number_request'] == '' ? '<p>- Número do Pedido</p>' : '';
        $isset = count($directory) == 0 && $purchase['number_request'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $purchase['value_provider'] == 0 ? '<p>- Valor Total da Compra no Fornecedor (R$)</p>' : '';
        $isset = $purchase['value_provider'] == 0 ? true : $isset;
        /*----------*/
        $append0 .= $purchase['value_parcel_provider'] == '0.00' && $purchase['date_parcel_provider'] == '0000-00-00' ? '<p>- Pagamento Fornecedor</p>' : '';
        $isset = $purchase['value_parcel_provider'] == '0.00' && $purchase['date_parcel_provider'] == '0000-00-00' ? true : $isset;
        /*----------*/
        $append0 .= $purchase['company_freight'] == '' ? '<p>- Transportadora</p>' : '';
        $isset = $purchase['company_freight'] == '' ? true : $isset;
        $append0 .= $purchase['date_arrival_material'] == '0000-00-00' ? '<p>- Previsão de Chegada dos Materiais</p>' : '';
        $isset = $purchase['date_arrival_material'] == '0000-00-00 ' ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/050';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Lista Técnica</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*
        $append0 .= $purchase['value'] == 0 ? '<p>- Total dos Serviços (R$)</p>' : '';
        $isset = $purchase['value'] == 0 ? true : $isset;
        /*----------*
        $append0 .= $purchase['value_parcel'] == '0.00' && $purchase['date_parcel'] == '0000-00-00' ? '<p>- Parcelas Serviços</p>' : '';
        $isset = $purchase['value_parcel'] == '0.00' && $purchase['date_parcel'] == '0000-00-00' ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/010/012';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Contrato</p>' : '';
        //$isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/120/126';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Nota Fiscal</p>' : '';
        //$isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $append .= $isset ? '<div class="comments '.$class.'"><p class="w100"><b>Setor de Compras:</b></p>'.$append0.'</div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>' : '<div class="comments '.$class.'"><p class="w100"><b>Setor de Compras:</b> <i>Nenhum dado faltante.</i></p></div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>';
        $issetSector = $sector == 20 && $isset ? true : $issetSector;
        /*----------*/
      }
      /*----------*/


      /*----------*/
      if($sector == 30 && $project == 0){
        $append .= '<div class="comments"><p class="w100"><b>Setor de Projetos:</b> <i>Não encaminhado</i></p></div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>';
      }
      else if($sector == 30){
        /*----------*/
        $append0 = '';
        $class = $sector == 30 ? 'highlight-' : '';
        $isset = false;
        /*----------*/
        $append0 .= $project['coordinates'] == '' ? '<p>- Coordenadas</p>' : '';
        $isset = $project['coordinates'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $project['elevation'] == '' ? '<p>- Elevação</p>' : '';
        $isset = $project['elevation'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $sale['module_model'] == '' ? '<p>- Modelo do Módulo</p>' : '';
        $isset = $sale['module_model'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $sale['module_maker'] == '' ? '<p>- Fabricante do Módulo</p>' : '';
        $isset = $sale['module_maker'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $sale['inverter_current'] == '' ? '<p>- Imax Inversor</p>' : '';
        $isset = $sale['inverter_current'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $sale['inverter_mppt'] == '' ? '<p>- Qtd. de MPPT / String por MPPT Inversor</p>' : '';
        $isset = $sale['inverter_mppt'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $project['inclination'] == '' ? '<p>- Inclinação do Painel</p>' : '';
        $isset = $project['inclination'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $project['angular_steering'] == '' ? '<p>- Direcionamento Angular</p>' : '';
        $isset = $project['angular_steering'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $project['dealership'] == '' ? '<p>- Distribuidora/Concessionária</p>' : '';
        $isset = $project['dealership'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $project['uc'] == '' ? '<p>- UC</p>' : '';
        $isset = $project['uc'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $project['consumption_class'] == '' ? '<p>- Classe de Consumo</p>' : '';
        $isset = $project['consumption_class'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $project['source'] == '' ? '<p>- Alimentação</p>' : '';
        $isset = $project['source'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $project['circuit_breaker'] == '' ? '<p>- Disjuntor</p>' : '';
        $isset = $project['circuit_breaker'] == '' ? true : $isset;
        /*----------*/
        $append0 .= $project['installed_load'] == '' ? '<p>- Demanda/Carga Instalada</p>' : '';
        $isset = $project['installed_load'] == '' ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/020';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Levantamento Fotográfico Inicial</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/060';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Projeto Unifilar</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/070/072';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- ART Emitida</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/070/076';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Boleto ART</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/070/074';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- ART Assinada</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/080/082';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Datasheet Módulos</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/080/084';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Datasheet Inversor</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/090/092';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Registro / Certificados</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/090/094';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Memorial Montado</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $append .= $isset ? '<div class="comments '.$class.'"><p class="w100"><b>Setor de Projetos:</b></p>'.$append0.'</div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>' : '<div class="comments '.$class.'"><p class="w100"><b>Setor de Projetos:</b> <i>Nenhum dado faltante.</i></p></div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>';
        $issetSector = $sector == 30 && $isset ? true : $issetSector;
        /*----------*/
      }
      /*----------*/


      /*----------*/
      if($sector == 40 && $operational == 0){
        $append .= '<div class="comments"><p class="w100"><b>Setor Operacional:</b> <i>Não encaminhado</i></p></div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>';
      }
      else if($sector == 40){
        /*----------*/
        $append0 = '';
        $class = $sector == 40 ? 'highlight-' : '';
        $isset = false;
        $append0 .= $operational['date_arrival_material'] == '0000-00-00' ? '<p>- Data do Recebimento dos Materiais</p>' : '';
        $isset = $operational['date_arrival_material'] == '0000-00-00' ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/100/102';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Foto Etiqueta Módulo</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/100/104';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Foto Etiqueta Inversor</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/100/106';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Nº de Série dos Módulos</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $path = DIR.'/file/sale/'.$sale['id'].'/110';
        $directory = Variable::getContent($path);
        $append0 .= count($directory) == 0 ? '<p>- Levantamento Fotográfico Final</p>' : '';
        $isset = count($directory) == 0 ? true : $isset;
        /*----------*/
        $append .= $isset ? '<div class="comments '.$class.'"><p class="w100"><b>Setor Operacional:</b></p>'.$append0.'</div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>' : '<div class="comments '.$class.'"><p class="w100"><b>Setor Operacional:</b> <i>Nenhum dado faltante.</i></p></div><div class="comments"><hr style="border: 1px dashed #ccc;"></div>';
        $issetSector = $sector == 40 && $isset ? true : $issetSector;
        /*----------*/
      }
      /*----------*/

      $return['html'] = '<div><h3 class="slide-on">Dados Faltantes <i class="fas fa-chevron-down"></i></h3><div class="slide">'.$append.'</div><!--slide--></div><hr>';
      $return['highlight'] = $issetSector ? 'highlight' : '';

      return $return;
    }
  }
?>