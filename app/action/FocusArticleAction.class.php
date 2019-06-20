<?php
  /**
   * 業務流程控制器類 -- 文章查詢、編輯系統 -- 業務邏輯
   */
  class FocusArticleAction{
    private $allArticleObj; //所有文章資料 物件
    private $oneArticleObj; //一則文章資料 物件
    private $model;//模型實例

    public function __construct(){
    
      $this->model = new FocusArticleModel();
    }

    public function action(){
      //驗證登入
      Validate::checkUserLogin()? Tool::alertBack('請 先 登 入！'):true;
      switch(@$_GET['act']){

        case 'show':
          $this->show();
          break;

        case 'add':
          $this->add();
          break;

        case 'update':
          $this->update();
          break;

        case 'delete':
          $this->delete();
          break;

        default:
          Tool::alertBack();
          break;
      }
    }

    /** 顯示文章列表 */
    private function show(){
      if(!!$obj = $this->model->getAllArticle()){
        $this->allArticleObj = $obj;
      }
    }

    private function update(){
      if(isset($_POST['save'])){
        $this->article_exist();//驗證文章是否存在
        $this->managerPrivilege(4);//驗證權限 >= 4
        $this->model->modify_user = $_COOKIE['cookied'];
        $title = strip_tags($_POST['title']);
        $summary = strip_tags($_POST['summary']);
        Validate::checkStrLen($title ,5,100)? Tool::errorMsg('標 題 不 得 少 於 5 字 或 超 過 100 字'):false;
        Validate::checkStrLen($summary ,20,500)? Tool::errorMsg('摘 要 須 符 合 20 - 500 字內 !'):false;
        $this->model->title = $title;
        $this->model->summary = $summary;
        $this->model->focus_img = $_POST['thumbImg'];
        $this->model->content = html_entity_decode($_POST['content']);
        //發布或草稿
        if($_POST['state']) $state = 1;
        else $state = 0;
        $this->model->state = $state;

        if($this->model->updateArticle()) echo 'SUCCESS';
        else echo 'ERROR';
      }
    }

    private function add(){
      if(isset($_POST['send'])){
        $this->managerPrivilege(4);//驗證權限 >= 4
        $this->model->user_id = $_COOKIE['cookied'];
        $title = strip_tags($_POST['title']);
        $summary = strip_tags($_POST['summary']);
        Validate::checkStrLen($title ,5,100)? Tool::errorMsg('標 題 不 得 少 於 5 字 或 超 過 100 字'):false;
        Validate::checkStrLen($summary ,20,500)? Tool::errorMsg('摘 要 須 符 合 20 - 500 字內 !'):false;
        $this->model->title = $title;
        $this->model->summary = $summary;
        $this->model->focus_img = $_POST['thumbImg'];
        $this->model->content = $_POST['content'];
        //發布或草稿
        if($_POST['state']) $state = 1;
        else $state = 0;
        $this->model->state = $state;
        $this->model->state = $_POST['state'];
        if($this->model->addArticle()) echo 'SUCCESS';
        else echo 'ERROR';
      }
    }

    private function delete(){
      if(isset($_POST['delete'])){
        $this->article_exist();//驗證文章是否存在
        $this->model->id = $_GET['id'];
        if($this->model->deleteArticle()) echo 'SUCCESS';
        else echo 'ERROR';
      }
    }

    /**取得所有文章 */
    public function getAllArticle(){
      return $this->allArticleObj;
    }

    /** 取得一則文章資料 */
    public function getOneArticle(){
      $this->article_exist()? true:Tool::alertBack('文 章 不 存 在！');
      return $this->oneArticleObj;
    }

    /** 取得一則文章詳細內容 */
    public function getDetail(){
      if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $this->model->id = round($_GET['id']);//轉整數型別
        if(!!$obj = $this->model->getOneArticleDetail()) return $obj; 
        else Tool::alertBack('無 此 文 章!');
      }else{
        Tool::alertBack('警告: 非 法 操 作!');
      }
    }

    /** 查詢編輯人員資料 */
    public function getEditor(){
      if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $this->model->id = round($_GET['id']);//轉整數型別
        return $this->model->getEditor();
      }
    }

    /** 查詢編輯人員資料 */
    public function getAllEditor(){
        return $this->model->getAllEditor();
    }
    
    /** 取得所有焦點文章的快照 */
    public function getArticleSnapShot(){
      if(!!$obj = $this->model->getAllSnapShot())
        return $obj;
    }

    /** 取得所有焦點文章的快照 */
    public function getFocusArticle(){
      if(!!$obj = $this->model->getOneFocus())
        return $obj;
    }

    //**驗證 特定文章是否存在 */
    private function article_exist(){
      if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $this->model->id = round($_GET['id']);//轉整數型別
        $this->oneArticleObj = $this->model->getOneArticle();
        return $this->oneArticleObj ? true:false;
      }else{
        Tool::alertBack('警告: 非 法 操 作!');
      }
    }

    /** 驗證管理員權限 */
    private function managerPrivilege($priv){

      if(Validate::checkManagerValidate($_COOKIE['cookied'],
                                        $_COOKIE['csk'],
                                        $_COOKIE['mv'],
                                        $_COOKIE['ln'],$priv)){
      Tool::errorMsg('無 權 操 作 ！');                   
      }
    }
    
  }
?>