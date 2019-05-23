<?php
  /** 新增文章 */
  require 'focusArticleEdit.php';
  $page="art";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>新增文章</title>
  <!--- Stylesheet Source Files -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/common.css">
  <link rel="stylesheet" href="stylesheet/bcHeader.css">
  <link rel="stylesheet" href="stylesheet/focusArticle_add.css">
  <link rel="stylesheet" type="text/css" href="../app/tool/simditor-2.3.23/styles/simditor.css" />
  <!--- JS Source Files -->
  <script type="text/javascript" src="js/focus_article.js"></script>
  <script src="js/uploadImage.js"></script>
  <script type="text/javascript" src="../app/tool/simditor-2.3.23/site/assets/scripts/jquery.min.js"></script>
  <script type="text/javascript" src="../app/tool/simditor-2.3.23/site/assets/scripts/module.js"></script>
  <script type="text/javascript" src="../app/tool/simditor-2.3.23/site/assets/scripts/hotkeys.js"></script>
  <script type="text/javascript" src="../app/tool/simditor-2.3.23/site/assets/scripts/uploader.js"></script>
  <script type="text/javascript" src="../app/tool/simditor-2.3.23/site/assets/scripts/simditor.js"></script>
</head>
<body>
  <?php include 'header.php';?>
  <script>
    window.onbeforeunload = unload; /*使用者 pressed 上一頁 或是 刷新頁面 則出現警告*/
    /*使用者 pressed 上一頁 或是 刷新頁面 則出現警告*/
    function unload(e){
      return "";
    }
  </script>
  <div id="page-bar">
        <ol>
          <li><a href="focusArticle.php?act=show">文章管理</a></li> > 
          <li><a id="dir-editTag" style="">編輯文章</a></li>
        </ol>
    </div>

  <div id="adapt">
    <div id="article-title">
      <span>標題 :</span>
     <input id="input-title" type="text" placeholder="" value=""> <span id="world-limit">(* 5 - 100個字)</span>
    </div>
    <div id="thumbnail-div">
      <span>封面圖:</span>
      <div id="thumb-img">
        <input type="hidden" name="inputThumb" id="inputThumb">
        <img id="thumbImg" src="" alt="" srcset="">
      </div>
    </div>
    <div id="summary">
      <span>摘要: (* 20 - 500 個字)</span>
      <div id="summ-div">
        <textarea name="" id="summ-text" cols="" rows="5"></textarea>
      </div>
    </div>
  </div>
  <div id="sec-adapt">
    <div id="content">
      <span>內文:</span>
      <div id="content-text">
        <textarea id="editor" name placeholder="輸入內文" autofocus><p></p></textarea> <!--- 重要須加上 <p></p>標籤以供 simditor 正確處理段落...（i.e. h1 h2...等等非 p 標籤)--->
      </div>
    </div>
    <div class="submit">
      <div id="draft-div">
        <label for=""><strong>存為草稿( 暫不公開 )</strong><input type="checkbox" name="draft" id="draft"></label>
      </div>
      <input class="enter-btn" type="button" id="send" name="send" value="確 認 送 出">
    </div>
    <div class="bottom-bar"><a href="./">返回上一頁</a></div>
  </div>
  <!-- Image Upload Modal -->
  <div id="upImgModal">
    <div id="modal-content">
      <div id="close-bar">
        <span id="close">&times;</span>
      </div>
      <div id="head-title">
        <h3>上 傳 圖 片</h3>
      </div>
      <div id="preview-Img">
        <canvas id="img-can"></canvas>
      </div>
      <div id="addImg-div">
        <a id="addImg-btn">+</a>
      </div>
      <div id="save-div">
        <a id="save-img">儲存</a>
      </div>
      <input type="file" id="inputImg"/>
    </div>
  </div>
  <!-- Thumbname Upload Modal -->
  <div id="thumbModal">
    <div id="modal-content">
      <div id="close-bar">
        <span id="close-btn">&times;</span>
      </div>
      <div id="preview-Img">
        <canvas id="thumb-can"></canvas>
      </div>
      <div id="addImg-div">
        <a id="addThumb-btn">上傳封面圖</a>
      </div>
      <div id="save-div"><a id="save-thumb">儲存</a></div>
      <input type="file" id="inputImg"/>
    </div>
  </div>

</body>
</html>