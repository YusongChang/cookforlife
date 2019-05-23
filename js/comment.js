var press_reply = 1,  //點擊回覆鈕次數初始值
    re_tmp = null;    //回覆鈕序號暫存

function isMobile() {
  return navigator.userAgent.match(/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i) ? true : false;
}
/** 在頁面添加事件 */
function updateListener(){
  const rebtn = document.getElementsByClassName('reply-btn'),
        time = document.getElementsByClassName('date-span');
  var commentTime = 0,
  tap = isMobile() ? 'touchstart' : 'click';

  $('#comment-send').on(tap,checkForm); //提交 留言

  for(var i = 0; i < rebtn.length; i++){
    rebtn[i].addEventListener('click',showRelyForm);
  }

  $('.reply-send').each(function(){ //提交回覆
    $(this).on(tap,checkForm);
  });

  for(var k = 0; k < time.length; k++){
    commentTime = parseInt(Date.now()/1000) - parseInt(time[k].getAttribute('time'));
    if(commentTime < 5){ //最新留言時間小於 5 秒
      time[k].parentElement.parentElement.style.backgroundColor="#fff1c2"; //hightlight
    }
  }
}

/** 驗證留言/回覆表單 */
function checkForm(e){
  var comment = document.getElementById('comment-text'),
      reply = document.getElementsByName('reply'),
      request = null,
      no = e.target.getAttribute('no'),
      userId = e.target.getAttribute('replyid'),
      pid = e.target.getAttribute('pid'),
      text = null;
  const urlParams = new URLSearchParams(window.location.search);
  const param = urlParams.get('id'); //URL 的參數 recipe id 值

  if(comment != undefined){ //若是留言
    text = comment.value;
  } 
  if(reply[no] != undefined){ //若是回覆
    text = reply[no].value; 
  }

  if(text < 2 || text > 500){
      alert('字數不得小於 2 個 或 大於 500 個!');
      return false;
  }

  request ='comment='+text
          +'&recId='+param
          +'&reply='+userId
          +'&pid='+pid;
    
  sendRequest(request,ajaxMessage,'addComment','add');
}

/** 顯示回覆區塊 */
function showRelyForm(e){
  const reply = document.getElementsByClassName('reply'),
        text = document.getElementsByName('reply'),
        comment = document.getElementById('write-comment');
        no = e.target.getAttribute('no');

  if(reply[no] === undefined) return false;
  if(re_tmp != no){ //跟前一個 不同 按鈕
    reply[no].style.display = "unset";
    if(re_tmp != undefined) reply[re_tmp].style.display = "none";
    text[no].focus();
    re_tmp = no;
    comment.style.display = "none"
  }else{ //按下同一個按鈕
    reply[no].style.display = "none";
    re_tmp = null; //清空，以便重新紀錄
    text[no].focus();
    comment.style.display = "block"
  }
  console.log('re_tmp:'+re_tmp+',no:'+no);

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
  
/** Ajax 傳送 留言 結果 */
function ajaxMessage(xhttp){
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