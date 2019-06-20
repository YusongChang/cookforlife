<?php

/**=== Mysql 資料庫連線類 === */



class DB{



  /** 獲取  Mysql 連線資源 handle*/

  static function getDB(){

    //使用過程化
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if($mysqli->connect_errno){

      exit('資料庫連線失敗，錯誤代碼'.mysqli_connect_errno());

    }

    

    $mysqli->set_charset('utf8');

    return $mysqli;

  }



  /** 關閉 資料庫連線 */

  static function closeDB(&$db,&$result=null){

    //不拷貝變數副本 &

    if(is_object($db)){

      $db->close();

      $db = null;

    }

    if(is_object($result)){

      $result->close();

      $db = null;

    }else{

      $result=null;

      // mysqli_free_result($result);

    }

  }

}

 ?>