<?php
/**
 * 業務流程控制器類 --食譜增刪改查 -- 業務邏輯
 */
class TrialAction{
  private $user_id;     //使用者 id
  private $user_name;   //使用者名稱
  private $foodName;    //材料名稱
  private $finish_img;  //成品圖片地址
  private $serve_unit;  //份量
  private $qt;          //材料數量單位
  private $filename;    //調理方式 xml 檔 路徑
  private $model;       //存放 model 資源物件
  private $recipeObj;   //存放 食譜資料 物件
  private $step_img;    //存放調理步驟圖片資料
  public $trial;        //試作資料 物件
  public $edit;        //試作編輯 物件

  public function __construct(){
    $this->model = new RecipeModel();
  }

  /**
  * 流程控制器
  * @return void
  */
  public function action(){

    //驗證登入!
    if(Validate::checkUserLogin()){
        echo 'UNLogin';
    }
    
    switch(@$_GET['act']){
      case 'show':
        $this->show();
      break;

      case 'update':
        $this->update();
      break;

      case 'add':
        $this->add();
      break;

      case 'delete':
        $this->delete();
      break;
    }

  }

  /** 顯示 試作內容 */
  public function getTrial(){
    if(@$_GET['id']) $this->model->follow = $_GET['id'];
    $this->model->user_id = @$_COOKIE['cookied'];
    $obj = $this->model->getTrialContent();
    return $obj;
  }

  /** 顯示 試作列表快照 */
  private function show(){
    if(isset($_GET['follow'])) {

      if($this->checkRecipe()){//驗證 原創食譜 是否存在
        echo "<script>alert('Oops! 原 創 食 譜 不 存 在！');window.close();</script>";
      } 
      $this->model->follow = $_GET['follow'];
      if(!$this->trial = $this->model->getTrialSnapShot()){
        echo "<script>alert('Oops! 無 任 何 試 作 資 料！');window.close();</script>";
      }
      
    }else{
      echo "<script>alert('非 法 操 作!');window.close();</script>";
    }
  }

  /** 更新食譜內容 */
  private function update(){

    if(isset($_GET['id'])) {

      if($this->checkRecipe()){//驗證 原創食譜 是否存在
        echo "<script>alert('Oops! 原 創 食 譜 不 存 在！');window.close();</script>";
      } 
      $this->model->id = $_GET['id'];
      if(!$this->edit = $this->model->getTrialSnapShot('id')){
        echo "<script>alert('Oops! 無 任 何 試 作 資 料！');window.close();</script>";
      }

    }
    
    if(isset($_POST['send'])){
      $this->checkRecipe(); //驗證 原創食譜 是否存在 
      $this->user_id = $_COOKIE['cookied'];
      $ckey = $_COOKIE['csk'];
      Validate::checkUserValidate($this->user_id,$ckey)?Tool::errorMsg('會 員 身 分 驗 證 失 敗!'):false;

      //--- 表單資料驗證 ---
      Validate::checkStrLen($_POST['summary'],0,500)? Tool::errorMsg(' 心 得 字 數 不 得 大 於 500 個'):false;
      $this->model->summary = $_POST['summary'];       //試作心得
      $this->model->id = $_POST['id'];
      //--刪除 原始 圖片
      if(isset($_POST['origiImg']) && !empty($_POST['origiImg'])){
        if(!file_exists($_POST['origiImg'])) Tool::errorMsg('警告: 原 始 照 片 不 存 在!');
        unset($_POST['origiImg']);
      }

      //--- 取得上傳圖片路徑 ---
      if(isset($_POST['img'])) $imgData = $_POST['img']; //將 JSON 文字轉為 陣列
      if(!empty($imgData)){
        $path ='uploaded/pics';  //上傳路徑
        $upimg = new UpImage();
        $img_src = $upimg->createImgFolder($path); //*索引值 0  固定是成品照片位址
        $this->model->finish_img = $img_src[0];           //索引值 0 是成品圖片地址
      }else{
        Tool::errorMsg('警告: 成 品 照 尚 未 修 改!'); 
      }

      //--上一個步驟成功後，將 試作 資料寫入資料庫 ---
      if($this->model->updateTrial()){
        //--- 上一個步驟成功後，才上傳圖片 ---
        $upimg->uploadImg($imgData,$img_src);
        echo 'SUCCESS'; //回 傳 成功信息
      }else {
        Tool::errorMsg('警告: 食 譜 新 增 失 敗! 可 能 是 系 統 異 常，請 聯 繫 管 理 員。!');
      }  
    }
    
  }

  /** 新增食譜 */
  private function add(){
    if(isset($_POST['send'])){

      $this->checkRecipe(); //驗證 原創食譜 是否存在 
      $this->user_id = $_COOKIE['cookied'];
      $ckey = $_COOKIE['csk'];
      Validate::checkUserValidate($this->user_id,$ckey)?Tool::errorMsg('會 員 身 分 驗 證 失 敗!'):false;
      $this->model->user_id = $this->user_id;
      
      //--- 表單資料驗證 ---
      Validate::checkStrLen($_POST['title'],2,20)? Tool::errorMsg('標 題 字 數 不 得 小 於 2 個 或 大於 20 個'):false;
      Validate::checkStrLen($_POST['summary'],0,500)? Tool::errorMsg(' 心 得 字 數 不 得 大 於 500 個'):false;
      $this->model->dish_name = $_POST['title'];       //食譜名稱 
      $this->model->summary = $_POST['summary'];       //試作心得

      //--- 取得上傳圖片路徑 ---
      if(isset($_POST['img'])) $imgData = $_POST['img']; //將 JSON 文字轉為 陣列
      if(!empty($imgData)){
        $path ='uploaded/pics';  //上傳路徑
        $upimg = new UpImage();
        $img_src = $upimg->createImgFolder($path); //*索引值 0  固定是成品照片位址
        $this->model->finish_img = $img_src[0];           //索引值 0 是成品圖片地址
      }else{
        Tool::errorMsg('警告: 成 品 照 尚 未 新 增!'); 
      }

      //--上一個步驟成功後，將 試作 資料寫入資料庫 ---
      if($this->model->addTrial()){
        //--- 上一個步驟成功後，才上傳圖片 ---
        $upimg->uploadImg($imgData,$img_src);
        echo 'SUCCESS'; //回 傳 成功信息
      }else {
        Tool::errorMsg('警告: 食 譜 新 增 失 敗! 可 能 是 系 統 異 常，請 聯 繫 管 理 員。!');
      }

    }
  }

  /** 刪除食譜 */
  private function delete(){
    
  }

  /** 驗證 原創食譜 是否存在 */
  private function checkRecipe(){

    if( isset($_GET['follow']) && is_numeric(@$_GET['follow']) || isset($_POST['id']) && is_numeric(@$_POST['id'])){

      $this->model->follow = round(@$_GET['follow'],0);//無條件捨去，確保為 id 整數型態
      $this->model->id = round(@$_POST['id'],0);//無條件捨去，確保為 id 整數型態
      //從資料庫獲取食譜資料

      if(!$this->model->checkRecipe()) {
        Tool::errorMsg("<h1 style='text-align:center;'>無 試 作 食 譜!</h1>");
        return false;
      }

    }else{
      Tool::errorMsg('警告: 非 法 操 作!');
      return false;
    }


  }

  /**
  * 獲取試作人數
  * @return Int
  */
  public function getTrialTotal(){
    return $this->model->getTrialTotal();
  }
  
}

?>