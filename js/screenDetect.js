window.onload = sizeAdapt;
window.onresize = sizeAdapt;
function sizeAdapt(){
  var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
  var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
  var coll_btn = document.getElementById('collapse_btn');
  var ol = document.getElementById('nav-bar');
  var logo = document.getElementById('logo');
  var header = document.getElementById('header');
  if(w<=360){ //螢幕寬度以 360px 為主
    ol.style.display="none";
    coll_btn.style.display="block";
  }
  if(w>360){
    if(w < (logo.clientWidth + ol.clientWidth) ){
      ol.style.display="none";
      coll_btn.style.display="block";
    }else{
      ol.style.display="block";
      coll_btn.style.display="none";
    }
  }
}