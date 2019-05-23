<?php
/**
 * 業務流程控制器類 --食譜增刪改查 -- 業務邏輯
 */
class RecipeAction{
  private $user_id;     //使用者 id
  private $user_name;   //使用者名稱
  private $foodName;    //材料名稱
  private $finish_img;  //成品圖片地址
  private $serve_unit;  //份量
  private $qt;          //材料數量單位
  private $filename;    //調理方式 xml 檔 路徑
  private $model;       //存放 model 資源物件
  private $recipeObj;    //存放 食譜資料 物件
  private $step_img;     //存放調理步驟圖片資料
  public $resultMsg;       // Ajax 傳值結果 String
  
  public function __construct(){
    $this->model = new RecipeModel();
    $this->result = null;
  }

  /**
  * 流程控制器
  * @return void
  */
  public function action(){

    //驗證登入!
    if(!Validate::checkUserLogin()){
        echo 'UNLogin';
    }
    
    switch(@$_GET['act']){
      case 'show':
        $this->show();
        break;

      case 'update':
        # code...
        break;

      case 'add':
        $this->add();
        break;
    }

  }

  /** 顯示 食譜列表快照 */
  private function show(){
  }

  /** 更新食譜內容 */
  private function update(){
    
  }

  /** 新增食譜 */
  private function add(){
    if(isset($_POST['send'])){
      // echo('Received');
      //--- 驗證身分 防止假造 cookie---
      $this->user_id = $_COOKIE['cookied'];
      $ckey = $_COOKIE['csk'];
      $validate = new Validate();
      $validate->checkUserValidate($this->user_id,$ckey)?Tool::errorMsg('會 員 身 分 驗 證 失 敗!'):false;
      $this->model->user_id = $this->user_id;

      //--- 取得上傳圖片路徑 ---
      if(isset($_POST['img']) && count($_POST['img']) > 0){
        $imgData = json_decode($_POST['img']); //將 JSON 文字轉為 陣列
        $imglen = count($imgData); //圖檔個數
        $path ='uploaded/pics';  //上傳路徑
        $upimg = new UpImage();
        $img_src = $upimg->createImgFolder($path,$imglen); //*索引值 0  固定是成品照片位址
        $this->model->finish_img = $img_src[0];           //索引值 0 是成品圖片地址
        $this->step_img = $img_src;                      //步驟說明圖片位址 從索引值 1 開始
      }else{
        Tool::errorMsg('警告: 成 品 照 尚 未 新 增!'); 
      }
      
      //--- 表單資料驗證 ---
      Validate::checkStrLen($_POST['title'],2,20)? Tool::errorMsg('標 題 字 數 不 得 小 於 2 個 或 大於 20 個'):false;
      Validate::checkStrLen($_POST['summary'],0,500)? Tool::errorMsg(' 字 數 不 得 大 於 500 個'):false;
      $this->model->dish_name = $_POST['title'];       //食譜名稱 
      $this->model->summary = $_POST['summary'];       //烹飪心得
      $this->foodName = json_decode($_POST['food_name']); //材料名 ，將 JSON 文字轉為 陣列
      $this->qt = json_decode($_POST['food_unit']); //材料數量單位 ，將 JSON 文字轉為 陣列
      $this->step_text = json_decode($_POST['step']); //步驟說明文字 ，將 JSON 文字轉為 陣列
      if($_POST['draft'] === 'on')//發布狀態選擇
        $this->state = 1; //草稿
      else{
        $this->state = 0; //發布
      }

      //--- 建立食譜準備材料、調理步驟 .xml 檔 ---
      $this->createXMLFileName(); //create step.xml path name
      $this->createRecipeXML();
      //--上一個步驟成功後，將食譜資料寫入資料庫 ---
      $this->model->step_xml = $this->filename; //give step xml path to model property
      $this->model->state = $this->state;
      $tableName = 'recipes';
      $next_id = $this->model->nextId($tableName);//*** 重要:必須先取得預計產生的新 id ，以供新增食譜後跳轉至 recipe_detail.php 頁面用
      if($this->model->addRecipe()){
        //--- 上一個步驟成功後，才上傳圖片 ---
        $upimg->uploadImg($imgData,$this->step_img);
        echo $next_id; //回 傳 食 譜 id
        
      }else {
        Tool::errorMsg('警告: 食 譜 新 增 失 敗! 可 能 是 系 統 異 常，請 聯 繫 管 理 員。!');
      }
    }
  }

  /** 刪除食譜 */
  private function delete(){
    
  }

  /** 生成 食譜資料 .xml 檔案名稱*/
  private function createXMLFileName(){
    $this->filename = 'recipe_detail/'.$this->user_id.'-'.date('Ymd').'-'.date('His',time()).'.xml';    //檔名、路徑
  }

  /** 建立食譜資料 .xml 檔 ， 請參考舊版*/
  private function createRecipeXML(){

    //--- 材料、步驟寫入 .xml ---
    $xml='<?xml version="1.0" encoding="UTF-8"?>';
    $xml.='<detail>';
    $xml.='<ingredient>';   //準備材料 轉譯成 xml 格式
    $count = 0; //迴圈計數用

    foreach($this->foodName as $key => $value){//取得材料名、數量
      
      if(!empty($this->qt[$key])){ //只要 材料名為空值
        if(empty($value)){
         Validate::checkStrLen($value,1,20)? Tool::errorMsg("警告: 第 {$k}. 列 的 材 料 尚 未 位 填 寫!") : false;
        }
      }
      else{
        if(empty($value)) continue;
      }
      $k = $key+1; //列數計算
      Validate::checkStrLen($value,1,20)? Tool::errorMsg("警告: 第 {$k}. 列 的 材 料 字數不得 小於 2 個或大於 20 個 ! 請斟酌字數。") : false;
      Validate::checkStrLen($this->qt[$key],1,10)? Tool::errorMsg("警告: 第 {$k}. 列的材料\"數量\" 字數不得 小於 1 個或大於 10 個 ! 請斟酌字數。") : false;
      $xml.= "<qt num=\"{$this->qt[$key]}\"><food>{$value}</food></qt>";
      $count++; 
    }

    $count < 1 ?  Tool::errorMsg("警告: \"準備材料與數量\" 欄位至少須 完整的 填寫 一列!") : false; //欄位上 1 列 都沒填寫，則警告
    
    $xml.='</ingredient>';

    //--- 調理步驟 寫入 xml --
    $count = 0; //迴圈計數用
    $img_key = 1; //計算步驟圖片索引，從 索引 1 為步驟圖片位址
    $xml.='<step>';

    foreach($this->step_text as $key => $value){

      if( empty($value) ) continue;
      
      $xml.="<img src=\"{$this->step_img[$img_key]}\">"; // 從索引值 1 開始是步驟照片 $key = 0 裡是放成品照
      
      $k = $key+1; //步驟列數計算
      Validate::checkStrLen($value,2,200)? Tool::errorMsg("欄位錯誤: 步驟 {$k}. 字數不得 小於 2 個 或 大於 200 個 ! 請斟酌字數。") : false;
      
      $xml.="<p>{$value}</p>"; 
      $xml.="</img>";

      $count++;
      $img_key++;    
    }
   
    $count < 1 ? Tool::errorMsg("警告: 調理步驟\"文字\"說明至少須填寫 一列!") : false;
    $xml.='</step>';
    $xml.='</detail>';
    $xml_open = fopen($this->filename,'w+'); //開啟資料夾，若不存在則建立
    flock($xml_open,LOCK_EX);               // acquire an exclusive lock 排他鎖定文件，只有自己可寫入
    
    if(!fwrite($xml_open,$xml)){
      exit('**** File Was Failed to Write ! *****');
    }else{
      fflush($xml_open);                   // flush output buffer before releasing the lock
      flock($xml_open, LOCK_UN);          // release the lock
    }

    fclose($xml_open); //關閉文件資源

  }
}

?>