<?php

/**

 * 業務流程控制器類 -- 會員 註冊、登入、登出、查詢 系統 -- 業務邏輯

 */

class CommentAction{

  private $id;          //留言 id
  private $user_id;     //使用者 id
  private $user_name;   //使用者名稱
  private $user_ckey;   //使用者 驗證序號
  private $content;     //留言內容
  private $state;       //發布狀態

  public function __construct(){
    $this->model = new CommentModel();
  }

  /**
   * 執行
   */
  public function action(){
    //驗證登入
    Validate::checkUserLogin()?Tool::errorMsg('UNLogin'):true;
    switch(@$_GET['act']){
      
      case 'add':
        $this->add();
        break;

      case 'update':
        $this->update();
        break;

      case 'delete':
        $this->delete();
        break;
    }
  } 

  /**
   * 新增留言 或 回覆
   */
  private function add(){
    
    if(isset($_POST['comment'])){
      //驗證身分
      $userId = $_COOKIE['cookied']; //使用者 id
      $ckey = $_COOKIE['csk']; //驗證序號
      $level = $_COOKIE['mv'];
      $levelName = $_COOKIE['ln'];
      
      if(Validate::checkUserValidate($this->user_id,$ckey,$level,$levelName)){ 
        echo '無 權 操 作!';
        return;
      }

      $comment = $_POST['comment'];

      if(Validate::checkStrLen($comment,2,500)){
        echo '字數不得小於 2 個 或 大於 500 個!';
        return;
      }

      $this->model->rec_id = $_POST['recId']; //食譜 id
      $this->model->user_id = $userId; //會員 id
      $this->model->reply = @$_POST['reply']; //被回覆者得 id
      $this->model->pid = @$_POST['pid']; //父留言 id ，若 = null 則為子留言
      $this->model->comment = $comment; //留言 或 回覆

      if($this->model->addComment()){
        echo 'SUCCESS';
      }else{
        echo '新 增 留 言 失 敗，請 聯 繫 管 理 員!';
        return;
      }
    }

    return;
  }
  /**
   * 獲取留言人數
   * @param Int $id  食譜 id
   * @return Int
   */
  public function getCommentTotal($id){
    $this->model->rec_id = $id;
    return $this->model->getCommentTotal();
  }
  /**
   * 獲取留言
   * @param Int $id  食譜 id
   * @return Object
   */
  public function getComment($id){
    $this->model->rec_id = $id;
    return $this->model->getComment();
  }

  /**
   * 獲取留言回覆
   * @param Int $id  食譜 id
   * @return Object
   */
  public function getReply($id){
    $this->model->rec_id = $id;
    return $this->model->getReply();
  }

}
?>