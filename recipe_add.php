<?php
  require 'editRecipe.php';
  // if(!defined('VERIFY_CODE')){ header("HTTP/1.0 404 Not Found");die(); } //防止惡意調用頁面
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>新增食譜</title>
  <!-- StyleSheets -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/basic.css">
  <link rel="stylesheet" href="stylesheet/header.css">
  <link rel="stylesheet" href="stylesheet/recipe_edit.css">
  <!--- Script Source Files -->
  <script src="js/recipe_add.js"></script>
  <script src="js/uploadImage.js"></script>
  <script src="js/addElement.js"></script>
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
<!-- Start Add Recipe section -->
<div id="add-recipe">
  <form action="?act=add" method="post">
    <div id="head_title">
      <input class="input_title text" id="" name="title" type="text" placeholder="輸入標題"> 
      <span>( 20 字)</span>
      <hr class="hr_medium">
    </div>
    <div id="finish_content">
      <div class="img-summ">
        <div class="up-img" id="up-img">
          <canvas class="preview_canvas"></canvas>
          <input no="0" class="finish_img" type="file" name="img[]" id="" accept="image/*" onchange=";">
        </div>
        <div class="up-img-info" id="up-img-info">
          <strong><span style="display:block;width:268px;height:20px;font-size:16px;color:green;border-bottom:0.5px dashed green">上傳比例&nbsp;4&nbsp;:&nbsp;3&nbsp;的照片，減少模糊失真</span></strong>
          <p>檔案名稱: <span id="filename">尚未上傳</span></p>
          <p>檔案大小: <span id="filesize">---- </span></p>
          <p>像素大小: <span id="filepixel">----</span></p>
          <p>自動修剪: <span id="autotrim">----</span>
        </div>
      </div>  
    </div>
    <div class="hidden_upimg_info">
        <h2><span style="display:block;width:268px;height:30px;font-size:16px;color:green;border-bottom:0.5px dashed green">上傳比例&nbsp;4&nbsp;:&nbsp;3&nbsp;的照片，降低像素失真</span></h2>
        <p>檔案名稱: <span id="filename2">尚未上傳</span></p>
        <p>檔案大小: <span id="filesize2">----</span></p>
        <p>像素大小: <span id="filepixel2">----</span></p>
        <p>自動修剪: <span id="autotrim2">----</span>
    </div>
    <div class="summary">
      <h3>烹飪心得 (500 字) </h3>
      <textarea name="summary" id="" placeholder="請寫下您的心得..." ></textarea>
    </div>
    <hr class="hr_medium">
    <div class="ingredient">
      <h3>材料</h3>
      <dl id="ingre_dl">
        <dd class="ingre_dd">
          <input class="text" type="text" name="food_name[]" id="" placeholder=" 材料">
          <input class="text unit" type="text" name="food_unit[]" id="" placeholder="數量">
        </dd>
        <dd class="ingre_dd">
          <input class="text" type="text" name="food_name[]" id="" placeholder=" 材料">
          <input class="text unit" type="text" name="food_unit[]" id="" placeholder="數量">
          <button type="button" id="" class="delete-btn" onclick="removeEls(event)">刪除材料</button>
        </dd>
        <dd class="ingre_dd">
          <input class="text" type="text" name="food_name[]" id="" placeholder=" 材料">
          <input class="text unit" type="text" name="food_unit[]" id="" placeholder="數量">
          <button type="button" class="delete-btn" onclick="removeEls(event)">刪除材料</button>
        </dd>
      </dl>
      <div class="add-row ig"><button class="add-ingre add-row-btn" type="button" value="新增材料" onclick="addIngredient();">新增材料</button> <span id="ingre_span" >( 剩餘: 3/15 列)</span></div>
    </div>
    <div class="cook_step">
      <h3>調理方式</h3>
      <dl id="step_dl">
        <dd class="step_dd">
          <h5>Step 1.</h5>
          <div class="upload_step">
          <canvas class="step_canvas"></canvas>
          <input no="1" class="step_img" type="file" name="img[]" id="" accept="image/*" onchange=";" >
          </div>
          <div class="input_area">
            <input  class="text step" type="text" name="step[]" id="" placeholder=" 輸入步驟">
          </div>
        </dd>
        <dd class="step_dd">
          <h5>Step 2.</h5>
          <div class="upload_step">
            <canvas class="step_canvas"></canvas>
            <input no="2" class="step_img" type="file" name="img[]" id="" accept="image/*" onchange=";" >
          </div>
          <div class="input_area">
            <input class="text step" type="text" name="step[]" id="" placeholder=" 輸入步驟">
            <button type="button" class="delete-btn" onclick="removeEls(event)">刪除步驟</button>
          </div>
        </dd>
        <dd class="step_dd">
          <h5>Step 3.</h5>
          <div class="upload_step">
            <canvas  class="step_canvas"></canvas>
            <input no="3" class="step_img" type="file" name="img[]" id="" accept="image/*" onchange=";" >
          </div>
          <div class="input_area">
            <input class="text step" type="text" name="step[]" id="" placeholder=" 輸入步驟">
            <button type="button" class="delete-btn" onclick="removeEls(event);">刪除步驟</button>
          </div>
        </dd>
      </dl>
      <div class="add-row"><button class="add-step add-row-btn" type="button" value="" onclick="addStep();">新增步驟</button> <span id="step_span">(剩餘: 3/15 列)</span></div>
    </div>
    <div class="submit">
      <label for=""><strong>存為草稿( 暫不公開 )</strong><input type="checkbox" name="draft" id="draft"></label>
      <input class="enter-btn" type="button" id="send" name="send" value="確認送出">
    </div>
    <div class="bottom-bar"><a onclick="history.back();">返回上一頁</a></div>
  </form>
</div>
<!-- End Add Recipe section -->    
<script>
</script>
</body>
</html>