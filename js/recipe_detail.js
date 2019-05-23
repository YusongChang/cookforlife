var press_trial = 1,  //點擊試作分享鈕次數初始值
    press_share = 1,  //點擊分享鈕次數初始值
    press_reply = 1,  //點擊回覆鈕次數初始值
    re_tmp = null,    //回覆鈕序號暫存
    ctx,              //canvas 物件用
    parentBg,         //父標籤背景物件
    no = 0,           //紀錄第幾個 input file 序號
    dataURL=[],       // Image source URL
    canvas,
    dt_x = 0,dt_y = 0,
    openWindow;

/** 在頁面添加事件 */
function updateListener(){
  const inputFile = document.getElementById('trial-img'),
        trialBtn = document.getElementById('trial-btn'),
        trialSend = document.getElementById('trial-send'),
        commSend = document.getElementById('comment-send'),
        reSend = document.getElementsByClassName('reply-send'),
        rebtn = document.getElementsByClassName('reply-btn'),
        trialEdit = document.getElementById('trial-edit'),
        trialOther = document.getElementById('trial-other');

  if(inputFile != undefined) inputFile.addEventListener('input',preview);    //等同於 input 的 change event 
  trialBtn.addEventListener('click',showTrial);  //顯示 試作區塊
  if(trialEdit != undefined) trialEdit.addEventListener('click',trialWindow); //開起試作編輯視窗
  if(trialOther != undefined) trialOther.addEventListener('click',trialWindow); //開起試作編輯視窗
  
  if(trialSend != undefined) trialSend.addEventListener('click',checkTrial); //檢查試作表單資料
  if(commSend != undefined) commSend.addEventListener('click',checkForm); //提交 留言
  for(var i = 0; i < reSend.length; i++){
    reSend[i].addEventListener('click',checkForm);
  }
  for(var i = 0; i < rebtn.length; i++){
    rebtn[i].addEventListener('click',showRelyForm);
  }
}

/** Input File OnChange Event */ 
function preview(e){
  const reader = new FileReader(), //set a file reader to read image URL
        img = new Image();       // set an image source，最後的長度還是依照 drawThumbNailImage() 的 size，所以沒設定沒差 
 
  if(e.target.files[0] == undefined){
    alert('Warnning : No any Image was selected!');
    return;
  }

  // ---- 獲取 圖檔資源 ----
  canvas = document.getElementById('finishImg');
  canvas.width = 360;    //固定容器 size 賦予 canvas * 若不設定，Chrome 預設 canvas  為 300 * 150
  canvas.height = 270;
  ctx = canvas.getContext('2d'); //get canvas 2d 背景物件
  if(e.target.files[0] != undefined ){

    reader.onloadend = function(){
      dataURL.length = 0 ;//確保陣列為空值，為了要放入最新值。
      img.src = reader.result;
      getOrientation(e.target.files[0],img,canvas);
      parentBg = e.target.parentElement; //取得父標籤 obj 刪除背景用
      parentBg.style.background="url('')"; //父標籤的背景改空
      parentBg.style.border="none"; //父標籤的邊線改空
    }
    reader.readAsDataURL(e.target.files[0]);  // read image URL 是 base64 文件
  }
  // imgType = e.target.files[0].type; //紀錄圖像類型
}

/*** 繪製處理過的圖像 可以參考 CMS8.3 PHP thumbnail 如何寫 ***/
function drawThumbNailImage() {

  // --- Resize Intrinsic source Image ---
  resizeIntrinsicImage(this); //原始圖 size 縮放，draw into destination x-axis and y-axis 
  //---- Will draw the image as the custom size of Width and Height ---
  parentBg.style.background="url('')"; //父標籤的背景改空
  parentBg.style.border="none"; //父標籤的邊線改空
  ctx.drawImage(this,dt_x,dt_y,this.width,this.height);   // Intrinsic size had been changed
  dataURL = canvas.toDataURL('image/png',1.0);   // Convert canvas image to URL format (base64) => 取得 Image Data
  dt_x = 0;  dt_y = 0; //*畫完，座標複歸
  console.log('dt_x = '+dt_x+',dt_y = '+dt_y); 
  
}

/** 縮放 原始圖片大小 (固定 寬*高 的容器，等比例縮放) */
function resizeIntrinsicImage(img){

  //取得原始圖片 size
  var imgW = img.naturalWidth,
      imgH = img.naturalHeight;

  //取得畫布大小    
  var cw = canvas.width,
      ch = canvas.height;

  //* 原始圖片 寬*高 size  都小於 canvas 長度 ，則不執行縮放
  //* 但執行計算放置位置
  if(imgW < cw && imgH < ch){
    dt_x = (cw-imgW)/2  //水平置中，避免圖片太小時貼在左上角
    dt_y = (ch-imgH)/2  //垂直置中，避免圖片太小時貼在左上角
    return; 
  } 

  // --- 計算 原始圖片 寬*高 需要的 縮放比例 ---
  // (以容器 canvas size 為主的強制縮放，避免因 size 超過 或 小於 canvas 而失真)
  if (imgW > imgH){
    img.height = imgH*(cw/imgW);
  }else{
    img.width = imgW*(ch/imgH);;
  }
  console.log('imgW = '+imgW+'; imgH = '+imgH+'; dt_x = '+dt_x+"; dt_y = "+dt_y);
  // --- 計算欲繪製至目標上的位置，並修正 size ---
  imgW = img.width;
  imgH = img.height;
  // X 軸
  if(imgW < cw){
    dt_x = (cw-imgW)/2  //水平置中，避免圖片太小時貼在左上角
  }else{
    img.width = cw;     //若大於如容器 canvas ，則 賦予 canvas size
  }
  // Y 軸
  if(imgH < ch){
    dt_y = (ch-imgH)/2  //垂直置中，避免圖片太小時貼在左上角
  }else{
    img.height = ch;    //若大於容器 canvas ，則 賦予 canvas size
  }
  console.log('img.width = '+img.width+'; img.height = '+img.height+'; dt_x = '+dt_x+"; dt_y = "+dt_y);
}

/** 驗證試作表單 */
function checkTrial(){
  var trialText = document.getElementById('finish-summary').value,
      dishName = document.getElementsByTagName('title')[0].innerHTML,
      trialSend = document.getElementById('trial-send').value;
  const urlParams = new URLSearchParams(window.location.search),
        param = urlParams.get('id'); //URL 的參數 recipe id 值'

  if(dataURL[0] === undefined){
    alert('圖 片 尚 未 新 增!'+dataURL[0]);
    return false;
  } 

  if(trialText != undefined){
    if(trialText < 5 || trialText > 500){
      alert('字數不得小於 5 個 或 大於 500 個!');
      return false;
    }
    else{

      request ='send='+trialSend
                +'&title='+dishName
                +'&summary='+trialText
                +'&id='+param
                +"&img="+JSON.stringify(dataURL[0]);
    }
    sendTrialRequest(request,ajaxTrial,'editTrial','add');
  }
}

/** 顯示試作區塊 */
function showTrial(){
  const trial = document.getElementById('trial-share'),
        share = document.getElementById('share-link');
        console.log('press_trial:'+press_trial);

  if(share != undefined){
    share.style.display = "none";
    press_share = 1;
  }

  if(trial != undefined){
    if(press_trial++ % 2 == 0) trial.style.display = "none";
    else trial.style.display = "block";
  }else{
    alert('親 愛 得 用 戶 請 先 登 入 !');
    location.href='user_login.php';
  }

  if(press_trial > 2) press_trial = 1;
}

/** 顯示社群分享區塊 */
function showShare(){
  const trial = document.getElementById('trial-share'),
        share = document.getElementById('share-link');
        console.log('press_share:'+press_share);
  if(trial!=undefined){
    trial.style.display = "none";
    press_trial = 1;
  } 
  if(press_share++ % 2 == 0) share.style.display = "none";
  else share.style.display = "unset";
  if(press_share > 2) press_share = 1;
}

/** 開啟食譜試作視窗 */
function trialWindow(e){
 var act = e.target.getAttribute('act'),
     id = e.target.getAttribute('trial-id'),
     width = 700, height = 650,
     left= (screen.width-width)/2,
     top = (screen.height-height)/2,
     specs = 'alwaysOnTop=on,left='+left+',top='+top+',width='+width+',height='+height,
     urlParams = new URLSearchParams(window.location.search),
     param = urlParams.get('id'), //URL 的參數 recipe id 值
     url;

    url = "trial_detail.php?act="+act+'&follow='+param

    if(id != undefined) url = "trial_detail.php?act="+act+'&follow='+param+'&id='+id; //編輯試作內容

     window.open(url,'trial_detail',specs).focus();
     
}

/**
 * 使用 AJAX 向伺服器發送請求 
 * XMLhttpRequest send POST Request to server
 * @param {String} request  發送的參數
 * @param {Function} FunctionName Input function that should execute 
 * @param {String} script 伺服器腳本名稱
 * @param {String} action 伺服器判斷執行動作的參數
 */
function sendTrialRequest(request,cFunction,script,action){

  const xhttp = new XMLHttpRequest();
  xhttp.open("POST",script+".php?act="+action,true);
  xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  xhttp.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      cFunction(this);
    }
  }
  xhttp.send(request);
}
  
/** Ajax 傳送 試作 資料 結果*/
function ajaxTrial(xhttp){
  if(xhttp.responseText === 'SUCCESS' ){ //回傳 食譜 id  
      window.onbeforeunload = null; //移除 onbeforeunload 事件
      alert('新 增 成 功!');
      location.reload(true);//重新載入頁面
      return false;
  }else if(xhttp.responseText === 'UNLogin'){
      window.onbeforeunload = null; //移除 onbeforeunload 事件
      alert('請先登入!'); //輸出錯誤訊息
      location.href ='user_login.php';
  }else{
        alert(xhttp.responseText); //輸出錯誤訊息
  } 
}
