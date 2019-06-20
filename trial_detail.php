<?php
  require dirname(__FILE__).'/init.php';

  $trial = new TrialAction();

  if(@$_GET['act'] == 'show') {
    $trial->action();
    $list = $trial->trial;
    $trialNum = $trial->getTrialTotal();
  }elseif (@$_GET['act'] == 'update') {
    $trial->action();
    $edit = $trial->edit;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <!-- StyleSheets -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/basic.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/trial_detail.css">
  <!--- Script Source Files -->
  <script src="js/trial_detail.js"></script>
  <script src="js/uploadImage.js"></script>
  <!-- JS script   -->
  <script>
    window.onload = function(){
      updateListener();
    };
  </script>
</head>
<body>
<div id="adapt">
<?php if(@$list) {?>
  <h4>已有 <span id="trialNum"><?php echo $trialNum;?></span> 人試作</h4>
  <!-- 以下顯示各個會員的跟著作的資料 -->
  <?php foreach($list as $value){?>
  <?php if($value->iid == @$_COOKIE['cookied']){ ?>
  <dl style="background-color:#fff1c2;">
  <?php }else{?>
  <dl>
  <?php }?>
    <dt ><span><?php echo $value->user; ?>的試作</span></dt>
    <dd><img src="<?php echo $value->finish_img; ?>" alt=""/></dd>
    <dd id="summary"><?php echo $value->summary; ?></dd>
  <?php if(@$_COOKIE['cookied'] == $value->iid){ ?><dd style="text-align:right;"><a href="?act=update&follow=<?php echo $value->follow;?>&id=<?php echo $value->id;?>" style="cursor:pointer;margin-right:10px;font-weight:bolder;font-size:14px;">>> 編輯</a></dd><?php }?>
  </dl>
<?php } }?>
<?php if(@$edit){?>
  <h3 style="color:gray;"><?php echo $edit->user; ?> 的試作</h3>
  <div id="finish-trial" class="finish-trial" origin="<?php echo $edit->finish_img;?>" style="background:url('<?php echo $edit->finish_img;?>') no-repeat left center">
    <canvas id="finishImg"></canvas>
    <input accept="image/*" type="file" name="" id="trial-img">
  </div>

  <div id="edit-p"> <p>點擊照片修改</p></div>
  <div class="finish-text">
    <h4>試 作 心 得<span> (500字)</span></h4>
    <textarea name="" id="finish-summary"  class="finish-summary" placeholder="寫些試作心得吧!"><?php echo $edit->summary?></textarea>
    <div><input  class="enter-btn" type="button" id="trial-send" name="send" value="確認修改"></div>
  </div>
  <div style="text-align:right;width:700px;max-with:100px;"><a href="?act=show&follow=<?php echo $edit->follow;?>&id=<?php echo $edit->id;?>" style="cursor:pointer;margin-right:10px;font-weight:bolder;font-size:14px;">>> 看其他人試作</a></div>
<?php }?>
</div>
</body>
</html>