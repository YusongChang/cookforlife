<?php
  /** 收藏食譜 */
  require dirname(__FILE__).'/init.php';
  //驗證登入
  Validate::checkUserLogin()? Tool::errorMsg('UNLogin'):true;

  if(isset($_POST['collect']) && isset($_POST['id'])){
    $collect = json_decode($_POST['collect']);
    $model = new CollectRecipeModel();
    $model->id = $_POST['id'];
    $model->user_id = $_COOKIE['cookied'];
    if($collect){
      echo $model->collect()? 'SUCCESS':'收 藏 失 敗！';
    }else{
      if(!$model->cancelCollect()) Tool::errorMsg('取 消 失 敗！');
      else echo 'CANCEL';
    }

  }

?>
