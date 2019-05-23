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
    openWindow,
    id;

/** 在頁面添加事件 */
function updateListener(){
  const inputFile = document.getElementById('trial-img'),
        trialSend = document.getElementById('trial-send');
  
  if(inputFile != undefined) inputFile.addEventListener('input',preview);    //等同於 input 的 change event 
  if(trialSend != undefined) trialSend.addEventListener('click',checkTrial); //檢查試作表單資料
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

/** 驗證試作表單 */
function checkTrial(){
  var trialText = document.getElementById('finish-summary').value,
      trialSend = document.getElementById('trial-send').value,
      urlParams = new URLSearchParams(window.location.search),
      id = urlParams.get('id'),//URL 的參數 recipe id 值,
      origiImg = document.getElementById('finish-trial').getAttribute('origin'); 

  if(trialText === undefined) return false;

  if(trialText < 5 || trialText > 500){
    alert('字數不得小於 5 個 或 大於 500 個!');
    return false;
  }

  request ='send='+trialSend
            +'&summary='+trialText
            +'&id='+id
            +'&img='+JSON.stringify(dataURL[0])
            +'&origiImg='+origiImg;
  sendRequest(request,ajaxTrial,'editTrial','update');
  
}

/**
 * 使用 AJAX 向伺服器發送請求 
 * XMLhttpRequest send POST Request to server
 * @param {String} request  發送的參數
 * @param {Function} FunctionName Input function that should execute 
 * @param {String} script 伺服器腳本名稱
 * @param {String} action 伺服器判斷執行動作的參數
 */
function sendRequest(request,cFunction,script,action){

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
      var urlParams = new URLSearchParams(window.location.search),
      follow = urlParams.get('follow');//URL 的參數 recipe follow 值 試做食譜的 id,
      alert('食 譜 試 作 內 容 修 改 成 功!');
      location.href='?act=show&follow='+follow;//重新載入頁面
      return false;
  }else if(xhttp.responseText === 'UNLogin'){
      window.onbeforeunload = null; //移除 onbeforeunload 事件
      alert('請先登入!'); //輸出錯誤訊息
      location.href ='user_login.php';
  }else{
        alert(xhttp.responseText); //輸出錯誤訊息
  } 
}
