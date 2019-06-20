/** 
 * 修正相機拍攝 圖片旋轉、翻轉
*/
var x = 0,y = 0; //translate (x,y)

/** 取得 orientation info 
*@param {String} file 圖檔
*@return {Function} callback
*/
function getOrientation(file,img,canvas){
  var reader = new FileReader(),
      view = null,
      byteLen = 0,
      offset = 0,
      edian,
      entries;

  reader.onloadend = function(){
    view = new DataView(reader.result); //取得 file 位元資料
    byteLen = view.byteLength; //資料長度
    
    if(view.getUint16(offset,false) !== 0xFFD8) return resetOrientation(img,false,canvas); //找不到 SOI Maker 表示非 JPEG
    
    offset += 2;
    
    if(view.getUint16(offset,false) !== 0xFFE1) return resetOrientation(img,false,canvas); //找不到  APP1 Marker，就結束，若有則繼續執行
    
    while (offset < byteLen){
      if(view.getUint32(offset,false) === 0x45786966){ //尋找 APP1 Data 裡的 Exif Header;
        //移動到 TIFF Header data Info
        endian = view.getUint16(offset += 6,false) === 0x4949; // TIFF Header 包含 Exif Header 的讀取資料順序 0x4d4d: big endian, 0x4949: little endian
        
        if(view.getUint32(offset + 4,edian) > 8) offset+=8; // *本人電腦只能以 little edian 讀取資料
        else offset += view.getUint32(offset + 4,edian);
        
        entries =  view.getUint16(offset); //計算IFD (Image File director Entry 簡稱 DE ) 實體數量
        offset += 2; //移動到第一個 DE 
        for(var i = 0; i < entries; i++){
          var tagOffset = offset + (i*12); //每個 DE 資料長度 為 12 bytes
          tagName = view.getUint16(tagOffset,edian); //每個 DE 的 Tag name 長度為 4 bytes
          //尋找 tag name 為 orientation 
          if(tagName === 0x0112) return resetOrientation(img,view.getUint16(tagOffset + 8,edian),canvas);//在第8位開始是 Orientation value
          else if(tagName === 0x1201) return resetOrientation(img,view.getUint8(tagOffset + 8,edian),canvas);//在第8位開始是 Orientation value *本人電腦只能以 little edian 讀取資料
        }
        //若找不到 Orientation Tag 就回傳 false
        return resetOrientation(img,false,canvas); 
      }
      offset += 2;
    }
    return resetOrientation(img,false,canvas);
  }
  reader.readAsArrayBuffer(file);
}

/** 重新將照片調整回原位置
 * @param {source} img 圖片資源
 * @param {Int} orientation 照片旋轉角度代碼
 */
function resetOrientation(img,orientation,canvas){
      var ctx = canvas.getContext('2d');
  // alert(orientation);
  resizeIntrinsicImage(img,canvas); //縮放圖片
  
  switch(orientation){

    case 2 ://照片被水平鏡射
            x = img.width;
            y = img.height+(canvas.height-img.height)/2
            ctx.transform(-1,0,0,1,x,y); //鏡射回來

    break;

    case 3 ://照片被旋轉 180 degree
            x = img.width;
            y = img.height+(canvas.height-img.height)/2
            ctx.transform(-1,0,0,-1,x,y);//鏡射回來
    break;

    case 4 ://照片被垂直鏡射
            x = img.width;
            y = img.height+(canvas.height-img.height)/2
            ctx.transform(1,0,0,-1,x,y);//鏡射回來
 
    break;

    case 5 ://照片被逆時針旋轉 90 degree + 垂直鏡射
            y = (canvas.height-img.width)/2
            ctx.transform(0,1,1,0,x,y); //逆時轉回，有鏡射效果
 
    break;

    case 6 ://照片被逆時針旋轉 90 degree
            x =  img.height+(canvas.width-img.height)/2;
            if(img.width < canvas.height) y = (canvas.height-img.width)/2  
            ctx.transform(0,1,-1,0,x,y); //順時轉回
 
    break;

    case 7://照片被順時針旋轉 90 degree 垂直鏡射 
            x = img.height;
            y = img.width+(canvas.height-img.width)/2
            ctx.transform(0,-1,-1,x,y);
    break;

    case 8://照片被順時針旋轉 90 degree
            x = (canvas.width-img.height)/2
            y = img.width
            ctx.transform(0,-1,1,0,x,y);//逆時針轉 90 degree 回來
    break;
    
    default:
      if(img.width < canvas.width){
        x = (canvas.width-img.width)/2  //水平置中，避免圖片太小時貼在左上角
      }else{
        img.width = canvas.width;
      }
      if(img.height < canvas.height){
        y = (canvas.height-img.height)/2  //垂直置中，避免圖片太小時貼在左上角
      }else{
        img.height = canvas.height;
      } 
      ctx.transform(1,0,0,1,x,y);//原始位置
    break;
  }
  drawImage(img,ctx);
  x = 0; y = 0;
  console.log('x: '+x+',y: '+y);
  if(no == undefined) var no = 0; //沒有定義可能就是 頭像圖片
  dataURL[no] = canvas.toDataURL('image/png',1.0); //按照 canvas 順序儲存
}

/** 縮放 原始圖片大小 (固定 寬*高 的容器，等比例縮放) */
function resizeIntrinsicImage(img,canvas){
  //取得原始圖片 size
  var imgW = img.naturalWidth,
      imgH = img.naturalHeight;
  //取得畫布大小    
  var cw = canvas.width,
      ch = canvas.height;

  // --- 計算 原始圖片 寬*高 需要的 縮放比例 ---
  // (以容器 canvas size 為主的強制縮放，避免因 size 超過 或 小於 canvas 而失真)
  if (imgW > imgH){
    img.height = imgH*(cw/imgW);
  }else{
    img.width = imgW*(ch/imgH);
  }
  // --- 修改圖片 size ---
  imgW = img.width;
  imgH = img.height;
  if(imgW > cw){
    img.width = cw;     //若大於如容器 canvas ，則 賦予 canvas size
  }
  if(imgH > ch){
    img.height = ch;    //若大於容器 canvas ，則 賦予 canvas size
  }
  console.log('img.width = '+img.width+'; img.height = '+img.height);
}

function drawImage(img,ctx){
  ctx.drawImage(img,0,0,img.width,img.height);
}



