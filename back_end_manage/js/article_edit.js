var editor,
    dataURL = [], // New Image source URL Nodelist array;
    modal,
    thumbModal,
    contentImg,//內文圖片的 URL (input element 物件)
    thumbImg,//文章縮略圖
    url,//發送 POST Request 的網址
    simBody,
    enter,//submit or save
    id, //文章 id
    tool;

    
window.onload = function(){
  EventListener();
  editor = new Simditor({
    textarea:$('#editor'),
    toolbar:[
              'title',
          'bold',
          'italic',
          'underline',
          'strikethrough',
          'fontScale',
          'color',
          'ol',         
          'ul',             // unordered list
          'blockquote',
          // 'code',           // code block
          'table',
          'link',
          'image',
          'hr',            // horizontal ruler
          'indent',
          'outdent',
          'alignment',
    ],
  });

  //--- 縮略圖物件---
  thumbImg = document.getElementById('thumbImg');
  //simditor toolbar  物件
  tool = document.getElementsByClassName('simditor-toolbar')[0];
  //--- simditor 去掉原本內文圖片的按鈕 ---
  var toolbarImg = document.getElementsByClassName('toolbar-item-image')[0];
  toolbarImg.style.display = "none";
  simBody = document.getElementsByClassName('simditor-body')[0];
  simBody.removeChild(simBody.childNodes[0]); //先刪除simditor 預設的段落
  //--- simditor新增自定義內文圖片按鈕 ---
  var parent = toolbarImg.parentElement,
      link = document.createElement('a'),
      span = document.createElement('span');
      link.setAttribute("class","toolbar-item");
      link.id="toolbar-img";
      span.setAttribute("class","simditor-icon simditor-icon-picture-o");
      link.appendChild(span);
      parent.appendChild(link);
      document.getElementById('toolbar-img').addEventListener('click',showImageModal);//開啟自訂定義圖片上傳 modal
 
  //--- 獲取文章 新增 與 更新 的 submit button 物件
  var send = document.getElementById('send'),
      save = document.getElementById('save');
  if(send != undefined){//新增文章頁面
    send.addEventListener('click',checkForm);
    url="focusArticleEditt.php?act=add";
    enter = 'send';
  }
  if(save != undefined){//更新文章頁面
    save.addEventListener('click',checkForm);
    param = new URLSearchParams(window.location.search);
    id = param.get('id');
    enter='save';
    url="focusArticleEditt.php?act=update&id="+id;
    //添加刪除按鈕點擊事件
    document.getElementById('delete').addEventListener('click',deleteArticle);
  } 

}

/** 添加事件監聽器 */
function EventListener(){
  modal = document.getElementById('upImgModal');
  thumbModal = document.getElementById('thumbModal');
  //---文章縮略圖（img element 物件）---;
  document.getElementById('thumb-img').addEventListener('click',showThumbModal);
}

/** 顯示 上傳圖片 Modal box */
function showImageModal(){
 var add = document.getElementById('addImg-btn'),
    close = document.getElementById('close'),
    input = document.getElementById('inputImg'),
    save = document.getElementById('save-img');

  tool.style.zIndex="-1" //simditor 排序最低
  document.body.style.overflow="hidden";  //禁止滑鼠滾動

  modal.style.display="block";

  save.addEventListener('click',uploadImg);//上傳圖片按鈕

  add.onclick=function(){ //按下canvas 觸發 input:file 的 click event
    input.addEventListener('change',previewImg);
    input.click();
  };

  close.onclick = function(){ //按下關閉標籤
    modal.style.display = "none";
    document.body.style.overflow="unset";
    //simditor 恢復排序
    tool.style.zIndex="2";
  }
}
/** 顯示 上傳縮略圖片 Modal box */
function showThumbModal(){
  var add = document.getElementById('addThumb-btn'),
      close = document.getElementById('close-btn'),
      input = document.getElementById('inputImg'), 
      save = document.getElementById('save-thumb');

  thumbModal.style.display = "block";

  save.addEventListener('click',uploadThumbnail);//上傳圖片
  add.onclick = function(){ //按下canvas 觸發 input:file 的 click event
    input.addEventListener('change',previewThumbnail);
    input.click();
  };
  close.onclick = function(){
    thumbModal.style.display = "none";
  }

}

/** 預覽上傳圖片 */
function previewImg(e){

  thumbImg = null; //清除內文圖片物件

  if(e.target.files[0] == undefined){
    alert('Warnning : No event an Image was selected!');
    return ;
  }

  const reader = new FileReader(), //set a file reader to read image URL
  img = new Image(), // set an image source，最後的長度還是依照 drawThumbNailImage() 的 size，所以沒設定沒差
  canvas = document.getElementById('img-can');

  reader.onloadend = function(){
    img.src = reader.result;
    img.onload =function(){
      // 圖片最大尺寸不能超過 900*900
      if(this.naturalWidth < 900) canvas.width = this.naturalWidth;
      else canvas.width = 900;//設置 canvas 寬尺寸
      if(this.naturalHeight < 900) canvas.height = this.naturalHeight;
      else canvas.height = 900;//設置 canvas 高尺寸
      getOrientation(e.target.files[0],img,canvas);
    }
  }
  reader.readAsDataURL(e.target.files[0]);
}

/** 預覽上傳縮略圖片 */
function previewThumbnail(e){

  contentImg = null; //清除內文圖片物件

  if(e.target.files[0] == undefined){
    alert('Warnning : No event an Image was selected!');
    return ;
  }

  const reader = new FileReader(), //set a file reader to read image URL
  img = new Image(), // set an image source，最後的長度還是依照 drawThumbNailImage() 的 size，所以沒設定沒差
  canvas = document.getElementById('thumb-can');
  reader.onloadend = function(){
    img.src = reader.result;
    img.onload =function(){
      // 圖片最大尺寸不能超過 72*72
      if(this.naturalWidth <= 100) canvas.width = this.naturalWidth;
      else canvas.width = 100;//設置 canvas 寬尺寸
      if(this.naturalHeight <= 100) canvas.height = this.naturalHeight;
      else canvas.height = 100;//設置 canvas 高尺寸
      getOrientation(e.target.files[0],img,canvas);
    }
  }
  reader.readAsDataURL(e.target.files[0]);
}

/** 上傳內文圖片並處理圖片
 * @param {Blob} file 上傳檔案物件
 * @param {Int} canWith 畫布寬度
 * @param {Int} canHeight 畫布高度
 */
function uploadImg(){
  if(dataURL[0] == undefined) return false; //沒有圖片則不執行
  var request ='img='+JSON.stringify(dataURL[0]),
      url='uploadImg.php';
     
  document.body.style.overflow="unset"; //恢復滾輪功能
  tool.style.zIndex="2";//simditor 恢復排序

  contentImg = document.getElementsByClassName('image-src')[0];//內文圖片的 URL (input element 物件)
  sendRequest(url,request,uploadResult);
}

/** 讀取上傳縮略圖片並處理圖片*/
function uploadThumbnail(){
  if(dataURL[0] == undefined) return false; //沒有圖片則不執行
  var request ='img='+JSON.stringify(dataURL[0]),
  url='uploadImg.php';
  
  sendRequest(url,request,uploadResult);
}

/**
 * 檢查表單資料，並發送 Post Request
 * @return {Boolean} 驗證失敗 false
 */
function checkForm(){
    
  var title = document.getElementById('input-title').value,
      summary = document.getElementById('summ-text').value,
      draft = document.getElementById('draft').checked,
      request;

  // ---- 檢查 暱稱 ----
  if(title.length < 5 || title.length > 100){
    alert('標 題 長 度 須 符 合 5-100 個 字!');
    return false; 
  }

  //---- 檢查摘要 ----

  if(summary.length < 20 || summary.length > 500){
    alert('摘 要 長 度 須 符 合 20-500 個 字!');
    return false; 
  }

  //--- 檢查縮略圖 ---
  if(thumbImg.src == null){
    alert('縮 略 圖 尚 未 上 傳！');
    return false; 
  }

  //--- 檢查內文 ---
  var str = editor.getValue();
  if(str.length <= 0){ alert('內 文 尚 未 填 寫！'); return false;}
  
  request = enter+'='+'send' //enter 若是 send 為新增; 是 save 為更新
            +'&title='+title
            +'&thumbImg='+document.getElementById('inputThumb').value
            +'&summary='+summary
            +'&content='+editor.getValue()
            +'&state='+draft;

  sendRequest(url,request,ajaxResult);
}

/** 刪除文章 */
function deleteArticle(){
  var con = confirm('刪 除 後 無 法 回 復 ，確 定 要 刪 除 嗎？');
  url = "focusArticleEdit.php?act=delete&id="+id;
  if(con){
    request ='delete='+'delete';
    sendRequest(url,request,ajaxResult);
  }
} 

/**
 * 使用 AJAX 向伺服器發送請求 
 * XMLhttpRequest send POST Request to server
 * @param {Element Object} element
 * 
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
  
/** Ajax 上傳圖片結果 
 * @param {Element Object} element
*/
function uploadResult(xhttp){
  if( xhttp.responseText != false ){ //回傳 食譜 id 整數型別   
      window.onbeforeunload = null; //移除 onbeforeunload 事件
      
      //---- 內文圖片 ---- 
      if(contentImg != undefined){

        var p = document.createElement('p'),//自訂義內文段落
            textImg = document.createElement('img');//自訂義內文圖片
        /**自訂義內文圖片 */
        textImg.setAttribute("data-image-size","120,120");
        textImg.src= xhttp.responseText;//填入 img src
        p.appendChild(textImg);
        simBody.appendChild(p);
        textImg.click(); //觸發圖片編輯功能
        contentImg.value = xhttp.responseText; //內文圖片 URL 填入 input
        modal.style.display="none";//關閉 modal
        tool.style.zIndex="2";//simditor 恢復排序
      }
      //--- 縮略圖 --- 
      if(thumbImg != undefined){
        thumbImg.src = xhttp.responseText;  //文章縮略圖片
        document.getElementById('inputThumb').value = xhttp.responseText;
        thumbModal.style.display="none"; //關閉 modal
      }

  }else{
        alert(xhttp.responseText); //輸出錯誤訊息
  } 
}

/** Ajax 傳送文章資料結果 */
function ajaxResult(xhttp){
  if( xhttp.responseText == 'SUCCESS' ){ //回傳 食譜 id 整數型別
    window.onbeforeunload = null; //移除 onbeforeunload 事件
    window.location.href="article.php?act=show";
  }else if('ERROR'){
        alert('系 統 發 生 異 常!'); //輸出錯誤訊息
  }else{
    alert(xhttp.responseText);
  } 
}