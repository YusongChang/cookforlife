<div id="header">

    <a href="./"><img id="logo" src="img/cookforlife.png" alt="cookforlife"></a>

    <ol id="nav-bar">

      <?php if(@$_COOKIE['cook']){?>

        <li class="setti-btn" title="設定"><span class="setting"></span></li>

      <?php }else{?>

      <li title="登入"><a href="user_login.php"><span class="login">登入</span></a></li>

      <?php }?>

      <!-- <li title="問題回報"><a href="#"><span class="report"></span></a></li>

      <li title="聯繫資訊"><a href="#"><span class="contact"></span></a></li> -->

    </ol>
    <?php if(@$_COOKIE['cook']){?>
    <div id ="setting-bar" class="settings">
      <ul id="setti-menu">
        <li><a href="userCenter.php?id=<?php echo $_COOKIE['cookied'];?>"><span>個人中心</span></a></li>
        <li><a href="recipe_add.php?act=add"><span>寫食譜</span></a></li>
        <li><a href="user_login.php?act=logout"><span>登出</span></a></li>
      </ul>
    </div>
    <?php }?>

</div>