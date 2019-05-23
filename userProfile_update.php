<?php
    require dirname(__FILE__).'/init.php';    
    $user = new UserAction();
    $html = $user->getUserProfile();
    if($_COOKIE['cook'] != $html->nickname && $_COOKIE['mv'] < 5){
      header("HTTP/1.0 404 Not Found");
      die();
    }
    $border = null;
    if($html->photo == null) $border = 'border:1px solid black;';
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
  <link rel="stylesheet" href="stylesheet/userProfile_update.css">
  <link rel="stylesheet" type="text/css" href="app/tool/simditor-2.3.23/styles/simditor.css" />
  <!--- JS Source Files -->
  <script type="text/javascript" src="js/navBar.js"></script>
  <script type="text/javascript" src="app/tool/simditor-2.3.23/site/assets/scripts/jquery.min.js"></script>
  <script type="text/javascript" src="app/tool/simditor-2.3.23/site/assets/scripts/module.js"></script>
  <script type="text/javascript" src="app/tool/simditor-2.3.23/site/assets/scripts/hotkeys.js"></script>
  <script type="text/javascript" src="app/tool/simditor-2.3.23/lib/simditor.js"></script>
</head>
  
<body>
<script>
  window.onbeforeunload = unload; /*使用者 pressed 上一頁 或是 刷新頁面 則出現警告*/
  
  window.onload = function(){
    navBarEvent();
  };
  
  /*使用者 pressed 上一頁 或是 刷新頁面 則出現警告*/
  function unload(e){
    return "";
  }
</script>
<?php include 'header.php';?>
<!-- Main Content -->
<div id="adapt">
  <div id="page-bar">
      <ol>
        <li><a href="user_profile.php?id=<?php echo $html->id;?>">基本資料</a></li> > 
        <li><a id="dir-editTag" style="">編輯基本資料</a></li>
      </ol>
  </div>
  <div id="user-info">
    <!-- Head Photo update-->
    <input type="file" id="upload-photo"  accept="image/*">
    <ol>
      <li>
        <input type="hidden" id="origin-photo" value="<?php echo $html->photo;?>">
  
        <div title="更換頭像" id="canvas-div"  style="background:url('<?php echo $html->photo;?>')no-repeat left center;background-size: 72px;">
          <canvas id="photo-canvas" style="<?php echo $border;?>"></canvas>
        </div>
      </li>
      <li><span>ID：</span> <?php echo $html->user;?></li>
      <li><span>暱稱：</span><input id="nickname" type="text" placeholder="輸入暱稱 2-10 個字" value="<?php echo $html->nickname;?>"></li>
      <li><span>信箱：</span><input id="email" type="email" placeholder="輸入信箱 fxs@xxxxx.com" value="<?php echo $html->email;?>"></li>
      <li><span>手機：</span><input id="phone" type="text" placeholder="輸入手機 0981xxxxxx" value="<?php echo $html->phone;?>"></li>
    </ol>
  </div>
  <!-- Introduce -->
  <div id="introduce">
    <div id="head-title"><h1>簡介</h1></div>
    <!-- 輸入支援 html -->
    <div id="intro-editor">
      <h6 style="color:gray;">不支援 html 語法</h6>
      <textarea id="editor" name placeholder="Balabala" autofocus><?php echo $html->intro;?></textarea>
    </div>
  </div>
  <div id="edit-bar">
    <a id="save-btn">確認儲存</a>
  </div>
</div>
<!--- JS Source Files -->
<script type="text/javascript" src="js/userProfile_update.js"></script>
<script type="text/javascript" src="js/uploadImage.js"></script>
</body>
</html>