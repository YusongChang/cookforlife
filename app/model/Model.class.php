<?php

/**

 * 資料庫操作-模型父類

 */

class Model{



  /** CUD 新增，修改，刪除*/

  protected function CUD(&$sql){

    $db = DB::getDB();

    $result = $db->multi_query($sql);

    echo $db->error;

    $affected_rows = $db->affected_rows;

    DB::closeDB($db,$result);

    return $affected_rows;

  }

  /** 搜尋 1 筆資料 */

  protected function one(&$sql){

    $db = DB::getDB();

    $result= $db->query($sql);

    echo $db->error;

    $obj = $result->fetch_object();

    DB::closeDB($db,$result);

    return $obj;

  }



  /** 搜尋 1 筆資料 */

  protected function all(&$sql){
    $html = array();
    
    $db = DB::getDB();

    $result= $db->query($sql);

    echo $db->error;

    while(!!$obj = $result->fetch_object()) $html[] = $obj; 

    DB::closeDB($db,$result);

    return $html;

  }



  /** 計算搜尋到的資料筆數*/

  protected function total(&$sql){

    $db = DB::getDB();

    $result= $db->query($sql);

    $nums = $result->num_rows;

    DB::closeDB($db,$result);

    return $nums;

  }



  /** 取得預計新增資料的 id */

  protected function nextId(&$table_name){

    $db = DB::getDB();

    // SHOW COLUMNS FROM tbl_name [FROM db_name]

    $sql = "SHOW TABLE STATUS LIKE '$table_name'";

    $obj = $this->one($sql);

    $next_id = $obj->Auto_increment;

    return $next_id;

  }

}

?>