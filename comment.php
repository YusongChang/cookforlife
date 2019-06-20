<?php
  require dirname(__FILE__).'/init.php';

  $details = new DetailsAction();

  $details->action();

  $comNums = $details->commentNums; //留言數

  $comment = $details->comment_object; //留言

  $reply = $details->reply_object; //回覆留言

  //留言編輯權限!!!!!


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $html->title; ?></title>
  <!-- StyleSheets -->
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/basic.css">
  <link rel="stylesheet" type="text/css" media="screen" href="stylesheet/comment.css">
  <!--- JS Source Files -->
  <script src="js/comment.js"></script>
  <script src="js/jquery-3.3.1.min.js"></script>
</head>
<body>
  <script>
    $(document).ready(function(){
      updateListener(); 
    });
  </script>
  <div id="comment">
    <div id="write-comment" class="write-comment">
      <h3>
        留言區
        <span> 
        <?php if($comNums) echo $comNums; else echo '0';?>  
          則留言
        </span>
      </h3>
      <h4 id="leave-head">
        <?php if(isset($_COOKIE['cook'])){ ?>
          <img src="uploaded/self_photo/22.gif" alt="頭像" style="">
          <a href="#" style=""><?php echo @$_COOKIE['cook'];?></a>
          <span class="word-limit">(500字)</span>
        <?php }else{?>
          <a class="login-link" href="user_login.php" target="_parent" style="">尚 未 登 入 </a>
        <?php }?>
      </h4>
      <?php if(isset($_COOKIE['cook'])){ ?>
        <div id="text-div">
          <textarea id="comment-text" cols="30" rows="10" placeholder="<?php if(isset($_COOKIE['cook'])) echo @$_COOKIE['cook'].'，';?>留下你的意見，與作者討論!"></textarea>
        </div>
        <div id="send">
          <button id="comment-send">send</button>
          <span><a href="">(留言,表示同意本站條款)</a></span>
        </div>
      <?php }?>
    </div>
    <div class="comment-list">
      <h4 id="list-head">留言列表</h4>
      <ol>
        <?php if($comment){ $count=0; $floor=1;foreach($comment as $key => $cvalue){?>
          <span id="<?php echo $floor.'F';?>" class="floor-span" ><?php echo $floor++.' F ';?></span>
          <li>
          <img src="uploaded/self_photo/22.gif" alt="頭像" style="">
          <a style=""><?php echo $cvalue->nickname;?></a>
          </li>
          <li>
          <span><?php echo $cvalue->content;?></span>
          <p><a class="reply-btn" no="<?php echo $count;?>">回覆</a><span class="date-span" time="<?php echo $cvalue->timestamp;?>"> <?php echo $cvalue->date;?></span></p>
          <!-- 回覆功能 -->
          <?php if(isset($_COOKIE['cook'])){?>
            <div class="reply">
              <textarea name="reply" cols="30" rows="10" placeholder="你的回覆..."></textarea>
              <div id="send">
                <button class="reply-send" pid="<?php echo $cvalue->id?>" replyid="<?php echo $cvalue->user_id;?>" no="<?php echo $count++;?>" >send</button>
                <span><a href="#">(留言,表示同意本站條款)</a></span>
              </div>
            </div>
          <?php }?>
          </li>
          <?php if($reply){ foreach ($reply as $k => $rvalue){?>
            <?php if($rvalue->pid === $cvalue->id){?>
              <li>
                <img src="uploaded/self_photo/22.gif" alt="頭像" style="">
                <a style=""><?php echo $rvalue->nickname;?></a>
              </li>
              <li>
                <span>
                  <strong style="font-weight:normal;color:green">
                    回覆 
                    <?php
                      if($cvalue->user_id === $rvalue->reply){ 
                        echo $cvalue->nickname.' &rarr;</strong> '.$rvalue->content;
                      }else{
                        echo $rvalue->uuser.' &rarr;</strong> '.$rvalue->content;
                      }
                    ?>
                </span>
                <p><a class="reply-btn" no="<?php echo $count;?>">回覆</a><span class="date-span" time="<?php echo $rvalue->timestamp?>"> <?php echo $rvalue->date;?></span></p>
                <!-- 回覆功能 -->
                <?php if(isset($_COOKIE['cook'])){?>
                  <div class="reply">
                      <textarea name="reply" cols="30" rows="10" placeholder="你的回覆..."></textarea>
                      <div id="send">
                        <button pid="<?php echo $cvalue->id;?>" replyid="<?php echo $rvalue->user_id;?>" class="reply-send" no="<?php echo $count++;?>">send</button>
                        <span><a href="">(留言,表示同意本站條款)</a></span>
                      </div>
                  </div>
              <?php }?>
              </li>
            <?php }?>
          <?php }}?>
          <hr/>
          <?php }}else{?>
          <li>
            <?php echo "無 任 何 留 言 .....";?>
          </li>
      <?php }?>
      </ol>
    </div>
  </div>
</body>
</html>