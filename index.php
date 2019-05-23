<?php
  require dirname(__FILE__).'/init.php';
  //---取得食譜快照資料----
  $recipe = new IndexAction();
  $html = $recipe->action();
  $html = $recipe->getSnapshot();
  //---取得焦點文章---
  $focus = new FocusArticleAction();
  $focus_article = $focus->getFocusArticle();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cook For Life</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <!-- StyleSheets -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/header.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/index.css">
  <!--- Script Source Files -->
  <script src="js/navBar.js"></script>
</head>
<body>
  <script>
    window.onload = function(){
      navBarEvent();
    };
  </script>
  <?php include 'header.php';?>
  <div id="adapt">
    <div id="article">
      <?php if($focus_article){foreach($focus_article as $key=>$value){ ?>
        <?php $focus_img = preg_replace('/^..\//','',$value->focus_img);//修正路徑*編輯新增路徑是在後台目錄下 ?>
        <div id="cover">
          <img id="cover-img" src="<?php echo  $focus_img;?>" alt="焦點">
        </div>
        <div id="content">
          <a id="img-link" href="article_detail.php?id=<?php echo $value->id;?>" title="點擊閱讀">
           <h3><?php echo $value->title;?></h3>
            <p><?php echo $value->summary?></p>
          </a>
          <span><a href="article_detail.php?id=<?php echo $value->id;?>">繼續閱讀</a></span>
        </div>
      <?php }}else{ echo '沒 任 何 資 料!';}?>
    </div>
    <div id="recipe-list">
      <h4>食譜</h4>
      <?php if($html){foreach($html as $value){?>
      <dl>
        <a href="recipe_detail.php?id=<?php echo $value->id?>">
          <dt>
            <img src="<?php echo $value->finish_img;?>" alt="" srcset="">
          </dt>
          <dt><span class="dishName"><?php echo $value->title;?></span></dt>
          <dd><span class="author">by <?php echo mb_substr($value->nickname,0,3).'...';?></span><span class="date"><?php echo $value->date;?></span></dd>
        </a>
      </dl>
      <?php }}?>
    </div>
  </div>
  <!-- <div id="footer" >bottom</div> -->
</body>
</html>