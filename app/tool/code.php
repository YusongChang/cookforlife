<?php
//=== 驗證碼 接口 === 
require substr(dirname(__FILE__),0,strlen(dirname(__FILE__))-8).'init.php';
$code = new ValidateCode();
$code->outputCodeImg();
$_SESSION['code'] = $code->getCode();
?>
