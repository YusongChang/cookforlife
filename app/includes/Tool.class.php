<?php

/** 工具類 */

class Tool{

  /** 回傳錯誤訊息 至 ajax */
  static function errorMsg($info) {
    echo $info;
    exit();
 }

  /** 顯示 JS alert message box 並返回原頁面*/

  static function alertBack($info=null){

    if($info) echo "<script>alert('$info');history.back();</script>";

    echo "<script>history.back();</script>";

    exit();

  }



 /** 顯示 JS alert message box 並跳轉頁面*/

  static function alertLocation($url,$info=null){

    if($info) echo "<script>alert('$info');location.href='$url';</script>";

    echo "<script>location.href='$url';</script>";

    exit();

  }



  /**  轉譯 資料庫 寫入字符串之中的 跳脫字元*/

  static function mysqliString($_data){ 

    if(is_array($_data)){

        foreach ($_data as $key=>$value) {

                $_data[$key] =Tool::mysqliString($value); //遞歸處理 Array

        }

    }else{

        $_data = !GPC ? DB::getDB()->escape_string($_data) : $_data; //檢查 GPC 是否開啟

    } 

    return $_data;

  }



}



?>