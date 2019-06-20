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
  private $recipeList;   //存放 食譜資料 物件
  private $step_img;    //存放調理步驟圖片資料
  private $contentObj;  //存放 食譜內容 物件
  private $ingredient;  //存放 食譜材料
  private $step;        //存放 食譜步驟
  private $orginXML;    //存放原有的 .xml 路徑
  private $originFinishImg; //存放原有成品圖片的路徑
  private $originStep;  //存放原有步驟圖片的路徑
  private $path;        //上傳路徑
  private $upimg;       //存放 上傳圖片類別的 實例化 物件
  private $validate;    //存放 驗證類的實力化 物件

  public function __construct(){
    //驗證登入!
    Validate::checkUserLogin()? Tool::alertBack('請 先 登 入！'):true;
    $this->model = new RecipeModel();
    $this->upimg = new UpImage();
    $this->validate = new Validate();
    $this->path ='uploaded/pics';  //設定上傳路徑
  }

  /**
  * 流程控制器
  * @return void
  */
  public function action(){
    
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

      default: 
        Tool::alertBack('警 告 非 法 操 作!');
        break;
    }

  }

  /** 顯示 食譜列表 */
  private function show(){

    $this->recipeList = $this->model->getSnapShot();//取得食譜內容
  }

  /** 更新食譜內容 */
  private function update(){

    //---驗證 原創食譜 是否存在---
    if( isset($_GET['id']) && is_numeric(@$_GET['id'])){
      $this->checkRecipe($_GET['id']) ? true: Tool::errorMsg('Oops! 查 無 此 食 譜!');
    }else{
      Tool::alertBack('警告: 非 法 操 作!');
    }
    //--- 食譜詳情 ---
    $this->contentObj = $this->model->getDetails(); //取得食譜內容 
    $this->readDetailsXML();//取得 材料、步驟 .xml 成物件陣列
    $this->orginXML = $this->contentObj->step_xml; //取得原本的 .xml 路徑
    $this->originFinishImg = $this->contentObj->finish_img; //取得成品照路徑
    $this->getOringinStepSrc();  //取得步驟圖片路徑

    //--- 更新食譜 ---
    if(isset($_POST['send']) && isset($_POST['id'])){
      //--- 驗證身分 防止假造 cookie---
      $this->user_id = $_COOKIE['cookied'];
      $ckey = $_COOKIE['csk'];
      $level = $_COOKIE['mv'];
      $levelName = $_COOKIE['ln'];
      Validate::checkUserValidate($this->user_id,$ckey,$level,$levelName)?Tool::errorMsg('會 員 身 分 驗 證 失 敗!'):false;
      //--- //驗證是否是作者本人 或 管理員權限是否足夠、 防止假造 cookie ---
      $this->checkRecipe($_POST['id']);//驗證 原創食譜 是否存在
      validate::checkRecipeAuthor($_POST['id'],$this->user_id,$ckey,$level)?Tool::errorMsg('警告: 無 權 操 作!'):false;
      $this->model->id = $_POST['id'];
      $this->model->user_id = $this->user_id;
      //--- 表單資料驗證 ---
      Validate::checkStrLen($_POST['title'],2,20)? Tool::errorMsg('標 題 字 數 不 得 小 於 2 個 或 大於 20 個'):false;
      Validate::checkStrLen($_POST['summary'],0,500)? Tool::errorMsg(' 心 得 字 數 不 得 大 於 500 個'):false;
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
      $this->model->state = $this->state;

      //--- 取得上傳圖片路徑 ---
      if(isset($_POST['img'])) $imgData = json_decode($_POST['img']); //將 JSON 文字轉為 陣列
      $this->updateImg($imgData); //*更新圖片

      //--- 新建食譜準備材料、調理步驟 .xml 檔 ---
      if(!file_exists($this->orginXML)) Tool::errorMsg('警告: 原本的 .xml 檔 案 遺 失!');
      unset($this->orginXML); // *清除舊的.xml 檔
      $this->createXMLFileName();
      $this->createRecipeXML();
      $this->model->step_xml = $this->filename; //give step xml path to model property

      //--上一個步驟成功後，更新食譜資料 ---
      if($this->model->updateRecipe()){
        echo $_POST['id']; //更 新 成 功 回 傳 食 譜 id
      }else {
        Tool::errorMsg('警告: 食 譜 更 新 失 敗! 可 能 是 系 統 異 常，請 聯 繫 管 理 員。!');
      }
    }
  }

  /** 新增食譜 */
  private function add(){
    if(isset($_POST['send'])){
      //--- 驗證身分 防止假造 cookie---
      $this->user_id = $_COOKIE['cookied'];
      $ckey = $_COOKIE['csk'];
      $level = $_COOKIE['mv'];
      $levelName = $_COOKIE['ln'];
      Validate::checkUserValidate($this->user_id,$ckey,$level,$levelName)?Tool::errorMsg('會 員 身 分 驗 證 失 敗!'):false;
      $this->model->user_id = $this->user_id;
      
      //--- 表單資料驗證 ---
      Validate::checkStrLen($_POST['title'],2,20)? Tool::errorMsg('標 題 字 數 不 得 小 於 2 個 或 大於 20 個'):false;
      Validate::checkStrLen($_POST['summary'],0,500)? Tool::errorMsg(' 心 得 字 數 不 得 大 於 500 個'):false;
      $this->model->dish_name = $_POST['title'];       //食譜名稱 
      $this->model->summary = strip_tags($_POST['summary']);       //烹飪心得
      $this->foodName = json_decode($_POST['food_name']); //材料名 ，將 JSON 文字轉為 陣列
      $this->qt = json_decode($_POST['food_unit']); //材料數量單位 ，將 JSON 文字轉為 陣列
      $this->step_text = json_decode($_POST['step']); //步驟說明文字 ，將 JSON 文字轉為 陣列
      if($_POST['draft'] === 'on')//發布狀態選擇
        $this->state = 1; //草稿
      else{
        $this->state = 0; //發布
      }

      //--- 取得上傳圖片路徑 ---
      if(isset($_POST['img'])) $imgData = json_decode($_POST['img']); //將 JSON 文字轉為 陣列
      if(!empty($imgData[0])){
        $imglen = count($imgData); //圖檔個數
        $img_src = $this->upimg->createImgFolder($this->path,$imglen); //*索引值 0  固定是成品照片位址
        $this->model->finish_img = $img_src[0];           //索引值 0 是成品圖片地址
        $this->step_img = $img_src;                      //步驟說明圖片位址 從索引值 1 開始
      }else{
        Tool::errorMsg('警告: 成 品 照 尚 未 新 增!'); 
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
        $this->upimg->uploadImg($imgData,$this->step_img);
        echo $next_id; //回 傳 食 譜 id
        
      }else {
        Tool::errorMsg('警告: 食 譜 新 增 失 敗! 可 能 是 系 統 異 常，請 聯 繫 管 理 員。!');
      }
    }
  }

  /** 刪除食譜 */
  private function delete(){
    if(isset($_POST['delete']) && isset($_POST['id'])) {
      if(isset($_POST['id']) && is_numeric(@$_POST['id'])){
        $this->checkRecipe($_POST['id']) ? true: Tool::alertBack('Oops! 查 無 此 食 譜!');
      }else{
        Tool::alertBack('警告: 非 法 操 作!');
      }
      /**驗證是否作者本人 或 管理人員權限足夠 */
      $this->validate->checkRecipeAuthor($_POST['id'],$_COOKIE['cookied'],$_COOKIE['csk'], $_COOKIE['mv'], $_COOKIE['ln'])? Tool::alertBack('警告: 無 權 操 作!') : false;
      $this->model->id = $_POST['id'];
      $this->model->deleteRecipe()?Tool::alertLocation('./','成 功 刪 除!'):Tool::alertBack('警告: 刪 除 失 敗! 可 能 是 系 統 異 常!') ;
    }
    Tool::errorMsg('刪 除 失 敗!');
  }

  /** 生成 食譜資料 .xml 檔案名稱*/
  private function createXMLFileName(){
    $this->filename = 'recipe_detail/'.$this->user_id.'-'.date('Ymd').'-'.date('His',time()).'.xml';    //檔名、路徑
  }

  /** 建立食譜資料 .xml 檔 */
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
      if(!empty($this->step_img[$img_key]) )
        $xml.="<img src=\"{$this->step_img[$img_key]}\">"; // 從索引值 1 開始是步驟照片 $key = 0 裡是放成品照
      else
        $xml.="<img>"; // 從索引值 1 開始是步驟照片 $key = 0 裡是放成品照

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
    $xml_open = fopen($this->filename,'w+'); //開啟檔案，若不存在則建立
    flock($xml_open,LOCK_EX);               // acquire an exclusive lock 排他鎖定文件，只有自己可寫入
    
    if(!fwrite($xml_open,$xml)){
      exit('**** File Was Failed to Write ! *****');
    }else{
      fflush($xml_open);                   // flush output buffer before releasing the lock
      flock($xml_open, LOCK_UN);          // release the lock
    }

    fclose($xml_open); //關閉文件資源
  }

  /** 更新圖片*/
  private function updateImg($imgData){

    //處理陣列最後一個的空元素 * 從 xml 取得的步驟圖片 固定最後一個都是空元素
    //For PHP <= 7.3.0 :
    if (! function_exists("array_key_last")) {
        function array_key_last($array) {
            if (!is_array($array) || empty($array)) {
                return NULL;
            }
            
            return array_keys($array)[count($array)-1];
        }
    }
    $isEpmty = array_key_last($this->originStep);
    if(empty($this->originStep[$isEpmty])) unset($this->originStep[$isEpmty]);
    
    $imglen = count($imgData); //計算圖檔個數(陣列長度)
    $img_src = $this->upimg->createImgFolder($this->path,$imglen); //生成圖片路徑

    //--- 處理成品照 ---
    if(empty($imgData[0])){ //未更改成品照沿用舊的
      $this->model->finish_img = $this->originFinishImg;
      $img_src[0] = null; //索引 0 成品照需要給空值避免，步驟圖片讀取錯誤
    }else{
      $this->model->finish_img = $img_src[0]; //索引 0 成品照
    }
    //--- 處理步驟圖片 ---
    for($i = 1; $i < $imglen; $i++){ //索引 1 開始是步驟圖片
      if(empty($imgData[$i])) $img_src[$i] = $this->originStep[$i-1]; // 若是空值則沿用舊照片
    }
    //--- 存放圖片路徑 ---
    $this->step_img = $img_src;    //步驟說明圖片位址 從索引值 1 開始 (*不強制要有步驟圖片)
 
    //--- 需有上傳圖片才上傳 ---
    if(!empty($imgData)){
      $this->upimg->uploadImg($imgData,$this->step_img);
    }
  }

  /** 驗證 原創食譜 是否存在 */
  private function checkRecipe($id){
      $this->model->id = round($id);//無條件捨去，確保為 id 整數型態
      //從資料庫獲取食譜資料
      return !!$this->model->checkRecipe() ? true: false;
  }

  /**
   * 讀取 準備材料、調理步驟 xml 檔案
   * @param Object Array $html 物件陣列
   */
  private function readDetailsXML(){
    
    $xm_dir = $this->contentObj->step_xml;  //取得 xml 路徑

    if(file_exists($xm_dir)){

      $sxml = simplexml_load_file($xm_dir); //載入 XML 檔案
      $this->ingredient = array(); //存放材料名稱、數量單位
      $this->step = array(); //存放調理步驟

      foreach($sxml as $value){ //取出ingredient 和 step 物件陣列 
        if($value->getname() == 'ingredient'){  
          $this->ingredient[] = $value; //取出 ingredient  物件陣列 
        }else{
          $this->step[]= $value;  //取出 step 物件陣列  -> img 物件陣列
        }
      }

    }else {
      exit('Failed to open: '.$xm_dir);
    }
  }

  /**
   * 讀取 準備材料、調理步驟 xml 檔案中的原有圖片路徑
   * @param Object Array $html 物件陣列
   */
  private function getOringinStepSrc(){
    $tmp = null; //暫存
    $this->originStep = array();

    // ====== 取得 步驟 原有圖片路徑======
    foreach($this->step as $value){
      foreach($value as $sv){
        $tmp .= $sv->attributes().','; //暫存成字符串
      }
    }
    $this->originStep = explode(',',$tmp);//字符串分割成陣列
}

  /** 取得食譜內容 */
  public function getContentObj(){
    return $this->contentObj;
  }
  /** 取得食譜列表內容 */
  public function getListObj(){
    return $this->recipeList;
  }
  /** 取得食譜材料 */
  public function getIngredient(){
    return $this->ingredient;
  }

  /** 取得食譜步驟 */
  public function getStep(){
    return $this->step;
  }

}

?>