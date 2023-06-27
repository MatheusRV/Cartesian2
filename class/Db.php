<?php
  class Db{
    public static function selectAll($table, $elOrder = null, $order = null){
      $query = "SELECT * FROM `$table` WHERE deleted = 0";

      if($order != null && $elOrder != null){
        $query .= " ORDER BY $elOrder $order";
      }
      
      $sql = MySql::connect()->prepare($query);
      $sql->execute();

      return $sql->fetchAll();
    }
    public static function selectAllDeleted($table, $elOrder = null, $order = null){
      $query = "SELECT * FROM `$table` WHERE deleted = 1";

      if($order != null && $elOrder != null){
        $query .= " ORDER BY $elOrder $order";
      }
      
      $sql = MySql::connect()->prepare($query);
      $sql->execute();

      return $sql->fetchAll();
    }

    public static function selectAllByVar($table, $var, $condition, $anyway = false, $elOrder = null, $order = null){
      $sql = MySql::connect()->prepare("SELECT * FROM `$table` WHERE `$var` = ? AND deleted = 0");
      $sql->execute([$condition]);

      $sql = $sql->fetchAll();

      if($anyway && $sql == false){
        $sql = '';
        $sql = MySql::connect()->prepare("SELECT * FROM `$table` WHERE `$var` = ?");
        $sql->execute([$condition]);
        $sql = $sql->fetchAll();
      }

      return $sql;
    }

    public static function selectId($table, $id, $anyway = false){
      $sql = MySql::connect()->prepare("SELECT * FROM `$table` WHERE id = ? AND deleted = 0 LIMIT 1");
      $sql->execute([$id]);

      $sql = $sql->fetch();

      if($anyway && $sql == false){
        $sql = '';
        $sql = MySql::connect()->prepare("SELECT * FROM `$table` WHERE id = ? LIMIT 1");
        $sql->execute([$id]);
        $sql = $sql->fetch();
      }

      return $sql;
    }

    public static function selectDeletedId($table, $id){
      $sql = MySql::connect()->prepare("SELECT * FROM `$table` WHERE id = ? AND deleted = 1 LIMIT 1");
      $sql->execute([$id]);

      return $sql->fetch();
    }

    public static function selectVar($table, $var, $condition, $anyway = false){
      $sql = MySql::connect()->prepare("SELECT * FROM `$table` WHERE `$var` = ? AND deleted = 0 LIMIT 1");
      $sql->execute([$condition]);

      $sql = $sql->fetch();

      if($anyway && $sql == false){
        $sql = '';
        $sql = MySql::connect()->prepare("SELECT * FROM `$table` WHERE `$var` = ? LIMIT 1");
        $sql->execute([$condition]);
        $sql = $sql->fetch();
      }

      return $sql;
    }

    public static function selectLimited($table, $start, $end, $elOrder = null, $order = null, $by_att = null){
       $query = "SELECT * FROM `$table` WHERE deleted = 0";

      if($order != null && $elOrder != null){
        $query .= $by_att == null ? " ORDER BY $elOrder $order" :" ORDER BY last_att DESC, $elOrder $order";
      }

      $query .= " LIMIT $start, $end";
      $sql = MySql::connect()->prepare($query);
      $sql->execute();

      return $sql->fetchAll();
    }

    public static function insert($table, $info){
      $query = "INSERT INTO `$table` VALUES (null";

      foreach($info as $key => $value){
      $query .= ",?";
      }

      $query .= ")";

      $sql = MySql::connect()->prepare($query);

      if($sql->execute($info)){
        return true;
      }
      else{
        return false;
      }
    }

    public static function update($table, $info){
      $first = 0;
      $query = "UPDATE `$table` SET ";

      foreach($info as $key => $value){
        if($first == 0){
          if($key != 'id'){
            $query .= $key.' = ?';
            $data[] = $value;

            $first++;
          }
        }
        else{
          if($key != 'id'){
            $query .= ','.$key.' = ?';
            $data[] = $value;
          }
        }
      }

      $query .= " WHERE id = ?";
      $data[] = $info['id'];

      $sql = MySql::connect()->prepare($query);

      if($sql->execute($data)){
        return true;
      }
      else{
        return false;
      }
    }

    public static function uploadFile($path, $f){
      $path = DIR.'/file/'.$path;
      if(!file_exists($path)){
        mkdir($path, 0777, true);
      }

      if($f['error'] == 0){
        $name = $f['name'];
        $verify_name = glob($path.'/'.$name, GLOB_BRACE);

        $k = 1;
        while(count($verify_name) > 0){
          $name = '';
          for($j = 0; $j < (count(explode('.', $f['name']))-1); $j++){
            $name .= $name == '' ? explode('.', $f['name'])[$j] : '.'.explode('.', $f['name'])[$j];
          }
          $name .= ' ('.$k.').'.explode('.', $f['name'])[count(explode('.', $f['name']))-1];
          $verify_name = glob($path.'/'.$name, GLOB_BRACE);

          $k++;
        }

        if(!move_uploaded_file($f['tmp_name'], $path.'/'.$name)){
          return false;
        }

        return $name;
      }

      return false;
    }

    public static function uploadCopyFile($f, $path){
      $path = DIR.'/file/'.$path;
      if(!file_exists($path)){
        mkdir($path, 0777, true);
      }
      
      if($f['error'] == 0){
        $name = $f['name'];
        $verify_name = glob($path.'/'.$name, GLOB_BRACE);

        $k = 1;
        while(count($verify_name) > 0){
          $name = '';
          for($j = 0; $j < (count(explode('.', $f['name']))-1); $j++){
            $name .= $name == '' ? explode('.', $f['name'])[$j] : '.'.explode('.', $f['name'])[$j];
          }
          $name .= ' ('.$k.').'.explode('.', $f['name'])[count(explode('.', $f['name']))-1];
          $verify_name = glob($path.'/'.$name, GLOB_BRACE);

          $k++;
        }

        if(!copy($f['tmp_name'], $path.'/'.$name)){
          return false;
        }
      }

      return true;
    }

    public static function copyFile($f1, $f2, $file_name){
      $path = DIR.'/file/'.$f2;
      $f1 = DIR.'/file/'.$f1.'/'.$file_name;
      $f2 = DIR.'/file/'.$f2;
      if(file_exists($f1)){
        $name = $file_name;
        $verify_name = glob($f2.'/'.$name, GLOB_BRACE);

        $k = 1;
        while(count($verify_name) > 0){
          $name = '';
          for($j = 0; $j < (count(explode('.', $file_name))-1); $j++){
            $name .= $name == '' ? explode('.', $file_name)[$j] : '.'.explode('.', $file_name)[$j];
          }
          $name .= ' ('.$k.').'.explode('.', $file_name)[count(explode('.', $file_name))-1];
          $verify_name = glob($path.'/'.$name, GLOB_BRACE);

          $k++;
        }

        if(!copy($f1, $f2.'/'.$name)){
          return false;
        }
      }

      return true;
    }

    public static function verifyPath($path, $p, $qtd, $pre){
      $path = DIR.'/file/'.$path;

      if(file_exists($path)){
        $directory = array_diff(scandir($path), array('..', '.'));

        $return = false;
        foreach($directory as $key => $file){
          if(!is_dir($path.'/'.$file)){
            $isset = false;

            for($i = 1; $i <= $qtd; $i++){
              if(isset($p[$pre.$i]) && $p[$pre.$i] == $file){
                $isset = true;
              }
            }

            if(!$isset){
              unlink($path.'/'.$file);
              $return .= $return == false ? '"'.$file.'"' : ', "'.$file.'"';
            }
          }
        }

        return $return;
      }
      return false;
    }

    public static function removeFile($file){
      if(file_exists($file)){
        if(unlink($file)){
          return true;
        }
      }

      return false;
    }

    public static function removeNotification($table, $id){
      $info = [];
      $info['notification'] = 0;
      $info['id'] = $id;

      return Db::update($table, $info);
    }

    public static function delete($table, $id){
      $info = [];
      $info['last_att'] = date('Y-m-d H:i:s');
      $info['user_last_att'] = LOGGED_ID2;
      $info['deleted'] = 1;
      $info['id'] = $id;

      if(Db::update($table, $info)){
        return true;
      }
      else return false;
    }

    public static function restore($table, $id){
      $info = [];
      $info['last_att'] = date('Y-m-d H:i:s');
      $info['user_last_att'] = LOGGED_ID2;
      $info['deleted'] = 0;
      $info['id'] = $id;

      if(Db::update($table, $info)){
        return true;
      }
      else return false;
    }

    public static function selectBudget($id, $proposal){
      $sql = MySql::connect()->prepare("SELECT * FROM `budgets.data` WHERE proposal = ? ORDER BY review ASC");
      $sql->execute([$proposal]);
      $budgets = $sql->fetchAll();

      $updt = [];

      foreach($budgets as $k => $v){
        $updt['id'] = $v['id'];
        
        if($updt['id'] == $id){
          $updt['selected_review'] = 1;
          $updt['deleted'] = 0;
        }
        else{
          $updt['selected_review'] = 0;
          $updt['deleted'] = 1;
        }
        Db::update('budgets.data', $updt);
      }
    }
  }
?>