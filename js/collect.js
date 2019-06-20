var collect = document.getElementById('collect-btn'),
    dele = document.getElementById('delete-btn'),      
    press = document.getElementById('collection'), //驗證是否收藏用 
    param = new URLSearchParams(window.location.search),
    id = param.get('id');//食譜 id
   
if(collect!=undefined) collect.addEventListener('click',clickCollect);
if(dele!=undefined) dele.addEventListener('click',deleteCollect);

/**
 * 點擊收藏功能
 */
function clickCollect(){
  press = press.value;
  if( (press++ % 2) != 0 ){
    collect.childNodes[0].style.background = "url('sprite/like2.svg') no-repeat left center"  ;
    recipeCollect();
  }else{
    collect.childNodes[0].style.background = "url('sprite/like.svg') no-repeat left center / 19px"  ;
    recipeCollect(false);
    // collect.childNodes[0].style.backgroundSize = "19px";
  } 
    if(press > 2) press = 1; //讓次數 停留在 1 ~ 2 之間 不浪費記憶體
}

/** 個人中心移除收藏 */
function deleteCollect(){
  id = document.getElementById('rec_id').value;
  recipeCollect(false); //取消收藏
  location.reload();
  document.getElementById('new-collect').focus();
}   

/**
 * 收藏、取消收藏
 * @param {boolean} true 收藏 false 取消收藏
 */
function recipeCollect(collect=true){
  var url = 'collect.php',
      request;
  request = 'collect='+collect
            +'&id='+id;
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

  switch (xhttp.responseText) {

    case 'SUCCESS':
      alert('已 收 藏!');
      var coll = document.getElementById('colle-span'); 
      coll.innerHTML = parseInt(coll.innerHTML) + 1; //收藏數 +1
      break;

    case 'CANCEL':
        var coll = document.getElementById('colle-span');
        if(coll != undefined) coll.innerHTML = parseInt(coll.innerHTML) - 1; //收藏數 -1 
      break;

    case 'UNLogin':
      alert('請先登入！'); //輸出錯誤訊息
      location.href ='user_login.php';
      break;

    default:
      alert(xhttp.responseText); //輸出錯誤訊息
      break;
  }

}
