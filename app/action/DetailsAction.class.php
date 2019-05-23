<?php

/**

 * 業務流程控制器類 --食譜詳情 -- 業務邏輯

 */

class DetailsAction{

  private $id;            //存放食譜 ID

  private $trial_object;  //試作食譜內容 物件

  private $ingredient;    //存放準備材料的 html 格式

  private $step;          //存放調理步驟的 html 格式

  private $model;         //存放 model 物件

  private $finish_img;    //成品圖片地址(或會員跟著做)

  private $act;           //存放 action 物件

  public $detail_object; //詳細內容 物件

  public $comment_object; //留言內容 物件

  public $commentNums;    //存放留言數

  public $reply_object;   //存放回覆 物件


  public function __construct(){

    $this->model = new RecipeModel();
    $this->act = new CommentAction();
  }


  /** 執行 */

  public function action(){
    

    $this->checkRecipe(); //驗證 原創食譜 是否存在 ;

    //==== 從資料庫獲取食譜資料 ====
    $this->detail_object = $this->model->getDetails();
    $this->commentNums = $this->act->getCommentTotal($this->id); //留言
    $this->comment_object = $this->act->getComment($this->id); //留言
    $this->reply_object = $this->act->getReply($this->id); //回覆
    $this->readDetailsXML();

    //=== 搜尋資料庫會員試作原創食譜資料
    $this->getTrialContent();
    //==== 網友跟著做功能的區塊 ====/

  }

  /**

   * 讀取 準備材料、調理步驟 xml 檔案，產生 html 文本內容

   * @param Object Array $html 物件陣列

   */

  private function readDetailsXML(){

    

    $xm_dir = $this->detail_object->step_xml;  //取得 xml 路徑



    if(file_exists($xm_dir)){

      $sxml = simplexml_load_file($xm_dir); //載入 XML 檔案

      $i_table = array(); //存放材料名稱、數量單位

      $step = array(); //存放調理步驟

      foreach($sxml as $value){ //取出ingredient 和 step 物件陣列 

        if($value->getname() == 'ingredient'){  

          $i_table[] = $value; //取出 ingredient  物件陣列 

        }else{

          $step[]= $value;  //取出 step 物件陣列  -> img 物件陣列

        }

      }



      // ====== 準備材料 處理區塊 ======

      $no = null; //群組編號檢查用

      $this->ingredient = null; //存放 html 文本

      foreach($i_table as $key => $t_value){

        $this->ingredient.="\t<dl class=\"ingredient\">\n";

        foreach($t_value->qt as $q_value){

          $this->ingredient.="\t<dd>".$q_value->food.'<span>'.$q_value->attributes()."</span></dd>\n";

        }

        $this->ingredient.="\t</dl>\n";

      }



      // ====== 調理步驟 處理區塊 ======

      $this->step= "<dl class='step_dl'>\n";

      foreach($step as $value){

        foreach($value as $iv){

          $this->step.= "\t\t<dt><img src=".$iv->attributes().'></dt><dd class="step_text">'.$iv->p."</dd>\n";

        }

      }

      $this->step.= "\t</dl>\n";

    }else {

      exit('Failed to open: '.$xm_dir);

    }

  }


  /** 會員跟著作料理的功能 

   * @return int

  */

  private function trialCook(){



    $this->checkRecipe(); //驗證 原創食譜 是否存在 ;



    if(!isset($_POST['img']) && !count($_POST['img'])){

      exit( "<script>alert('Oops! 您 的 成 品 照 片 尚 未 上 傳!');history.back();</script>");

    }



    $note = $_POST['note'];

    $note_len = mb_strlen($note,'utf8');

    if(empty($_POST['note'])){

      exit("<script>alert('Oops! 您 的 心 得 尚 未 填 寫!');history.back();</script>");

    }else if($note_len > 500 || $note_len < 10){

      exit( "<script>alert('Oops! 心 得 字 數 不 得 小於 10 字 或 大於 500 字!');history.back();</script>");

    }

    

    //==== 取得會員跟著做的成品照片位址 ====

    $imagData = $_POST['img'];

    $path ='uploaded/TrialPics';  //上傳路徑

    $upimg = new UpImage();

    $img_src = $upimg->createImgFolder($path,1);  //* 生成上傳路徑，索引值 0 固定是成品照片位址



    //--- 食譜試作資料寫入資料庫 ---

    $this->model->user_id = 1;  //$_COOKIE['CookId'];要先登入

    $this->model->follow = $this->id;

    $this->model->finish_img = $img_src[0];     //成品圖片地址

    $this->model->summary= $note;   //試作心得

    $this->model->state = 1;

    $this->summary = $note;

    if($this->model->addTrial()){

      $upimg->uploadImg($imagData,$this->step_img); //上傳圖檔

      echo "<script>alert('食 譜 試 作 新 增 成 功 !');location.href='recipe_detail.php?id={$this->id}';</script>";

    }else echo "<script>alert('警告: 食 譜 試 作 新 增 失 敗，請 聯 繫  管 理 員!');history.back();</script>";


  }


  /** 

   * 獲取其他會員跟著食譜做的資料 

   */

  private function getTrialContent(){

    $this->model->id = $this->id;

    if(!!$obj = $this->model->getTrialContent()) {

      $this->trail_object = $obj;

    }

  }



  /** 驗證 原創食譜 是否存在 */

  private function checkRecipe(){

    if( isset($_GET['id']) && is_numeric(@$_GET['id']) ){

      $this->id = round($_GET['id'],0); //無條件捨去，確保為 id 整數型態

      $this->model->id = $this->id;

      //從資料庫獲取食譜資料

      if(!$this->model->checkRecipe()) {
        Tool::alertBack( "警告: 查 無 此 食 譜 !");
      }

    }else{
      Tool::alertBack('警告: 非 法 操 作!');
    }

  }



  /**

   * 返回 準備材料、調理步驟 的 html 文本內容

   * @param String $type : ingredient = 準備材料, step = 調理步驟

   * @return String

   */

  public function getHtml($type){

    return $this->$type;

  }



  /**

  * 獲取 食譜詳細內容資料 (物件陣列)

  * @return Object array

  */

  public function getDetail(){
    return $this->detail_object;
  }

}

?>