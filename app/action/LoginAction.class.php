<?php

/**

 * 業務流程控制器類 -- 後台管理員 註冊、登入、登出、查詢 系統 -- 業務邏輯

 */

class LoginAction{

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

        Validate::checkManagerLogin() ? true : Tool::alertLocation('index.php');

    }

  }


  /** 

   * 註冊管理員 

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

    Validate::checkStrLen($_POST['code'],4,4) ? Tool::alertBack('警告: 驗 證 碼 錯 誤!'):true;

    $_SESSION['code'] != $_POST['code'] ? Tool::alertBack('驗 證 碼 錯 誤!'):true;

    $user = Validate::checkStrLen($_POST['username'],2,20)?Tool::alertBack('警告:名 稱 錯 誤!'):$_POST['username'];

    $pass = Validate::checkStrLen($_POST['pass'],6,15)?Tool::alertBack('警告: 密 碼 錯 誤!'):$_POST['pass'];

    $pass = sha1($pass);

    $this->model->user = $user;

    $this->model->pass = $pass;

    //--- 驗證成功，生成 cookie ---
    if(!$obj = $this->model->getOneUser() ){

      Tool::alertBack('您 輸 入 的 帳 號 或 密 碼 錯 誤 或 尚 未 註 冊!'); 

    }else{
      
      switch($obj->level){//權限判斷
        
        case '1':
        case '2': Tool::alertLocation('../','無 權 訪 問 !'); break;
        case '3': 
        case '4':
        case '5':
        case '6':break; 
        default: Tool::alertLocation('../','無 權 訪 問!'); break;

      }

      $this->frontLogout();//登出前台會員

      setcookie('key', $obj->id, time()+365*24*60*60, '/'); // 管理員編號

      setcookie('manager', $obj->nickname ,time()+365*24*60*60, '/'); //管理員姓名

      setcookie('ms', $obj->ckey, time()+365*24*60*60, '/'); //驗證序號

      setcookie('mv', $obj->level, time()+365*24*60*60, '/'); //等級編號

      setcookie('ln', $obj->name, time()+365*24*60*60, '/'); //等級名

      session_destroy(); //清除驗證碼 session

    }

    Tool::alertLocation('backmanagesystem.php',$obj->user.' 歡 迎 使 用!');

    }

  }


  /** 登出 
   * @param Boolean $user true = 已登入前台
  */
  private function logout(){
    // cookie path 統一為 '/'
    setcookie('key', '', -time(), '/' );

    setcookie('manager', '', -time(), '/');

    setcookie('ms', '', -time(), '/');
    
    setcookie('mv', '', -time(), '/');

    setcookie('ln', '', -time(), '/');

    Tool::alertLocation('login.php');
  }

  
  /** 前台會員登出，避免操作 COOKIE 錯誤 */
  private function frontLogout(){

    if(isset($_COOKIE['cookied']) && isset($_COOKIE['cook']) && isset($_COOKIE['csk'])){
      
      echo"<script>alert('前 台 會 員 已 登 出！');</script>";

      setcookie('cookied', '', -time(), '/');

      setcookie('cook', '', -time(), '/');
  
      setcookie('csk', '', -time(), '/');

    }

  }


}



?>