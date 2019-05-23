<?php

/**

 * 業務流程控制器類 -- 會員 註冊、登入、登出  -- 業務邏輯

 */

class UserLoginAction{

  private $user_ckey;   //使用者 驗證序號

  private $model;       //存放 model 資源物件

  private $user_Obj;    //存放 會員資料 物件



  public function __construct(){

    $this->model = new UserModel();

  }



  /**

  * 流程控制器

  * @return void

  */

  public function action(){

    switch(@$_GET['act']){

      case 'reg':

        $this->register();

        break;

      case 'login':

        $this->login();

        break;

      case 'logout':

        $this->logout();

        break;

      default:
        //已登入則跳回首頁
        Validate::checkUserLogin() ? $this->login() : Tool::alertLocation('index.php');
    }

  }

  /** 
   * 註冊 
   */
  private function register(){
    if(isset($_POST['reg-send'])){
    }
    return;
  }



  /** 登入 */

  private function login(){
    if(isset($_POST['login'])){

      /**登入錯誤次數 超過 5次  須鎖定用戶 IP 10 mins 後才能使用登入系統!*/

      Validate::checkStrLen($_POST['code'],4,4)?Tool::alertBack('警告:驗 證 碼長度不得 "小於" 或 "大於" 4 !'):true;

      if($_SESSION['code']!=$_POST['code']) Tool::alertBack('驗 證 碼 錯 誤!');

      $user = Validate::checkStrLen($_POST['username'],2,20)?Tool::alertBack('警告:會員名稱長度不得小於 2 或 大於 20'):$_POST['username'];

      $pass = Validate::checkStrLen($_POST['pass'],6,15)?Tool::alertBack('警告:密碼長度不得小於 6 或 大於 15'):$_POST['pass'];

      $pass = sha1($pass);

      $this->model->user = $user;

      $this->model->pass = $pass;

      //--- 驗證成功，生成 cookie ---

      if(!$obj = $this->model->getOneUser() ){

        Tool::alertBack('您 輸 入 的 帳 號 或 密 碼 錯 誤 或 尚 未 註 冊!'); 

      }else{
        // cookie path 統一為 '/'
        setcookie('cookied', $obj->id, time()+365*24*60*60, '/');//會員ID
        setcookie('cook', $obj->nickname, time()+365*24*60*60, '/'); //會員名
        setcookie('csk', $obj->ckey, time()+365*24*60*60, '/'); //驗證序號
        setcookie('mv', $obj->level, time()+365*24*60*60, '/'); //等級編號
        setcookie('ln', $obj->name, time()+365*24*60*60, '/'); //等級名
        session_destroy(); //清除驗證碼 session
        $this->model->id = $obj->id; //會員編號
        $this->model->userLogin() ? true:Tool::alertBack('警告：登入失敗！可能是系統異常！');
      }
      $this->loginWelcome($obj); //登入歡迎
    }
  }



  /** 登入 */

  private function logout(){

    setcookie('cookied', '', -time(), '/');//會員ID

    setcookie('cook', '', -time(), '/'); //會員名

    setcookie('csk', '', -time(), '/'); //驗證序號

    setcookie('mv', '', -time(), '/'); //等級編號

    setcookie('ln','', -time(), '/'); //等級名
    
    Tool::alertLocation('user_login.php?act=login');

  }

  /** 登入歡迎 
   * @param Object $obj 會員資料物件
  */
  
  private function loginWelcome($obj){
    if($obj->level >= 4) Tool::alertLocation('./loginWelcome.php');
    else Tool::alertLocation('index.php','歡 迎 回 來 '.$obj->user.' !');
  }
}



?>