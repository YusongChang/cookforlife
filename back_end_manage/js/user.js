var inputPhoto,
    photo,
    send;

window.onload = function(){
  inputPhoto = document.getElementById('photo-upload');
  photo = document.getElementById('photo');
  send = document.getElementById('send');
  var dele = document.getElementById('delete');

  if(photo != undefined) photo.addEventListener('click',onclikPhoto);
  if(send != undefined) send.addEventListener('click',checkForm);
  if(dele != undefined) dele.addEventListener('click',deleteUser);

}

function onclikPhoto(){
  inputPhoto.click();//讓 onclick event 被觸發
  inputPhoto.addEventListener('change',previewPhoto);
}

function previewPhoto(e){
}

function checkForm(){
  var form = document.getElementById('form'),
      oldpwd = form.oldpasswd.value; //舊密碼
      newpwd = form.newpasswd.value; //新密碼

  if(oldpwd.length != 0){
    if(newpwd.length == 0){
      alert('新密碼尚未填寫');
      return false;
    }
    if(newpwd == oldpwd){
      alert('舊密碼 與 新密碼 不可相同！');
      return false;
    }
  }
  if(newpwd.length != 0){
    if(oldpwd.length == 0){
      alert('舊密碼尚未填寫');
      return false;
    }
    if(newpwd == oldpwd){
      alert('舊密碼 與 新密碼 不可相同！');
      return false;
    }
  }

 

  if(checkLenLimit(oldpwd,6,12)){
    alert('[舊密碼] 位數不可以 小於 6位 或 大於 12位！');
    return false;
  }
  if(checkLenLimit(newpwd,6,12)){
    alert('[新密碼] 位數不可以 小於 6位 或 大於 12位！');
    return false;
  }

}

function deleteUser(){
  
}
/**
 * @param {String}
 * @param {Int} $min 最小長度
 * @param {Int} $max 最大長度
 * @return {Boolean} True of False
 */
function checkLenLimit($data,$min,$max){
  var strlen = $data.length;
  return (strlen < $min || strlen > $max) ? true:false;
}

function AjaxRequest(){

}

function AjaxResult(){

}

