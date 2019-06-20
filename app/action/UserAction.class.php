<?php
/**
 * 業務流程控制器類 -- 後台會員編輯系統 -- 業務邏輯
 */
  class UserAction{
    private $model;       //存放會員模型實例化物件
    private $user_id;     //會員 id
    private $userListObj; //會員資料 物件
    private $oneUserObj;  //會員資料 物件
    private $validate;    //驗證類實例
    private $originPhoto; //原本的頭像路徑
    
    public function __construct(){
      //驗證登入
      Validate::checkUserLogin()? Tool::alertBack('請 先 登 入！'):true;
      $this->model = new UserModel;
      $this->validate = new Validate();
      $this->path ='uploaded/self_photo';  //設定上傳路徑

    }

    public function action(){

      switch(@$_GET['act']){

        case 'show':
  
          $this->show();
  
          break;
  
        case 'update':
  
          $this->update();
  
          break;
  
        case 'delete':
  
          $this->delete();
  
          break;
  
        default:
          Tool::alertBack('非 法 操 作！');
          break;
      }
    }

    /** 顯示所有會員資料物件 */
    private function show(){
      if($obj = $this->model->getAllUser()){
        $this->userListObj = $obj;
      }else return false;
    }

    /** 更新會員資料物件 */
    private function update(){

      if(isset($_POST['send'])){
        $this->user_exist();//驗證此會員是否存在
        //驗證是否為會員本人
        $is_Self = Validate::checkUserSelf($_COOKIE['cookied'],$_COOKIE["csk"],$_GET['id']);
        //若非本人，則驗證是否為管理員以及是否有權限編輯
        if($is_Self) $this->checkManagerLevel(6); //驗證管理員等級
        //--- 表單資料驗證 ---
        Validate::checkStrLen($_POST['nickname'],2,10)? Tool::errorMsg('暱 稱 不 得 小 於 2 字 或 大於 10 字'):false;
        Validate::checkEmail($_POST['email'])? Tool::errorMsg('email 格 式 錯 誤！'):false;;
        Validate::checkPhone($_POST['phone'])? Tool::errorMsg('手 機 號 格 式 錯 誤！'):false;;
        //strip_tags() 用來刪除 Html 標籤
        $intro = html_entity_decode($_POST['intro']);
        Validate::checkStrLen(strip_tags($intro),5,500)? Tool::errorMsg('簡 介 不 得 小 於 5 字 或 大於 500 字'):false;
        $this->model->user_id = $_COOKIE['cookied'];//編輯者的 id
        $this->model->id = $_GET['id'];//被編輯的會員 id
        $this->model->nickname = strip_tags($_POST['nickname']);  //暱稱 
        $this->model->email = $_POST['email'];       //信箱
        $this->model->phone = $_POST['phone'];      //手機號
        $this->model->intro = $intro;      //簡介 
        //--- 取得上傳圖片路徑 ---
        if(isset($_POST['newPhoto']) && isset($_POST['originPhoto'])){
          $imgData = json_decode($_POST['newPhoto']); //從JSON格式轉回陣列或是字串
          $this->originPhoto = $_POST['originPhoto'];
          $this->updateImg($imgData); //*更新圖片
        }

        if($this->model->updateProfile()){
          if(!empty($this->originPhoto)){ //有舊檔案才執行
            if(!file_exists($this->originPhoto)) Tool::errorMsg('警告: 原 本 的 頭 像 遺 失!');
            unset($this->originPhoto); // *清除舊的.xml 檔
          }
          echo $_GET['id'];//跟新成功則回傳 會員 id
        }else{
          Tool::errorMsg('更 新 失 敗，可 能 是 系 統 異 常！');
        }
      }
     
    }

    /** 取得 所有會員 資料列表 */
    public function getUserListObj(){
      return $this->userListObj;
    }

    /** 取得 1 位 會員資料列表 */
    public function getOneUserObj(){
      return $this->oneUserObj;
    }

    /** 顯示 1 位會員 的會員中心資料 */
    public function showUserCenter(){
      if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $this->model->id = round($_GET['id']);//無條件捨去，確保為 id 整數型態
        $obj = $this->model->userCenter();
        $obj ? true : Tool::alertBack('此會員不存在！');
        return $obj;
      }else Tool::alertBack('非法操作！');
    }

    /** 顯示 1 位會員 的資料 */
    public function getUserProfile(){
      if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $this->model->id = round($_GET['id']);//無條件捨去，確保為 id 整數型態
        $obj = $this->model->getProfile();
        $obj ? true : Tool::alertBack('此會員不存在！');
        return $obj;
      }else Tool::alertBack('非法操作！');
    }

    /** 驗證 1 位會員是否存在 
     * @return Object 1 位 會員資料
    */
    private function user_exist(){
      if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $this->model->id = round($_GET['id']);//無條件捨去，確保為 id 整數型態
        $obj = $this->model->oneUserExist();
        $obj ? true : Tool::alertBack('此會員不存在！');
        return $obj;
      }else Tool::alertBack('非法操作！');
    }

    /** 驗證管理員等級 */
    private function checkManagerLevel($privilege = 5){
      //--- 驗證身分 防止假造 cookie---
      $this->user_id = $_COOKIE['cookied'];
      $ckey = $_COOKIE["csk"];
      $level = $_COOKIE["mv"];
      $levelName = $_COOKIE["ln"];
      Validate::checkManagerValidate($this->user_id,$ckey,$level,$levelName,$privilege)?Tool::errorMsg(' 無 權 操 作！'):true;
    }

    /** 更新頭像*/
    private function updateImg($imgData){
      $upimg = new UpImage();
      $img_src = $upimg->createImgFolder($this->path); //生成圖片路徑,檔案數預設 1 個
      //--- 處理頭像 ---

      if(empty($imgData)){ //未更改頭像則沿用舊的
        $this->model->photo = $this->originPhoto;
        $img_src[0] = null; //索引 0 頭像需要給空值避免，步驟圖片讀取錯誤
        return; //直接返回不需要上傳
      }else{
        $this->model->photo = $img_src[0]; //索引 0 頭像路徑
       
      }
      //--- 需有頭像資源才上傳 ---
      if(!empty($imgData)){
        $upimg->uploadImg($imgData,$img_src);
      }
    }

  }

?>