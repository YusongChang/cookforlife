<?php
/**
 * 業務流程控制器類 -- 權限等級編輯系統 -- 業務邏輯
 */
  class LevelAction{
    private $model;   // 存放會員模型實例化物件
    private $user_id; //會員 id
    private $AllLevelObj; //等級資料 物件
    private $oneLevelObj; //等級資料 物件

    public function __construct(){
      $this->model = new LevelModel;
    }

    public function action(){
      //驗證登入
      Validate::checkManagerLogin()? Tool::alertBack('請 先 登 入！'):true;

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

    /** 取得所有會員資料物件 */
    private function show(){
      if($obj = $this->model->getAllLevel()){
        $this->levelListObj = $obj;
      }else return false;
    }

    /** 取得所有會員資料物件 */
    private function update(){

      if(isset($_GET['id']) && is_numeric($_GET['id'])){
       
      }else Tool::alertBack('非法操作！');

      if(isset($_POST['send'])){
        //--- 驗證身分 防止假造 cookie---
        $this->user_id = $_COOKIE['cookied'];
        $ckey = $_COOKIE["csk"];
        $level = $_COOKIE["mv"];
        $levelName = $_COOKIE["ln"];
        Validate::checkManagerValidate($this->user_id,$ckey,$level,$levelName,6)?Tool::alertBack(' 無 權 操 作！'):true;
        Tool::alertBack('更新！');
      }
     
    }


    // /** 顯示所 1 筆等級資料 */
    // public function getOneLevelObj(){
    //   return $this->OneLevelObj;
    // }

    // /** 顯示所有等級資料 */
    // public function getAllLevel(){
    //   return $this->AllLevelObj;
    // }

    /** 查詢 1 筆等級 */
    public function getOneLevel(){
      if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $this->model->id = round($_GET['id']);//無條件捨去，確保為 id 整數型態
        $this->oneLevelObj = $this->model->getOneLevel();
        return $this->oneLevelObj;
      }else{
        return false;
      }
    }

    /** 查詢 所有位等級 */
    public function getAllLevel(){
      $this->AllLevelObj = $this->model->getAllLevel();
      return $this->AllLevelObj;
    }
  }

?>