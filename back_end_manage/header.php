<?php
  //top-bar 頁面選擇
  switch($page){

    case 'art':
      $selec = 'article';
      $article = 'selected';
      break;

    case 'rep':
      $selec = 'recipe';
      $rep = 'selected';
      break;

    case 'user':
    $user = 'selected';
    break;

    default: 
      $selec = 'home';
      $home = 'selected';
      break;

  }
?>
<div id="header">
    <a href="./"><img id="logo" src="../img/cookforlife.png" alt="cookforlife"></a>
    <div class="bc-title"><span>後台系統</span></div>

<?php //if(@$_COOKIE['manager']){?>
    <div class="header-logout">
          <a href="../user_login.php?act=logout">登 出</a>
    </div>
    <div class="header-menu">
          <a id="menu-btn">選 項</a>
    </div>

</div>

<!-- side-bar-top 待修改 摺疊式 menu -->
<div id="top-bar">
  <ol>
    <li class="<?php echo @$home;?>"><a href="home.php">首頁</a></li>
    <li class="<?php echo @$article;?>" ><a href="focusArticle.php?act=show">文章管理</a></li>
    <li class="<?php echo @$rep;?>"><a href="recipe.php?act=show">食譜管理</a></li>
    <!-- <li><a href="?selec=adver">廣告管理</a></li> -->
    <li class="<?php echo @$user;?>" ><a href="user.php?act=show">會員管理</a></li>
    <li><a id="collapse-btn" title="隱藏">▲</a></li>
  </ol>
</div>
<?php //}?>
<script src="js/header.js"></script>
