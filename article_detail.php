<?php
  /** 新增文章 */
  require dirname(__FILE__).'/init.php';
  $article = new FocusArticleAction();
  $html = $article->getDetail();
  $modify = $article->getEditor();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>閱讀文章</title>
  <!--- Stylesheet Source Files -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/basic.css">
  <link rel="stylesheet" href="stylesheet/header.css">
  <link rel="stylesheet" href="stylesheet/article_detail.css">
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
  <div id="page-bar">
        <ol>
          <li><a href="./">首頁</a></li> > 
          <li><a id="dir-currentTag" style="">生活資訊</a></li>
        </ol>
    </div>

  <div id="adapt">
    <div id="article-title">
      <h1><?php echo $html->title;?></h1>
    </div>
    <div id="publish-time">
      日期: <?php echo $html->date;?>
    </div>
    <div id="summary">
      <p><?php echo $html->summary?></p>
    </div>
  </div>
  <div id="sec-adapt">
    <div id="content">
      <div id="content-text">
        <?php echo $html->content;?>
      </div>
    </div>
    <div id="author-time">
      <div id="author">
        作者: <strong><?php echo $html->nickname;?></strong>
      </div>
      <div id=update>
      <?php if($html->modify_date){?>
        編輯: <?php echo $html->modify_date;?> By <strong><?php echo $modify->nickname;?></strong> 
      <?php }?>
      </div>
    </div>
    <div class="bottom-bar"><a href="./">返回首頁</a></div>
  </div>
</body>
</html>