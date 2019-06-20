//==== 新增食譜 HTML ELEMENTS 新增功能 ====

var ingre_span,
    step_span;
/** 新增 材料區塊的 HTML ELEMENTS*/

function addIngredient(){

  // --- 數量限制警告 ---
  var ingre_dd = document.getElementsByClassName('ingre_dd'),
  res = checkElementsNum(ingre_dd,0,15);
  ingre_span = document.getElementById('ingre_span');


  if(res){

    alert('很抱歉已經太多列了，無法繼續增加!');

    return ;

  }

  //--- 新增元素----

  var dl= document.getElementById('ingre_dl'); 

  var dd = document.createElement('dd');

  var input = document.createElement('input');

  var unit = document.createElement('input');

  var delet = document.createElement('button');

  

  input.setAttribute('type','text');

  input.setAttribute('class','text');

  input.setAttribute('name','food_name[]');

  input.setAttribute('placeholder',' 材料');

  unit.setAttribute('type','text');

  unit.setAttribute('class','text unit');

  unit.setAttribute('name','food_unit[]');

  unit.setAttribute('placeholder','數量');

  delet.setAttribute('type','button');

  delet.setAttribute('class','delete-btn');

  delet.setAttribute('id','delete-ingre');

  delet.setAttribute('onclick','removeEls(event);');

  dd.setAttribute('class','ingre_dd');

  delet.innerHTML="刪除材料"

  var span1 = document.createElement('span'); 

  var span2 = document.createElement('span');

  span1.innerHTML = span2.innerHTML = "&nbsp;";//間隔

  dd.appendChild(input);

  dd.appendChild(span1);

  dd.appendChild(unit);

  dd.appendChild(span2);

  dd.appendChild(delet);

  dl.appendChild(dd);

  if(ingre_span != undefined) ingre_span.innerHTML = '(剩餘: '+ingre_dd.length+'/15 列)';

}



/** 新增 調理方式區塊的 HTML ELEMENTS*/

function addStep(){

  var step_dd = document.getElementsByClassName('step_dd');
  step_span = document.getElementById('step_span');
  // --- 數量限制警告 ---
  var res = checkElementsNum(step_dd,0,15);

  if(res){

    alert('很抱歉已經太多列了，無法繼續增加。還 請 斟 酌 步 驟 數 量!');

    return ;

  }



  //--- 新增元素----

  var dl= document.getElementById('step_dl');

  var dd = document.createElement('dd');

  var div_img = document.createElement('div');

  var div_text = document.createElement('div');

  var canvas = document.createElement('canvas');

  var inputFile = document.createElement('input');

  var input = document.createElement('input');

  var inputFileArr = document.getElementsByClassName('step_img');   //步驟照上傳 input 物件

  var inputFileLen = inputFileArr.length;       //步驟照上傳 input 物件陣列長度 = 個數

  var delet = document.createElement('button');

  var span = document.createElement('span');

  var h5 = document.createElement('h5');

  var h5Len = document.getElementsByTagName('h5').length;

  

  h5.innerHTML ="Step "+(h5Len+1)+".";

  dd.appendChild(h5);

  

  div_img.setAttribute('class','upload_step');

  canvas.setAttribute('class','step_canvas');

  inputFile.setAttribute('no',inputFileLen+1);  //* no 從 0 開始

  inputFile.setAttribute('class','step_img');

  inputFile.setAttribute('type','file');

  inputFile.setAttribute('name','step_img[]');

  inputFile.setAttribute('accept','image/*');

  inputFile.setAttribute('onchange','preview(event)');





  div_img.appendChild(canvas);

  div_img.appendChild(inputFile);

  dd.appendChild(div_img);



  div_text.setAttribute('class','input_area');

  input.setAttribute('class','text step');

  input.setAttribute('type','text');

  input.setAttribute('name','step[]');

  input.setAttribute('placeholder',' 輸入步驟');

  delet.setAttribute('type','button');

  delet.setAttribute('class','delete-btn');

  delet.setAttribute('id','delete-step');

  delet.setAttribute('onclick','removeEls(event);');

  dd.setAttribute('class','step_dd');

  delet.innerHTML="刪除步驟"

  span.innerHTML = "&nbsp;";



  div_text.appendChild(input);

  div_text.appendChild(span);

  div_text.appendChild(delet);

  dd.appendChild(div_text);

  dl.appendChild(dd);

  
  step_span.innerHTML = '(剩餘: '+step_dd.length+'/15 列)';

}



/** 檢查 Elements 的數量

 * @param  {object}  HTML Collection

 * @return {Boolean} 

 */

function checkElementsNum(collection,min=null,max=null){

  var len = collection.length;

  var res = len < min || len >= max ? true : false;

  return res

}



/** 刪除列 (準備材料 Elements、 步驟 Elements)*/

function removeEls(event){
  ingre_span = document.getElementById('ingre_span');
  step_span = document.getElementById('step_span');

  var child,parent,childLen;

  child = event.target;

  while(child.parentElement.nodeName != 'DL') child = child.parentElement;

  parent = child.parentElement;//此時的child 還是 dd element 故再取得 dl element

  childLen = parent.childElementCount;

  //清除該列的 input file 圖片資料（儲存在 dataURL 陣列） 以免被上傳伺服器
  if(child.querySelector('.step_img') != undefined){
    var inputNo = child.querySelector('.step_img').getAttribute('no');
    dataURL[inputNo] = '';
  }
 
  
  if(childLen == 1){

    console.log(parent.childElementCount);

    alert('很 抱 歉 至 少 得 留 一 列 填 寫!') 

    return false;

  }


  parent.childNode
  parent.removeChild(child); //移除 1 列 (dd elment)
  childLen = parent.childElementCount;//重新計算
  if (parent.parentElement.getAttribute('class') == 'cook_step'){
    step_span.innerHTML = '(剩餘: '+childLen+'/15 列)';
  }
  if (parent.parentElement.getAttribute('class') == 'ingredient'){
    ingre_span.innerHTML = '(剩餘: '+childLen+'/15 列)';
  }

}