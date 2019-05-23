<?php
//=== *** 驗證管理員身分 *** ===
require 'require.php';
Validate::checkManagerLogin() ? Tool::alertLocation('login.php') : Tool::alertLocation('home.php');
?>