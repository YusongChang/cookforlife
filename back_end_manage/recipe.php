<?php
  require 'require.php';    
  
  //=== 顯示食譜列表 ===
  $recipe = new RecipeAction();
  $recipe->action();
  $html = $recipe->getListObj();
  $page = "rep"; //header 用
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>食譜管理</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/common.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/bcHeader.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/recipe.css">
</head>
<body>
  <?php include 'header.php' ;?>
  <div id="adapt" style="text-align:center;">
    <!-- content -->
    <div id="head-title">
      <span>食譜管理</span>
    </div>
    <div id="sort-menu">
      <a href=""></a>
    </div>
    <div id="table-area">
      <table>
        <?php if($html){ ?>
        <tr><th>食譜名</th><th>作者</th><th>點閱數</th><th>收藏數</th><th>發布日期</th><th>修改日期</th><th>操作</th></tr>
        <?php foreach($html as $value){?>
        <tr><td><?php echo $value->title;?></td><td><?php echo $value->nickname;?></td><td><?php echo $value->hits;?></td><td><?php echo $value->collected;?></td><td><?php echo $value->date;?></td><td><?php echo $value->modify_date ? $value->modify_date:'尚未修改';?></td><td><a href="../recipe_update.php?act=update&id=<?php echo $value->id?>" class="edit-btn">編輯</a></td></tr>
        <?php }}else{ echo '沒有任何資料!';}?>
      </table>
    </div>
  </div>
</body>
</html>