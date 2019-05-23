<?php

/**

 * 業務流程控制器類 --首頁 index-- 業務邏輯

 */

class IndexAction{

  private $model;       //存放 model 物件

  private $recipeObj;    //存放 食譜資料



  public function __construct(){

    $this->model = new RecipeModel();

  }



  /**

  * 流程控制器

  * @return void

  */

  public function action(){

      $this->show();

  }



  /** 

   * 顯示 食譜列表快照 

   */

  private function show(){

    if(!!$obj = $this->model->getSnapShot()){

       $this->recipeObj = $obj;

    }else{

      echo "<script>alert(警告: 無 食 譜 資 料!);</script>";

    }

  }



  /** 取得食譜快照資料 */

  public function getSnapshot(){

    return $this->recipeObj;

  }

  

}



?>