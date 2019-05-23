
function navBarEvent(){

  var setti_btn = document.getElementsByClassName('setti-btn')[0],
      setti = document.getElementById('setti'),
      settings = document.getElementsByClassName('settings')[0],
      cl_navbar = document.getElementsByClassName('collapse_navbar')[0],
      cl_btn = document.getElementById('collapse_btn'),
      navBar = document.getElementById('nav-bar'),
      count = 1,press = 1, // 點擊次數計算
      cw = document.getElementById('header').clientWidth, //header 在客戶端的寬度
      ch = document.getElementById('header').clientHeight; //header 在客戶端的寬度

  //-- 智慧裝置的點擊功能
  if(screen.width <= 414){
    if(cl_btn != undefined){
      cl_btn.onclick = function(){
        if( (press % 2) != 0 ){
          settings.style.display="block"; //點擊次數是奇數則顯示
          settings.style.left = cw - settings.clientWidth + 'px';
          settings.style.top = (ch - 10) + 'px';
        } 

        else settings.style.display="none";

        press++;

        if(press > 2) press = 1; //讓次數 停留在 1 ~ 2 之間 不浪費記憶體
        
      };
    }
  }
  
  if(setti_btn != undefined){

    //當游標在設定按鈕上
    setti_btn.onmouseover = function(){
      settings.style.display="block";
      settings.style.left = cw - settings.clientWidth + 'px';
      settings.style.top = (ch - 10) + 'px';
      
    }

    //當游標在設定按鈕外

    setti_btn.onmouseout = function(){
      settings.style.display="none";
    }
    


  }

  //當游標在設定區塊上
  settings.onmouseover = function(){

    settings.style.display="block";

  }

  //當游標在設定區塊外
  settings.onmouseout = function(){

    settings.style.display="none";

  }

  // //當 點擊 collapse button
  // cl_btn.onclick = function(){

  //   if( (count%2) != 0 ) cl_navbar.style.display="block"; //點擊次數是奇數則顯示

  //   else{
  //     cl_navbar.style.display="none";
  //     settings.style.display="none";
  //     press=1;
  //   } 

  //   count++;

  //   if(count  > 2) count = 1; //讓次數 停留在 1 ~ 2 之間 不浪費記憶體

  // }

}