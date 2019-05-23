var editor = new Simditor({
  textarea: $('#editor'),
  placeholder: '輸入自我介紹 500 字',
  toolbar: false,//沒有設計上傳功能應避免使用此功能
});
var newPhoto, //新圖像
    no = 0,//圖像資料陣列索引初始 為 0 
    dataURL = []; // New Image source URL Nodelist array;
document.getElementById('photo-canvas').addEventListener('click',triggerInputFile);
document.getElementById('save-btn').addEventListener('click',checkForm);

/** 按下canvas 觸發 input:file 的 click event */
function triggerInputFile(){
  var photoUp = document.getElementById('upload-photo');
  photoUp.click();
  photoUp.addEventListener('change',preview);
}

/** 預覽上傳頭像 */
function preview(e){

  if(e.target.files[0] == undefined){
    alert('Warnning : No event an Image was selected!');
    return ;
  }
  
  readImgSrc(event.target.files[0]);//reader 寫在同一個 method 裡 即使 return 也會被執行
}

/** 讀取上傳圖片並處理圖片 */
function readImgSrc(file){
  const reader = new FileReader(), //set a file reader to read image URL
  img = new Image(), // set an image source，最後的長度還是依照 drawThumbNailImage() 的 size，所以沒設定沒差
  canvas = document.getElementById('photo-canvas'),
  parentBg = canvas.parentElement; //取得父類標籤 改背景用
  canvas.width = 72;//設置 canvas 寬尺寸
  canvas.height = 72;//設置 canvas 高尺寸

  reader.onloadend = function(){
    img.src = reader.result;
    parentBg.style.background="url('')";
    canvas.style.background="url('')";
    getOrientation(file,img,canvas);
    canvas.style.opacity="1";

  }
  reader.readAsDataURL(file);
}

/**
 * 檢查表單資料，並發送 Post Request
 * @return {Boolean} 
 */
function checkForm(){
    
  var nickname = document.getElementById('nickname').value,
      email = document.getElementById('email').value,
      phone = document.getElementById('phone').value,
      param = new URLSearchParams(window.location.search),
      originPhoto = document.getElementById('origin-photo').value, //原圖
      id = parseInt(param.get('id')),
      url = 'editProfile.php?act=update&id='+id,
      request;

  // ---- 檢查 暱稱 ----
  if(nickname.length < 2 || nickname.length > 10){
    alert('暱 稱 長 度 須 符 合 2-10 個 字!');
    return false; 
  }

  // --- 檢查 Email 格式 ---
  if(email.length <= 0){alert('Email 尚 未 填 寫！'); return false;}
  if( !email.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/g) ){
    alert('Email 格 式 錯 誤！'); return false;   
  }

  // --- 檢查 phone 格式 ---
  if(phone.length <= 0){alert('手 機 號 尚 未 填 寫！'); return false;}
  if( !phone.match(/^09\d{8}$/g) ){
    alert('手 機 號 格 式 錯 誤！'); return false;   
  }

  // 檢查簡介字數
  var str = editor.getValue();
  if(str.length <= 0){ alert('簡 介 尚 未 填 寫！'); return false;}
  if(dataURL[0] == undefined) dataURL[0] = null;
  request ='send='+'save'
            +'&newPhoto='+JSON.stringify(dataURL[0])
            +'&originPhoto='+originPhoto
            +'&nickname='+nickname
            +'&email='+email
            +'&phone='+phone
            +'&intro='+editor.getValue();

  sendRequest(url,request,ajaxResult);
}

/**
 * 使用 AJAX 向伺服器發送請求 
 * XMLhttpRequest send POST Request to server
 */
function sendRequest(url,request,cFunction){

  const xhttp = new XMLHttpRequest();
    xhttp.open("POST",url,true);
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
  if( !isNaN(parseInt(xhttp.responseText)) ){ //回傳 食譜 id 整數型別   
      window.onbeforeunload = null; //移除 onbeforeunload 事件
      alert('儲 存 成 功!');
      location.href = 'user_profile.php?id='+xhttp.responseText;
  }else if(xhttp.responseText == 'UNLogin'){
      window.onbeforeunload = null; //移除 onbeforeunload 事件
      alert(xhttp.responseText); //輸出錯誤訊息
      location.href ='user_login.php';
  }else{
        alert(xhttp.responseText); //輸出錯誤訊息
  } 
}