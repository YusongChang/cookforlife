<?php

/** 驗證類 */

class Validate{



  /** 檢查 會員、管理員 登入狀態*/
  static function checkUserLogin(){

    if( isset($_COOKIE['cookied'])
     && isset($_COOKIE['cook']) 
     && isset($_COOKIE['csk']) 
     && isset($_COOKIE['mv'])
     && isset($_COOKIE['ln'])
     ){
       return false;
    }
    else return true;

  }

  /** 檢查 會員、管理員 登入狀態*/
  static function checkManagerLogin(){

    if( isset($_COOKIE['cookied'])
    && isset($_COOKIE['cook']) 
    && isset($_COOKIE['csk']) 
    && isset($_COOKIE['mv'])
    && isset($_COOKIE['ln'])
    ){
        switch($_COOKIE['mv']){//權限判斷
          
          case '1':
          case '2': Tool::alertLocation('../','無 權 訪 問!');
            break;

          case '3': 
          case '4':
          case '5':
          case '6': return false;
            break;

          default: Tool::alertLocation('../','無 權 訪 問!'); 
            break;

        }

    }else return true;

  }

  /** 驗證字符長度 

   * @param Mixed $string 字符串

   * @param Int $min 最小長度

   * @param Int $max 最大長度

   * @return Boolean

  */
  static function checkStrLen($string,$min,$max){

    if(is_array($string)){

      foreach($arr as $v){

        $result = self::checkStrLen($v,$min,$max);

      }

    }else{

      $strlen = mb_strlen($string,'utf8');

      $result = $strlen < $min  ||  $strlen > $max ? true:false;

   }

    return  $result;

  }

  /** 驗證會員身分 *重要: 網站的 CUD 屬危險操作

   * @param Int $userId 會員 ID

   * @param Int $ckey   會員 驗證序號

   */
  static function checkUserValidate($userId,$ckey,$level,$levelName){
     $model = new UserModel();
     $model->user_id = $userId;
     $model->ckey = $ckey;
     $model->level = $level;
     $model->levelName = $levelName;
     return $model->validateUser() ? false : true; //false 會員存在  反之 true

  }

  /** 驗證會員是否是本人
   * @param Int $userId 會員 ID (這裡是拿 cookie 紀錄值）
   * @param Int $ckey   會員 驗證序號
   * @param Int $id 對照會員的 ID （這裡是拿 $_GET['id'] 紀錄值）
   */
  static function checkUserSelf($userId,$ckey,$id){
    $model = new UserModel();
    $model->user_id = $userId;
    $model->ckey = $ckey;
    $result = $model->checkUserSelf();
    return  $result->id == $id? false : true; //false 是會員本人  反之 true
  }

  /** 驗證管理員等級權限 *重要: 網站的 CUD 屬危險操作

   * @param Int $userId 會員 ID

   * @param Int $ckey   會員 驗證序號

   */
  static function checkManagerValidate($userId,$ckey,$level,$levelName,$privileges){
    $model = new UserModel();
    $model->user_id = $userId;
    $model->ckey = $ckey;
    $model->level = $level;
    $model->levelName = $levelName;
    $result = $model->validateManager();
    return $result->id >= $privileges? false : true; //false 有權限編輯  反之 true
 }

  /** 驗證身分、權限可否編輯此文檔 *重要: 網站的 CUD 屬危險操作

   * @param Int $id 文檔 ID
   * 
   * @param Int $userId 會員 ID

   * @param Int $ckey   會員 驗證序號
   
   * @param Int $level  會員等級 編號
   * 
   * @param String $levelName  會員等級 名稱
   * 
   * @param Int $privileges   會員 驗證等級編號

   */
  static function checkRecipeAuthor($id,$userId,$ckey,$level){
    $model = new RecipeModel();
    $model->id = $id;
    $result = $model->checkRecipe();
    //等級至少是大於等於 4
    return $result->user_id == $userId || $level >=4 ? false : true; //false 是會員本人 或 有權限編輯  反之 true
 }

   /** 驗證是否為會員本人的收藏、權限可否編輯此食譜 *重要: 網站的 CUD 屬危險操作

   * @param Int $collecId 收藏 ID
   * 
   * @param Int $id 被收藏的食譜 ID
   * 
   * @param Int $userId 會員 ID
   */
  static function deleteSelfCollection($collecId,$id,$userId){
    $model = new CollectRecipeModel();
    $model->collec_id = $collecId;
    $model->id = $id; //食譜 id
    $model->user_id = $userId; //會員 id
    $result = $model->deleteSelfCollection();
    return $result ? false : true; //false 是會員本人的收藏  反之 true
 }

 /** 檢查 EMAIL*/
 static function checkEmail($string){
  return !preg_match("/^\w+((-\w+)|(\.\w+))*\@[a-zA-Z0-9]+((\.|-)[a-zA-Z0-9]+)*\.[a-zA-Z]+$/",$string);
 }
 
 /**檢查手機 */
 static function checkPhone($string){
  return !preg_match("/^[09]\d{9}$/",$string);
 }

}



?>