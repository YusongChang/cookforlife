<?php
//=== *** 驗證管理員身分 *** ===
require 'require.php';
Validate::checkManagerLogin() ? Tool::alertLocation('login.php') : true;
$page = 'home';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>後台首頁</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/bcHeader.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/home.css">
</head>
<body>
<?php include 'header.php' ;?>
  <div id="adapt" style="text-align:center;">
    <span>歡　迎　使　用</span>
    <p>點擊右上方的選項，選擇管理功能</p>
  </div>
</body>
</html>
