<?php
    require dirname(__FILE__).'/init.php';
    $user = new UserAction();
    $html = $user->getUserProfile();
    if($_COOKIE['cook'] != $html->nickname && $_COOKIE['mv'] < 5){
      header("HTTP/1.0 404 Not Found");
      die();
    }
    if($html->photo == null) $html->photo = 'sprite/unknowUser.svg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>基本資料</title>
  <!--- Stylesheet Source Files -->
  <link rel="stylesheet" href="stylesheet/header.css">
  <link rel="stylesheet" href="stylesheet/user_profile.css">
  <!--- Script Source Files -->
  <script src="js/navBar.js"></script></head>
<body>
<script>
    window.onload = function(){
      navBarEvent();
    };
  </script>
<?php include 'header.php';?>
<!-- Main Content -->
<div id="adapt">
  <div id="page-bar">
      <ol>
        <li><a href="userCenter.php?id=<?php echo $html->id;?>">個人中心</a></li> > 
        <li><a id="dir-profileTag" style="">基本資料</a></li>
      </ol>
  </div>
  <!-- <div id="head-title"><h1><?//echo $username?>的個人中心</h1></div> -->
  <!-- Head Photo  and Username-->
  <div id="self-photo">
    <img src="<?php echo $html->photo;?>"  alt="頭像"></dt>
  </div>
  <div id="user-info">
    <ol>
      <li><span>ID：<?php echo $html->user;?></span></li>
      <li><span>暱稱：<?php echo $html->nickname;?></span></li>
      <li><span>等級：<?php echo $html->levelname;?></span></li>
      <li><span>信箱：<?php echo $html->email;?></span></li>
      <li><span>手機：<?php echo $html->phone;?></span></li>
    </ol>
  </div>
  <!-- Introduce -->
  <div id="introduce">
    <div id="head-title"><h1>簡介</h1></div>
    <!-- 輸入支援 html -->
    <div id="intro-content">
      <?php echo $html->intro;?>
      <div id="intro-edit"></div>
    </div>
  </div>
  <div id="edit-bar">
    <a href="userProfile_update.php?id=<?php echo $html->id?>" id="edit-btn">編輯</a>
    <a href="#"id="change-pwd-btn">更換密碼</a>
  </div>
</div>
</body>
</html>