<?php
  /** === 食譜詳情頁面 ===*/
  require dirname(__FILE__).'/init.php';
  //----詳細內容----    
  $details = new DetailsAction();
  $details->action();
  $ingre =  $details->getHtml('ingredient'); //材料
  $step =  $details->getHtml('step'); //步驟
  $html =  $details->getDetail(); //詳細內容
  //----試作功能----
  $trial = new TrialAction();
  $try = $trial->getTrial(); //試作內容
  //-----收藏功能----
  $collect = new CollectRecipeModel();
  $collect->id = $_GET['id'];
  if(isset($_COOKIE['cookied'])) $collect->user_id = $_COOKIE['cookied'];
  if($collect->selfCollection()){//驗證是否為自己的收藏
    $colle_bg = 'style="background:url(\'sprite/like2.svg\')no-repeat left center"'; 
    $col = 2; //已收藏,可供 JS 計算按下的次數(偶數為按下)
  }else{
    $colle_bg = 'style="background:url(\'sprite/like.svg\')no-repeat left center / 19px;"'; 
    $col = 1; //還沒收藏,可供 JS 計算按下的次數(奇數還沒按下)
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $html->title; ?></title>
  <!-- StyleSheets -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/basic.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/header.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/recipe_detail.css">
  <!--- Script Source Files -->
  <script src="js/navBar.js"></script>
  <script src="js/recipe_detail.js"></script>
  <script src="js/uploadImage.js"></script>
  <!-- JS script   -->
  <script>
      window.onload = function(){
        navBarEvent();
        updateListener();
      };
  </script>
</head>
<body>
  <?php include 'header.php';?>
  <div id="adapt">
      <!------- 手機板的導覽列 ------->
    <div class="collapse_navbar">
      <ul id="collapse-menu">
        <li><a href="#"><span class="contact">聯繫</span></a></li>
        <li><a href="#"><span class="report">問題</span></a></li>
        <li><a href="user_login.php"><span class="login">登入</span></a></li>
      </ul>
    </div>
    <!------- 廣告區塊 ------->
    <div class="advertise"></div>
    <!------- 食譜詳情 -------->
    <div class="recipe_detail">
      <h1><?php echo $html->title;?></h1>
     
      <dl>
        <dt><img src="<?php echo $html->finish_img?>" alt="食譜成品"></dt>
        <dd class="summary"><?php echo $html->summary;?></dd>
      </dl>
      <div id="ingre">
        <h2>準備材料</h2>
        <?php if($ingre){?>
        <?php echo $ingre?></dd>
        <?php }else{ ?> 
        <dd><span>無 資 料!</span></dd>
        <?php }?>
        <h2>調理方式</h2>
        <?php if($step){?>
        <?php echo $step?></dd>
        <?php }else{ ?> 
        <dd><span>無 資 料!</span></dd>
        <?php }?>
      </div>
      <?php if(@$_COOKIE['cookied'] == $html->user_id || @$_COOKIE['mv'] >= 5 ){ //只有本人或是管理員?>
      <div id ="edit-bar"><a href="recipe_update.php?act=update&id=<?php echo $html->id;?>">編輯</a></div>
      <?php }?>
      <div id="authorTime">
        <ol>
          <input type="hidden" id="collection" value="<?php echo $col;?>">
          <li>收藏: <span id="colle-span"><?php if($html->collected){echo $html->collected;} else echo 0;?></span></li>
        </ol>
        <div class="author">
          <ol>
            <li class="photo-li">
              <img src="uploaded/self_photo/22.gif" alt="頭像" style="float:left">
              <a href="#" style=""><?php echo $html->nickname;?></a>
            </li>
            <li class="time-li"><?php echo $html->date;?> 發 布</li>
          </ol>
        </div>
        <div id="trial-like">
          <button id="collect-btn" class="collect-btn"><span <?php echo $colle_bg;?>>收藏</span></button>
          <!-- <button id="share-btn" class="share-btn"><span>分享</span></button> -->
        </div>
      </div>

    </div>
    <!---- 留言區 ---->
    <div id="comment">
      <iframe src="comment.php?id=<?php echo @$_GET['id'];?>" style="" frameborder="0"></iframe>
    </div>
  </div>
<!--- JS Source Files -->
<script src="js/collect.js"></script>
</body>
</html>