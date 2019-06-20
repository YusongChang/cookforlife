<?php
  require 'require.php';
  if(isset($_POST['img']) && !empty($_POST['img'])){
    $imgData = json_decode($_POST['img']); //從JSON格式轉回陣列或是字串
    $upimg = new UpImage();
    $path = '../uploaded/article_img';//位址在主目錄下第一層
    $img_src = $upimg->createImgFolder($path); //生成圖片路徑,圖片檔案數預設 1 個
    $upimg->uploadImg($imgData,$img_src);
    echo file_exists($img_src[0])? $img_src[0] : false;
  }
?>