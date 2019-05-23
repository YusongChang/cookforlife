<?php



/** 解析 Javascript 上傳圖片*/ 

class UpImage{

  private $path_arr; //空陣列，存放生成的圖片路徑

  private $src_w;    // image source intrinsic width

  private $src_h;   // image source intrinsic height

  private $dst_w;   // customized width

  private $dst_h;   // customized height

  private $type;    //image source type





  /** 生成上傳檔案的相對路徑 ，返回 陣列

   * @param String $folderName : folder name(Path) of Upload Images ex:uploaded/pic

   * @param Int $number : 個數

   * @return Array 路徑陣列;

  */

  public function createImgFolder(&$folderName='uploaded',&$number = 1){

    $img_path = array(); //存放生成的圖片路徑

    // $dir = './'.$folderName;//在專案目錄底下 ===> UBUNTU 不行
    $dir = './'.$folderName;//在專案目錄底下

    if(!file_exists($dir)){//如果目錄不存在則建立

      if(!mkdir($dir,777,true)) exit('Failed to create folders'); //建立目錄，windows 0777 Linux 777

    }

    for($i=0; $i < $number; $i++){

      $img_path[] = $folderName.'/'.$i.'-'.$_COOKIE['cookied'].'-'.date('Ymd').'-'.date('His',time()).'.png';  // 檔名路徑設置，避免重複 ， * file_put_contents method 執行速度太快了，time()值會一樣

    }

    return $img_path; 
  }



  /** 執行上傳動作 

  *@param Mixed $imgData : Images source data

  *@param Array $fileArr : Include String of files path of Array

  */

  public function uploadImg($imgData,$pathArr){

    // ---- 處理 javascript POST Request (上傳檔案) ---- 

    if(!empty($imgData)){

      if(!is_array($pathArr)) Tool::errorMsg('Second parameter Not an Array of the Path');

      $this->imgDataDecode($imgData,$pathArr);

      // ---- PHP $_FILES 處理上傳檔案 ---- 

      // $nameArray = explode('.',$_FILES['img']['name']); // 將 filename extension  取出

      // $new_name = date('Ymd').mt_rand(100,1000).'.'.$nameArray[1]; // new file's name

      // list($width,$height) = getimagesize($_FILES['pic']['tmp_name']);

      // if(is_uploaded_file($_FILES['pic']['tmp_name'])){

      //   if(!move_uploaded_file($_FILES['pic']['tmp_name'],$new_name)){

      //     exit('Files Failed to Upload !');

      //   }else{

      //     resizeIntrinsicImage($new_name,$this->src_w,$this->src_h,$this->dst_w,$this->dst_h);

      //   }

      // }

    }else{
      Tool::errorMsg('圖 像 資 源 不 存 在!');
    }

  }



  /** 

   * 解析 mime(img.png) base64 ，生成圖檔

   * @param Mixed $img : String type of Image data

   * @param Array $path： Uploaded New Image Path

   * @param Int $key : $path array then do Image data foreach $key will have values,so that we can access the file name  

   */

  private function imgDataDecode($img,&$path,&$key=null){

    if(is_array($img)){ //陣列則進行遞迴

      foreach($img as $k => $v){

        $key = $k;

        $this->imgDataDecode($v,$path,$key);

      }

    }else{

      $key = $key ? $key: 0; 

      if(empty($img)) return; //沒有圖片資源或沿用舊圖片則返回

      if(!is_string($img)) Tool::errorMsg('警告: 圖 片 資 源 非 String 型 別 !');

      $img = str_replace('data:image/png;base64,', '', $img);

      $img = str_replace(' ', '+', $img);     //沒將 blank 換成 + 會 解析失敗

      $data = base64_decode($img);

      $success = file_put_contents($path[$key], $data);

      $success ? true : Tool::errorMsg('Unable to save the file.');

      flush();//清除緩存區(避免浪費效能)

      // ---- 也可使用 imagecreateformstring method ----

      // $create_img = imagecreatefromstring($data); 從 string 建立 一個 圖像 資源也可以喔!

      // print( imagepng($create_img,$file) ? $file : 'Unable to save the file.');

      // imagedestroy($create_img); 

    }

  }



  /**

   * 將上傳的圖片 等比例 縮放

   */

  private function resizeIntrinsicImage($file,$src_w,$src_h,$dist_w,$dist_h){



    $img = imagecreatefromjpeg($file);



    if($src_w < $src_h){ 

      $dist_w = ($dist_h/$src_h)*$src_w; //原始圖 寬度 < 高度

    }else{

        $dist_h = ($dist_w/$src_w)*$src_h; //原始圖 寬度 > 高度

    }

    $new_img = imagecreatetruecolor($dist_w,$dist_h);

    //原圖 拷貝給 縮略圖

    imagecopyresampled($new_img,$img,0,0,0,0,$dist_w,$dist_h,$src_w,$src_h);

    imagejpeg($new_img,$file);

    imagedestroy($img);

    imagedestroy($new_img);

  }



  

}





?>