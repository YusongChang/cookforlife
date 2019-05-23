<?php
  require 'editRecipe.php';
  $content = $recipeEdit->getContentObj();//取得標題、成品照、心得
  $ingredient = $recipeEdit->getIngredient(); //取得材料
  $step = $recipeEdit->getStep(); //取得步驟
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>編輯食譜</title>
  <!-- StyleSheets -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/basic.css">
  <link rel="stylesheet" href="stylesheet/header.css">
  <link rel="stylesheet" href="stylesheet/recipe_edit.css">
  <!--- Script Source Files -->
  <script src="js/recipe_update.js"></script>
  <script src="js/uploadImage.js"></script>
  <script src="js/navBar.js"></script>
  <script>
  window.onbeforeunload = unload; /*使用者 pressed 上一頁 或是 刷新頁面 則出現警告*/
  window.onload = function(){
    navBarEvent();
    updateListener();
  };

  /*使用者 pressed 上一頁 或是 刷新頁面 則出現警告*/
  function unload(e){
    return "";
  }
</script>
</head>
<body >
<?php include "header.php";?>
<div class="collapse_navbar">
  <ul id="collapse-menu">
    <li><a href="#"><span class="contact">聯繫</span></a></li>
    <li><a href="#"><span class="report">問題</span></a></li>
    <li><a href="user_login.php"><span class="login">登入</span></a></li>
  </ul>
</div>
<!-- <div id="advertise_area">廣告區塊</div> -->
<div id="update-head"><h1>編輯食譜</h1></div>
<!-- Start Update Recipe section -->
<div id="add-recipe">
    <div id="head_title">
      <input class="input_title text" id="" name="title" type="text" value="<?php echo $content->title?>"> 
      <span>( 20 字)</span>
      <hr class="hr_medium">
    </div>
    <div id="finish_content" >
      <div class="img-summ">
        <div class="up-img" id="up-img" style="<?php echo 'background:url('.$content->finish_img.')no-repeat left center;border:1px dashed black;';?>">
          <canvas class="preview_canvas"></canvas>
          <input no="0" class="finish_img" type="file" name="img[]" id="" accept="image/*">
        </div>
        <div class="up-img-info" id="up-img-info">
          <strong><span style="display:block;width:268px;height:20px;font-size:16px;color:green;border-bottom:0.5px dashed green">上傳比例&nbsp;4&nbsp;:&nbsp;3&nbsp;的照片，減少模糊失真</span></strong>
          <p>檔案名稱: <span id="filename">尚未修改</span></p>
          <p>檔案大小: <span id="filesize">---- </span></p>
          <p>像素大小: <span id="filepixel">----</span></p>
          <p>自動修剪: <span id="autotrim">----</span>
        </div>
      </div>  
    </div>
    <div class="hidden_upimg_info">
        <h2><span style="display:block;width:268px;height:30px;font-size:16px;color:green;border-bottom:0.5px dashed green">上傳比例&nbsp;4&nbsp;:&nbsp;3&nbsp;的照片，降低像素失真</span></h2>
        <p>檔案名稱: <span id="filename2">尚未修改</span></p>
        <p>檔案大小: <span id="filesize2">----</span></p>
        <p>像素大小: <span id="filepixel2">----</span></p>
        <p>自動修剪: <span id="autotrim2">----</span>
    </div>
    <div class="summary">
      <h3>烹飪心得 (500 字) </h3>
      <textarea name="summary"><?php echo $content->summary;?></textarea>
    </div>
    <hr class="hr_medium">
    <div class="ingredient" >
      <h3>材料</h3>
      <dl id="ingre_dl">
        <?php
          foreach($ingredient as $value){
            foreach($value->qt as $qValue){
        ?>
        <dd class="ingre_dd">
          <input class="text" type="text" name="food_name[]" id="" value="<?php echo $qValue->food;?>">
          <input class="text unit" type="text" name="food_unit[]" id="" value="<?php echo $qValue->attributes()?>">
          <button type="button" id="" class="delete-btn" onclick="removeEls(event)">刪除材料</button>
        </dd>
        <?php }}?>
      </dl>
      <div class="add-row ig"><button class="add-ingre add-row-btn" type="button" value="新增材料" onclick="addIngredient();">新增材料</button> <span id="ingre_span" >(最多: 15 列)</span></div>
    </div>
    <div class="cook_step">
      <h3>調理方式</h3>
      <dl id="step_dl">
        <?php
          $no = 1;
          foreach($step as $value){
            foreach($value as $sv){
        ?>
        <dd class="step_dd">
          <h5>Step <?php echo $no;?>.</h5>
          <div class="upload_step" style="<?php echo 'background:url('.$sv->attributes().')no-repeat left center;'?>">
          <canvas class="step_canvas"></canvas>
          <input no="<?php echo $no++;?>" class="step_img" type="file" name="img[]" id="" accept="image/*" value="<?php echo $sv->attributes();?>" >
          </div>
          <div class="input_area">
            <input  class="text step" type="text" name="step[]" id="" value="<?php echo $sv->p?>">
            <button type="button" class="delete-btn" onclick="removeEls(event);">刪除步驟</button>
          </div>
        </dd>
        <?php }}?>
      </dl>
      <div class="add-row"><button class="add-step add-row-btn" type="button" value="" onclick="addStep();">新增步驟</button> <span id="step_span">(最多: 15 列)</span></div>
    </div>
    <div class="submit">
      <label for="draft"><strong>存為草稿( 暫不公開 )</strong><input type="checkbox" name="draft" id="draft"></label>
      <input class="enter-btn" type="button" id="send" name="send" value="確認儲存">
      <input class="delete-btn" type="button" id="delete" name="delete" value="刪除">
    </div>
    <div class="bottom-bar"><a onclick="history.back();">返回上一頁</a></div>
</div>
<!-- End Update Recipe section -->
<script src="js/addElement.js"></script>
</body>
</html>