<?php

/** Session 設置*/

session_start();

/** Set Time Zone */

date_default_timezone_set ('Asia/Taipei');

/** === 資料庫連線 ===*/

define('DB_NAME','id8867658_cookforlife');

define('DB_USER','id8867658_soholifeguy');

define('DB_PASS','sohogoodman99');

define('DB_HOST','localhost');

/** mysql 轉譯功能*/

define('GPC',get_magic_quotes_gpc());           //mysqli 轉譯功能是否打開了 true/false

/** 專案文件下的絕對路徑*/

define('ROOT_PATH',dirname(__FILE__));



/** === 自動引入類別 ===  */

spl_autoload_register(function ($class_name) {
  if(substr($class_name,-6) == 'Action'){

    include ROOT_PATH.'/app/action/'.$class_name.'.class.php';

  }elseif(substr($class_name,-5) == 'Model'){

    include ROOT_PATH.'/app/model/'.$class_name.'.class.php';

  }else{

    include ROOT_PATH.'/app/includes/'.$class_name.'.class.php';

  }
});

?>