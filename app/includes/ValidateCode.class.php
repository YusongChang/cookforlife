<?php

/**

* 驗證碼類

*/

class ValidateCode{

    private $img;             //圖像資源句柄

    private $width = 130;     //圖像寬度

    private $height = 52;     //圖像高度

    private $char;            //驗證碼隨機因子

    private $code;            //驗證碼

    private $codelen = 4;     //驗證碼長度

    private $grids_space = 4; //網格線間隔 (* 不可小於 4，否則網格排列過密)

    private $grids;           //網格線數量

    private $font;            //字體 TrueType 路徑

    private $fontsize = 20;   //字體大小

    private $grid_style;      //網格形式 斜線"slash" 垂直"verticle"

    private $uplow;           //驗證碼輸入 區分大小寫"yes"，反之"no"

    /** 初始化 */

    public function __construct($uplow="yes",$grid_style="verticle"){

      $this->char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 

      $this->font = ROOT_PATH.'/app/font/elephant.ttf';

      $this->grid_style = $grid_style;

      $this->uplow = $uplow;

      if(ob_get_length() > 0) ob_clean (); //清除緩存

    }

    

    /** 生成背景 */

    private function createBg(){

        $this->img = @imagecreatetruecolor($this->width,$this->height)  //新建一個真彩圖像，預設為黑色

                    or die('Failed to Open GD Image Stream!');         

        $color = imagecolorallocate($this->img,255,247,232);            //顏色-淡黃色

        imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);

    }



    /** 繪製線條網格 */

    private function lineGrid($type){

      

      $color = imagecolorallocatealpha($this->img,mt_rand(156,230),mt_rand(156,230),mt_rand(156,230),70);

      imagesetthickness($this->img,2);

      if ($type=="slash"){ //=== 繪製斜線網格 ===

        for($i = $this->grids_space; $i <= $this->height; $i+=$this->grids_space){

          //--- 繪製 / 的線條 ---

          imageline($this->img,$i,0,0,$i,$color);// x1 從 4 算起, y1 = 0 ||  x2 = 0 , y2 從 4 算起

          // --- 繪製 \ 的線條 ---

          imageline($this->img,($this->width)-$i,0,$this->width,$i,$color);// x1 從 130-4 算起, y1 = 0 ||  x2 = 130 , y2 從 4 算起

   

         }

         for($j = $this->grids_space; $j <= ($this->width-$this->height); $j+=$this->grids_space){

          //--- 接續上一個迴圈 繪製 / 的線條 ---

          imageline($this->img,$j+($this->height),0,$j,$this->height,$color); // x1 從 52 算起, y1 = 0 || x2 從 2 算起, y2 = 0  

          imageline($this->img,$this->width,$j-2,($this->width-$this->height-2)+$j,$this->height,$color); // x1 從 130 算起, y1 = 4(-2 修正間距) 算起|| x2 從 130-52(-2，修正間距) 算起, y2 = 52 算起 

          // --- 繪製 \ 的線條 ---

          imageline($this->img,($this->width-$this->height-$j),0,$this->width-$j,$this->height,$color); // x1 從 130-52-4 算起, y1 = 0 || x2 從 130-4 算起, y2 = 52  

          imageline($this->img,0,$j-2,$this->height-$j+2,$this->height,$color); // x1 從  算起, y1 = 4(-2 修正間距) 算起 || x2 從  52-4(+2 修正間距)算起, y2 = 52 

         }

      }else if ($type == "verticle"){ //=== 繪製直線網格 ===

        //繪製 —— 線條

        for($i = $this->grids_space; $i <= $this->height; $i+=$this->grids_space){

          imageline($this->img,0,$i,$this->width,$i,$color);// x1 從 4 算起, y1 = 0 ||  x2 = 0 , y2 從 4 算起

        }

        //繪製 | 線條

        for($j = $this->grids_space; $j <= $this->width; $j+=$this->grids_space){

          imageline($this->img,$j-1,0,$j-1,$this->height,$color);// x1 從 4(-1,修正) 算起, y1 = 0 ||  x2 = 4(-1,修正) , y2 從 4 算起

        }

      } else exit ('Didn\'t Input param either of (slash) and (verticle) ! ');

      

    }



    /** 生成驗證碼 */

    public function createCode(){

      for($i=0; $i < $this->codelen;$i++){

        $this->code.=$this->char[mt_rand(0,strlen($this->char)-1)];

      }

    }



    /** 繪製驗證碼文字*/

    public function codeString(){



      $color = imagecolorallocate($this->img, 0, 0, 0);



      for($i = 0; $i<strlen($this->code); $i++){

        imagefttext($this->img,

                    $this->fontsize,

                    0,

                    $i*($this->width/$this->codelen)+($this->fontsize/4),

                    $this->height/1.4,

                    $color,

                    $this->font,

                    $this->code[$i]);

      }

    }



    /** 生成驗證碼圖像 */

    public function CreateCodeImg(){

      $this->createBg();

      $this->createCode();

      $this->codeString();

      $this->lineGrid($this->grid_style);

  }



  /** 輸出驗證碼圖像 */

  public function outputCodeImg(){

    $this->CreateCodeImg(); //生成驗證碼圖像

    header('Content-Type: image/png');

    imagepng($this->img);

    imagedestroy($this->img);

  }



  /** 取得驗證碼 */

  public function getCode($uplow = "yes"){

    $code = null; //存放驗證碼

    if($this->uplow == "yes") $code = $this->code;

    else if($this->uplow == "no") $code =strtolower($this->code);

    else exit('Didn\'t Input param either of (yes) and (no) ! ');

    return $code;

  }

}  

?>