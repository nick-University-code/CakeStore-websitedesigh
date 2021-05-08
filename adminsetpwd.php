<?php
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
require_once("../include/xss.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);

/*
$ReqID = addslashes($ReqID);
$ReqID = xsspurify($ReqID);
*/
$sqlcmd = "SELECT * FROM data WHERE reqid='$ReqID' ";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) 
    die('重設密碼連結已失效，可能是密碼已重設或是已再次申請重設');
$ID = $rs[0]['account'];
if (!isset($PWD01)) $PWD01 = '';
if (!isset($PWD02)) $PWD02 = '';
$ErrMsg = '';
if (isset($Confirm)) {
    if (strlen($PWD01)>20 || $PWD01<>$PWD02)
        $ErrMsg .= '密碼長度少於8或超過20，或是兩個密碼不相同\n';
    if (empty($ErrMsg)) {   // 資料驗證無誤
        //$NewvCode = rand(1000,9999);
        //$PWD = password_hash($PWD01, PASSWORD_BCRYPT);
		$PWD = sha1($PWD01);
        $sqlcmd = "UPDATE data SET password='$PWD' "
            . "WHERE reqid='$ReqID' AND account='$ID'";
        $result = updatedb($sqlcmd, $db_conn);
        header ("Location:index.php");
        exit();
    }
}
require_once('../include/header.php');
?>
<script type="text/javascript">
function setFocus() {
    document.LoginForm.PWD01.focus();
}
</script>
<body onload="setFocus()">
<div class="Container" style="width:800px">



<table width="700" align="center" style="position:absolute; top:20px;left:35%;">
  <tr height="30" >
    <td >
  密碼重置
   </td>
  </tr>
  <form method="POST" name="LoginForm" action="">
  <input type="hidden" name="ReqID" value="<?php echo $ReqID;?>">
  <input type="hidden" name="vercode" value="<?php echo $vercode;?>">
   <tr height="30" >
    <td >
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;帳號：<?php echo $ID; ?>
  </td>
  </tr>

<tr height="30" >
    <td >
   登入密碼：<input type="password" id='PWD01' name="PWD01" size="20" maxlength="20">
    &nbsp;&nbsp;(8~20個英數字或符號)
    </td>
  </tr>
  <tr height="30" >
    <td >
    密碼確認：<input type="password" name="PWD02" size="20" maxlength="20">&nbsp;&nbsp;(需與登入密碼相同)
 </td>
  </tr>
    <tr height="30" >
    <td >
  <input type="submit" name="Confirm" value="更新密碼">
  </td>
  </tr>
 <tr height="30" >
    <td >
  請於上方欄位輸入登入密碼及密碼確認後，點選『更新密碼』按鈕即可重新設定密碼。
 </td>
  </tr>
  </form>
</div>
</body>
</html>
