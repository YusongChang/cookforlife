<?php
  require 'require.php';
  $page = 'art';
  $focus = new FocusArticleAction();
  $focus->action();
  $focus_html = $focus->getAllArticle();
  $focus_editor = $focus->getAllEditor();
  
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>文章管理</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/common.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/bcHeader.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/article.css">
</head>
<body>
<?php include 'header.php' ;?>
  <div id="adapt" style="text-align:center;">
    <!-- content -->
    <div id="focus-title">
      <span>焦點文章</span>
    </div>
    <div id="menu-area">
      <div id="menu-bar">
        <a id="add-btn" href="focusArticle_add.php?act=add" title="新增文章">+</a>
      </div>
    </div>
    <div id="table-area">
      <table>
        <?php if(isset($focus_html)){ ?>
        <tr><th>標題</th><th>摘要</th><th>作者</th><th>發布日期</th><th>修改日期</th><th>修改人</th><th>狀態</th><th>操作</th></tr>
        <?php foreach($focus_html as $key=>$value){?>
        <tr>
          <td id="td-title"><a href="../article_detail.php?id=<?php echo $value->id;?>"><?php echo $value->title;?></a></td>
          <td id="td-content"><a href="../article_detail.php?id=<?php echo $value->id;?>"><?php echo mb_substr($value->summary,0,15).'...';?></a></td>
          <td><?php echo $value->nickname;?></td>
          <td><?php echo $value->date;?></td>
          <td><?php echo $value->modify_date ? $value->modify_date:'尚未修改';?></td>
          <td><?php echo $value->modify_date ? $focus_editor[$key]->nickname:'尚未修改';?></td>
          <td><?php echo $value->state;?></td>
          <td><a href="<?php echo 'focusArticle_update.php?id='.$value->id; ?>" class="edit-btn">edit</a></td>
        </tr>
        <?php }}else{ echo '沒有任何資料!';}?>
      </table>
    </div>
  </div>
</body>
</html>