<?php
  //權限小於 3 則跳回首頁
 if(isset($_COOKIE['mv'])){
  $_COOKIE['mv'] < 3 ? header("Location: index.php") : false;
  $URL = "back_end_manage";
}
 else header("Location: user_login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>歡迎頁面</title>
  <link rel="stylesheet" href="stylesheet/loginWelcom.css">
</head>
<body>
  <div id="adapt">
    <div id="head-title">
      <span>歡 迎 回 來 <?php echo @$_COOKIE['cook'];?> !</span>
    </div>
    <div id="wel-info">
      <span>請　　選　　擇　　下　　方　　頁　　面</span>
    </div>
    <div id="page-selec">
      <a href="./">前台首頁</a>　<a href="back_end_manage">後台首頁</a>
    </div>
  </div>
</body>
</html>