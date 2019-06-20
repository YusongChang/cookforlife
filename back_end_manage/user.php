<?php
  require 'require.php';

  //=== 顯示會員列表 ===
  $user = new UserAction();
  $user->action();
  $level = new LevelAction();
  $level->action();
  $page = "user"; //header 用
  $html = $user->getUserListObj();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>會員管理</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- stylesheet source file -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/common.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/bcHeader.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/user.css">
</head>
<?php include 'header.php' ;?>
  <div id="adapt" style="text-align:center;">
    <!-- content -->
    <div id="head-title">
      <span>會員管理</span>
    </div>
    <div id="table-area">
      <table>
        <?php if(isset($html)){ ?>
        <tr><th>會員名</th><th>等級</th><th>頭像</th><th>最近登入 IP</th><th>最近登入時間</th><th>註冊日期</th><th>修改日期</th><th>操作</th></tr>
        <?php foreach($html as $value){?>
        <tr>
          <td><?php echo $value->user;?></td>
          <td><?php echo $value->name;?></td>
          <td><img width=72 height=72 src="../<?php echo $value->photo? $value->photo:'sprite/unknowUser.svg'?>" alt=""></td>
          <td><?php echo $value->last_ip;?></td>
          <td><?php echo $value->last_time;?></td>
          <td><?php echo $value->reg_date;?></td>
          <td><?php echo $value->modify_date ? $value->modify_date:'尚未修改';?></td>
          <td><a href="<?php echo '../userProfile_update.php?id='.$value->id; ?>" class="edit-btn">edit</a></td>
        </tr>
        <?php }}else{ echo '沒有任何資料!';}?>
      </table>
    </div>
  <!-- JS source file -->
  <script src="js/user.js"></script>
  </div>