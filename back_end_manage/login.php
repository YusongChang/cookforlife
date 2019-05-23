<?php
require 'require.php';
$user = new UserLoginAction();
$user->action();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>登入</title>
  <!-- StyleSheets -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/bcHeader.css">
  <link rel="stylesheet" type="text/css" media="screen" href="../stylesheet/login.css">
</head>
<body>
<div id="login" style="text-align:center;">
  <form action="?act=login" method="post">
    <dl>
      <dd >
        <div><h1 id="head-title">後 台 管 理</h1></div>
      </dd>
      <dd >
        <div class="title"><h1 id="managehead">Login</h1></div>
      </dd>

      <dd><label>使用者名稱: <input type="text" name="username" ></label></dd>

      <br>

      <dd><label>密　　　碼: <input type="password" name="pass" ></label></dd>

      <br>

      <dd><label>驗　證　碼: <input type="text" name="code" ></label></dd>

      <br>

      <dd><img title="點一下更換" style="margin:0 0 0 50px ;border:1px solid grey;cursor:pointer;" src="../app/tool/code.php" alt="驗證碼" onclick="javascript:this.src='../tool/code.php'"></dd>

      <dd class="span-hint"><span style="">(*區分大小寫)</span></dd>

      <br>

      <dd class="submit"><input style="" type="submit" name="login" value="登入"></dd>

    </dl>

  </form> 

</div>  
</body>
</html>