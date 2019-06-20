
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
  if(isset($_POST['add-img'])){
        //判斷文件錯誤類型
    if($_FILES['photo']['error'] > 0){
      switch ($_FILES['photo']['error']) {
        case '1':
          echo "<script>alert('警告: 上傳文件超過約定值 1');history.back()</script>";
          break;
        case '2':
          echo "<script>alert('警告: 上傳文件超過約定值 2');history.back()</script>";
          break;
        case '3':
          echo "<script>alert('警告: 僅有部分文件被上傳!');history.back()</script>";
          break;
        case '4':
          echo "<script>alert('警告: 沒有任何文件被上傳!');history.back()</script>";
          break;
      }
      exit();
    }
      //文件大小檢查 不得超過 1 MB
    if($_FILES['photo']['size'] > 1000000)
    {
      echo "<script>alert('文件大小不可超過 1 MB !')</script>";
    }
    //獲取文件的副檔名
    $_ext=explode('.',$_FILES['photo']['name']);
    //定義上傳檔案名稱，也可供父視窗調用
    $path = 'img/'.$_POST['dir'].'/'.time().'.'.$_ext[1];
    $filename ='../../'.$path;
    //父視窗的圖片 id (步驟序號)
    if (!empty($_POST['no'])){
     $id = $_POST['dir'].'-img-'.$_POST['no'];
     $input_id=$_POST['dir'].'img-'.$_POST['no'];
    }else{
       $id = $_POST['dir'].'-img';
       $input_id = $_POST['dir'];
    }
    //檢查臨時文件是否存在
    if(is_uploaded_file($_FILES['photo']['tmp_name'])){
        if(move_uploaded_file($_FILES['photo']['tmp_name'],$filename)){
          echo "<script>alert('上 傳 成 功!');
                /*window.opener.document.getElementById('photo-url').value='$path'*/;
                
                var img = window.opener.document.getElementById('$id');
                var banner_area = window.opener.document.getElementById('banner-area');
                var add_banner = window.opener.document.getElementById('add-banner');
                /* 新增食譜步驟圖片 */
                if (img != undefined){
                   img.src='$path'
                }
                /* banner 區域 */
                if( banner_area != undefined ){
                  banner_area.style.background=\"grey url('$path') no-repeat center\";
                  window.opener.document.getElementById('banner_title').innerHTML='';
                }

                /* hidden Input 填入值*/
                window.opener.document.getElementById('$input_id').value='$path';

                window.close();
              </script>";
        }else {
          echo "<script>alert('上 傳 失 敗!');</script>";
        }
    }
  }
  // === 檢查 網址中的變數 ===
  $no =null; //步驟圖片序號
  if(isset($_GET['act'])){
    if($_GET['act']=='finish') $dir = $_GET['act'];
    else if($_GET['act']=='step'){
      if(isset($_GET['no'])){
        $dir = $_GET['act'];
        $no = $_GET['no'];
      }else echo "<script>alert('警告: 非 法 操 作!');window.close();</script>";
    }else if($_GET['act']=='banner'){
      $dir = $_GET['act'];
    }else echo "<script>alert('警告: 非 法 操 作!');window.close();</script>";
  }else echo "<script>alert('警告: 非 法 操 作!');window.close();</script>";
?>
<!-- html 區塊須移至模板 -->
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
    <input type="hidden" name="dir" value="<?php echo $dir;?>" />
    <input type="hidden" name="no" value="<?php echo $no;?>" />
    <h1>選擇圖片</h1>
    <br>
    <br>
    <input type="file" name="photo" id="photo" accept="image/*">
    <br>
    <br>
    <input type="submit" name="add-img" value="添加圖片" class="submit">
</form>

<h4>—————————　預覽圖片　——————————</h4>
<!-- 功能尚未完成 -->
<img src="" alt="">