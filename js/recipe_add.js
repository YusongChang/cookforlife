var no, 
    dt_x=0, 
    dt_y=0, 
    form, 
    imgType,
    filename, 
    filesize, 
    ctx, 
    parentBg,
    send,
    food_name = document.getElementsByName('food_name[]'),
    food_unit = document.getElementsByName('food_unit[]'),
    summary = document.getElementsByName('summary'),
    step = document.getElementsByName('step[]'),
    title = document.getElementsByName('title'),
    dataURL = [];// Image source URL

/** 在 頁面載入時添加事件 */
function updateListener(){
  const inputFile = document.getElementsByName('img[]');
        send = document.getElementById('send');
  for(var i = 0; i<inputFile.length; i++){
      inputFile[i].addEventListener('input',preview,true); //等同於 input 的 change 
    }
  send.addEventListener('click',checkFormData,true);
}


/** Input File OnChange Event */ 
function preview(event){
  const reader = new FileReader(), //set a file reader to read image URL
        img = new Image(), // set an image source，最後的長度還是依照 drawThumbNailImage() 的 size，所以沒設定沒差
        canvas = document.getElementsByTagName('canvas');      
  
  if(event.target.files[0] == undefined)
  {
    alert('Warnning : No any Image was selected!');
    return;
  }

  no = event.target.getAttribute('no'); //input  的 屬性 no 的值 用來辨別 第幾個 input tag 和 canvas 

  // ---- 設置 canvas 尺寸 ----
  if(event.target.getAttribute('class') != 'step_img'){
    canvas[no].width = 360;    //固定容器 size 賦予 canvas * 若不設定，Chrome 預設 canvas  為 300 * 150
    canvas[no].height = 270;
  }else{
    canvas[no].width = 240;    
    canvas[no].height = 180;
  }

  ctx = canvas[no].getContext('2d'); //get canvas 2d 背景物件
  
  // ---- 獲取 圖檔資源 ----
  if(event.target.files[0] != undefined ){

    reader.onloadend = function(){

      if(no == 0 ){ //只有 finish imag 需要使用

        filename = event.target.files[0].name;
        filesize = event.target.files[0].size ;

      }
      parentBg = event.target.parentElement; //取得父標籤 obj 刪除背景用
      img.src = reader.result;
      getOrientation(event.target.files[0],img,canvas[no]);
      img.onload = FileBgInfo;//載入圖片時顯示圖片資訊
    }
    reader.readAsDataURL(event.target.files[0]);  // read image URL 是 base64 文件
  }
  
  // imgType = event.target.files[0].type; //紀錄圖像類型
}


/*** 處理圖片資訊與背景 ***/
function FileBgInfo() {
  var pixel,autocrop;
  const span_crop = document.getElementById('autotrim'),
        span_crop2 = document.getElementById('autotrim2'),
        span_filesize = document.getElementById('filesize');
  
  //---- Will draw the image as the custom size of Width and Height ---
  parentBg.style.background="url('')"; //父標籤的背景改空
  parentBg.style.border="none"; //父標籤的邊線改空
  //---- 成品圖片是否經過系統修剪，並顯示詳細資料 ---
  if(no != 0) return; // finish image only
  if( this.width < this.naturalWidth  || this.height < this.naturalHeight )
  {
    autocrop = 'Yes! ('+String(this.width)+' x '+String(this.height)+')';
    span_crop.style.color="orange";
    span_crop.style.border="1px dashed #fc7a01";
    span_crop2.style.color="orange";
    span_crop2.style.border="1px dashed #fc7a01";
  }
  else autocrop = 'None.';

  filesize = filesize /1024; // 轉 KB
  filesize = Math.floor(filesize);

  if(filesize < 1024 ){
    filesize = String(filesize)+' KB'; //小於1024 KB (1 MB)
    span_filesize.style.color="black";
  }else{
    filesize = filesize/1024;
    if(filesize > 2){
      alert('警告: 上傳的圖片大小: '+filesize+',超過 2 MB 的限制!');
      span_filesize.style.color="red";
    }
    filesize = String(filesize)+' MB'
  }

  pixel = String(this.naturalWidth)+' x '+String(this.naturalHeight);
  showFileDetails(filename,filesize,pixel,autocrop);
}

/** 顯示 上傳文件詳細資料
 * @param String name   檔名
 * @param String size   檔案大小
 * @param String pix    像素
 * @param String crop   自動修剪
*/
function showFileDetails (name,size,pix,crop){
  var filename = document.getElementById('filename');
  var filesize = document.getElementById('filesize');
  var pixel = document.getElementById('filepixel');
  var trim = document.getElementById('autotrim');
  var filename2 = document.getElementById('filename2'); //screen size 變小 會用到
  var filesize2 = document.getElementById('filesize2');
  var pixel2 = document.getElementById('filepixel2');
  var trim2 = document.getElementById('autotrim2');

  filename.innerHTML = name;
  filesize.innerHTML = size;
  pixel.innerHTML = pix;
  trim.innerHTML = crop;
  filename2.innerHTML = name;
  filesize2.innerHTML = size;
  pixel2.innerHTML = pix;
  trim2.innerHTML = crop;
}

// /** 縮放 原始圖片大小 (固定 寬*高 的容器，等比例縮放) */
// function resizeIntrinsicImage(img){

//   //取得原始圖片 size
//   var imgW = img.naturalWidth,
//       imgH = img.naturalHeight;

//   //取得畫布大小    
//   var cw = canvas[no].width,
//       ch = canvas[no].height;

//   //* 原始圖片 寬*高 size  都小於 canvas 長度 ，則不執行縮放
//   //* 但執行計算放置位置
//   if(imgW < cw && imgH < ch){
//     dt_x = (cw-imgW)/2  //水平置中，避免圖片太小時貼在左上角
//     dt_y = (ch-imgH)/2  //垂直置中，避免圖片太小時貼在左上角
//     return; 
//   } 

//   // --- 計算 原始圖片 寬*高 需要的 縮放比例 ---
//   // (以容器 canvas size 為主的強制縮放，避免因 size 超過 或 小於 canvas 而失真)
//   if (imgW > imgH){
//     img.height = imgH*(cw/imgW);
//   }else{
//     img.width = imgW*(ch/imgH);;
//   }
//   console.log('imgW = '+imgW+'; imgH = '+imgH+'; dt_x = '+dt_x+"; dt_y = "+dt_y);
//   // --- 計算欲繪製至目標上的位置，並修正 size ---
//   imgW = img.width;
//   imgH = img.height;
//   // X 軸
//   if(imgW < cw){
//     dt_x = (cw-imgW)/2  //水平置中，避免圖片太小時貼在左上角
//   }else{
//     img.width = cw;     //若大於如容器 canvas ，則 賦予 canvas size
//   }
//   // Y 軸
//   if(imgH < ch){
//     dt_y = (ch-imgH)/2  //垂直置中，避免圖片太小時貼在左上角
//   }else{
//     img.height = ch;    //若大於容器 canvas ，則 賦予 canvas size
//   }
//   console.log('img.width = '+img.width+'; img.height = '+img.height+'; dt_x = '+dt_x+"; dt_y = "+dt_y);
// }


/**
 * 檢查表單資料，並發送 Post Request
 * @return {Boolean} 
 */
function checkFormData(){
  const draft = document.getElementById('draft').checked;
  // ---- 檢查上傳圖片，與步驟說明 ----
  if(dataURL[0] == undefined){
    alert('尚 未 上 傳 成 品 照 !');
    return false; 
  }else if( dataURL.length < 1){ //沒有上傳任何圖片
    alert('請 至 少 上 傳 成 品 照 ，謝 謝 !');
    return false; 
  }

  if(step.length < 0 ){
    alert('步驟 至 少 須 填 寫 1 列!');
    return false;
  }

  var k; //圖片陣列索引
  for(var i = 0; i < dataURL.length; i++){
      k = i+1; 
    if(dataURL[k] != undefined){ //步驟圖是從 索引 1 開始
      if(step[i] == undefined) continue; //若被刪除則不執行
      if(step[i].value ==''){
        alert('步驟 '+k+'. 說 明 尚 未 填 寫!');
        return false; 
      }
    }
  }
  // ---- 檢查 標題、心得、材料、步驟文字 ----
  if(title[0].value.length < 2 || title[0].value.length > 20){
    alert('標 題 長 度 不 得 小 於 " 2 " 個字 或 大 於 " 20 " 個字 !');
    return false; 
  }

  if(summary[0].value.length > 500){
    alert('烹 飪 心 得 內 容 長 度 不 得 大 於 " 500 " 個字 !');
    return false; 
  }

  var count = 0;
  for(var i = 0; i < food_name.length; i++){
    if((food_name[i].value === "") && (food_unit[i].value === "") ) continue;
    if(food_name[i].value){
      if(food_unit[i].value === ""){
        alert('材料第 '+(i+1)+'.列 的 數 量 尚 未 填 寫!');
        return false;
      }  
    }else{
      alert('材料第 '+(i+1)+'. 列 的 材 料 尚 未 填 寫!');
      return false;
    }
    count++;
  }
  if(count < 1){
    alert('準 備 材 料 尚 未 填 寫!');
    return false;
  }
  var imgArr = imagDataToArr(dataURL),
      food_nameArr = nodeListToArr(food_name),
      food_unitArr = nodeListToArr(food_unit);
      stepArr = nodeListToArr(step);
  var request = "send="+send.value
              +"&title="+title[0].value
              +"&summary="+summary[0].value
              +"&food_name="+JSON.stringify(food_nameArr)
              +"&food_unit="+JSON.stringify(food_unitArr)
              +"&img="+JSON.stringify(imgArr)
              +"&step="+JSON.stringify(stepArr)
              +"&draft="+draft;

  sendRequest(request,ajaxResult);
}

/**
 * Get Data of Nodelist Object to Array
 * @param {NodeList}
 * @return {Array}
 */
function nodeListToArr(nodelist){
  if(nodelist == undefined) return;
  var arr = []; //new array object
  for(var i = 0; i < nodelist.length; i++) arr.push(nodelist[i].value);
  return arr;
}

/** 
 * 取得圖像資料，去除陣列空值，返回陣列
 * Get Data of Image Nodelist Object to Array
 * @param {NodeList}
 * @return {Array}
 */
function imagDataToArr(nodelist){
  if(nodelist == undefined) return;
  var imgArr = []; //new array object
  for(var key in nodelist){
    if(nodelist.hasOwnProperty(key)){ //有索引值
      if(nodelist[key] == '') continue; //是空元素不繼續執行
      imgArr[key] = nodelist[key];
    }
  }
  return imgArr;
}

/**
 * 使用 AJAX 向伺服器發送請求 
 * XMLhttpRequest send POST Request to server
 */
function sendRequest(request,cFunction){

const xhttp = new XMLHttpRequest();
  xhttp.open("POST","editRecipe.php?act=add",true);
  xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  xhttp.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      console.log(this.responseText);
      cFunction(this);
    }
  }
  xhttp.send(request);
}

/** Ajax 傳值結果 */
  function ajaxResult(xhttp){
    if( !isNaN(parseInt(xhttp.responseText)) ){ //回傳 食譜 id 正整數型別   
        window.onbeforeunload = null; //移除 onbeforeunload 事件
        alert('食 譜 新 增 成 功!');
        location.href = 'recipe_detail.php?id='+xhttp.responseText;
    }else if(xhttp.responseText === 'UNLogin'){
        window.onbeforeunload = null; //移除 onbeforeunload 事件
        alert(xhttp.responseText); //輸出錯誤訊息
        location.href ='user_login.php';
    }else{
         alert(xhttp.responseText); //輸出錯誤訊息
    } 
     
 }


