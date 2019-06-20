<?php
require dirname(__FILE__).'/init.php';    

$user = new UserLoginAction();

$user->action();

?>

<!DOCTYPE html>

<html lang="UTF-8">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <title>登入會員</title>

  <!-- StyleSheets -->

  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/basic.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/header.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/login.css">
  <!--- Script Source Files -->
  <script src="js/navBar.js"></script>
</head>
<body>
  <?php include 'header.php';?>   
  <script>
    window.onload = function(){
      navBarEvent();
    };
  </script>

  <div id="login" style="text-align:center;">

    <form action="?act=login" method="post">

      <dl style="">

        <dd >

          <div class="title"><h1>Login</h1></div>

        </dd>

        <dd><label>使用者名稱: <input type="text" name="username" ></label></dd>

        <br>

        <dd><label>密　　　碼: <input type="password" name="pass" ></label></dd>

        <br>

        <dd><label>驗　證　碼: <input type="text" name="code" ></label></dd>

        <br>

        <dd><img title="點一下更換" style="margin:0 0 0 50px ;border:1px solid grey;cursor:pointer;" src="app/tool/code.php" alt="驗證碼" onclick="javascript:this.src='app/tool/code.php'"></dd>

        <dd class="span-hint"><span style="">(*不含空白 、區分大小寫)</span></dd>

        <br>

        <dd class="submit"><input style="" type="submit" name="login" value="登入會員">   <a href="register.php">註冊</a></dd>

      </dl>

    </form> 

  </div>

  <!-- <div id="footer" >bottom</div> -->

</body>

</html>