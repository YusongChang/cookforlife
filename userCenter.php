<?php 
  require dirname(__FILE__).'/init.php';    
  $user = new UserAction();
  $html = $user->showUserCenter();
  if($html->photo == null) $html->photo = 'sprite/unknowUser.svg';
  $recipe = new RecipeModel();
  $recipe->user_id =$_GET['id'];
  $relist = $recipe->getUserRecipeList();//取得食譜內容
  $colist = $recipe->getUserCollection();//取得收藏的食譜內容
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>個人中心</title>
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/header.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/userCenter.css">
  <!--- JS Source Files -->
  <script type="text/javascript" src="js/navBar.js"></script>
</head>
<body>
<script>
    window.onload = function(){
      navBarEvent();
    };
</script>
  <?php include 'header.php'; ?>
</body>
  <!-- Main Content -->
  <div id="adapt">
    <!-- <div id="head-title"><h1><?//echo $username?>的個人中心</h1></div> -->
    <!-- Head Photo  and Username-->
    <div id="self-photo">
      <dl>
        <dt><img src="<?php echo $html->photo;?>" alt="頭像"></dt>
        <dd><?php echo $html->nickname;?></dd>
      </dl>
    </div>
    <!-- Conculate Fans Collections Recipes -->
    <div id="fan-collect-recipes">
      <ol>
        <li>收藏：<?php echo $html->collections;?></li>
        <li>食譜：<?php echo $html->recipes;?></li>
      </ol>
    </div>
    <!-- Introduce -->
    <div id="introduce">
      <div id="head-title"><h2>簡介</h2></div>
      <div id="intro-content">
        <?php echo $html->intro;?>
      </div>
      <?php if($_COOKIE['cook'] == $html->nickname || $_COOKIE['mv'] >= 5){?>
      <div id="enter-profile"><a id="profile-link" href="user_profile.php?id=<?php echo $html->id;?>">基本資料</a></div>
      <?php }?>
    </div>
    <!-- Time -->
    <div id="time">
      <div id="login-time"><span>最近登入：</span><?php echo $html->last_time;?></div>
      <div id="reg-date"><span>註冊日期：</span><?php echo $html->reg_date;?></div>
    </div>
    <!-- New Recipes -->
    <div id="new-recipe">
      <div id="head-title"><h3>原創食譜</h3></div>
      <div id="recipe-list">
        <div id="list">
          <?php if($relist){ foreach($relist as $value){ ?>
          <dl>
            <a href="recipe_detail.php?id=<?php echo $value->id;?>">
              <dt><img src="<?php echo $value->finish_img;?>" alt="snapshot"></dt>
              <dd id="dd-title"><?php echo $value->title;?></dd>
              <dd id="dd-date"><?php echo $value->date;?></dd>
            </a>
          </dl>
          <?php }}else{ echo '<p>尚 未 發 表 食 譜！</p>';}?>
        </div>
      </div>
    </div>
    <!-- New Collect -->
    <div id="new-collect">
    <div id="head-title"><h3>收藏食譜</h3></div>
      <div id="recipe-list">
        <div id="list">
          <?php if($colist){ foreach($colist as $value){ ?>
          <dl>
            <a href="recipe_detail.php?id=<?php echo $value->id;?>">
              <dt><img src="<?php echo $value->finish_img;?>" alt="snapshot"></dt>
              <dt id="dd-title"><?php echo $value->title;?></dt>
              <dd id="dd-title">by <?php echo mb_strlen($value->nickname)>6 ? mb_substr($value->nickname,0,6).'...':$value->nickname;?></dd>
              <dd id="dd-date"><?php echo $value->date;?></dd>
            </a>
            <dd id="dd-delete">
              <input type="hidden" id="rec_id" value="<?php echo $value->id;?>">
              <a id="delete-btn">移除</a>
            </dd>
          </dl>
          <?php }}else{ echo '<p>尚 未 收 藏 食 譜！</p>';}?>
        </div>
      </div>
    </div>
  </div>
<script src="js/collect.js"></script>
</body>
</html>