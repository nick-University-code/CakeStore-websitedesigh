<?php
// Authentication 認證
require_once("../include/auth.php");
// session_start();
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
if (!isset($GID)) $GID='';
/*
$sqlcmd = "SELECT * FROM user WHERE loginid='$LoginID' AND valid='Y'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');
$UserGroupID = $rs[0]['groupid'];
// var_dump($_SESSION);
// 取出群組資料
$sqlcmd = "SELECT * FROM groups WHERE valid='Y' AND (groupid='$UserGroupID' "
    . "OR groupid IN (SELECT groupid FROM userpriv "
    . "WHERE loginid='$LoginID' AND privilege > 1 AND valid='Y'))";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs)<=0) die('No group could be found!');  
$GroupNames = array();
foreach ($rs as $item) {
    $ID = $item['groupid'];
    $GroupNames[$ID] = $item['groupname'];
}
*/
if(isset($search)){
	echo $key;
	header("Location: goodssearch.php?key=$key");
}
if (isset($action) && $action=='recover' && isset($cid)) {
    // Recover this item
    // Check whether this user have the right to modify this contact info
    $sqlcmd = "SELECT * FROM goods WHERE name = '$Name' AND valid='N'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
//        $GID = $rs[0]['groupid'];
//        if (isset($GroupNames[$GID])) {     // Yes, the  user has the right. Perform update
            $sqlcmd = "UPDATE goods SET valid='Y' WHERE name='$Name'";
            $result = updatedb($sqlcmd, $db_conn);
//        }
    }
}
if (isset($action) && $action=='delete' && isset($cid)) {
    // Invalid this item
    // Check whether this user have the right to modify this contact info
    $sqlcmd = "SELECT * FROM goods WHERE name = '$Name' AND valid='Y'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
//        $GID = $rs[0]['groupid'];
//        if (isset($GroupNames[$GID])) {     // Yes, the user has the right. Perform update
            $sqlcmd = "UPDATE goods SET valid='N' WHERE name='$Name'";
            $result = updatedb($sqlcmd, $db_conn);
//        }
    }
}
//$ItemPerPage = 1;
$PageTitle = '單位人員資訊系統示範';
require_once("../include/header.php");
/*
$GroupIDs = '';
foreach ($GroupNames as $ID => $GroupName) $GroupIDs .= "','" . $ID;
$GroupIDs = "(" . substr($GroupIDs,2) . "')";
$sqlcmd = "SELECT count(*) AS reccount FROM namelist WHERE groupid IN $GroupIDs ";
*/
$sqlcmd = "SELECT count(*) AS reccount FROM goods ";
$rs = querydb($sqlcmd, $db_conn);
$RecCount = $rs[0]['reccount'];
$TotalPage = (int) ceil($RecCount/$ItemPerPage);
if (!isset($Page)) {
    if (isset($_SESSION['NCurPage'])) $Page = $_SESSION['NCurPage'];
    else $Page = 1;
}
//if($Page == 0) $Page = 1;
if ($Page > $TotalPage) $Page = $TotalPage;
$_SESSION['NCurPage'] = $Page;
$StartRec = ($Page-1) * $ItemPerPage;
$sqlcmd = "SELECT * FROM goods "
    . "LIMIT $StartRec,$ItemPerPage";
	/*echo $_SESSION['CurPage'];
	echo " ";
	echo $Page;
	echo " ";
	echo $StartRec;
	echo " ";
	echo $ItemPerPage;*/
$Contacts = querydb($sqlcmd, $db_conn);
?>
<body>
<div>
<Script Language="JavaScript">
<!--
function confirmation(DspMsg, PassArg) {
var name = confirm(DspMsg)
    if (name == true) {
      location=PassArg;
    }
}
-->
</SCRIPT>
<div id="logo">蛋糕列表</div>
<table border="0" width="90%" align="center" cellspacing="0"
  cellpadding="2">
<tr>
  <td width="50%" align="left">
<?php if ($TotalPage > 1) { ?>
<form name="SelPage" method="POST" action="">
  第<select name="Page" onchange="submit();">
<?php 
for ($p=1; $p<=$TotalPage; $p++) { 
    echo '  <option value="' . $p . '"';
    if ($p == $Page) echo ' selected';
    echo ">$p</option>";
}
?>
  </select>頁 共<?php echo $TotalPage ?>頁
</form>
<?php } ?>
<td>
	<form method="POST" name = "Test" action="">
		<input type ="text" size ="30" name="key"></input>
		<input type="submit" name="search" value="搜尋">&nbsp;
	</form>
</td>
  <td>
  <td align="right" width="30%">
    <a href="goodsadd.php">新增</a>&nbsp;
    <a href="logout.php">登出</a>
  </td>
</tr>
</table>
<table class="mistab" width="90%" align="center">
<tr>
  <th width="15%">處理</th>
  <th width="15%">商品</th>
  <th width="15%">圖片</th>
  <th width="20%">價格</th>
  <th width="20%">敘述</th>
</tr>
<?php
foreach ($Contacts AS $item) {
  
  $SeqNo = $item['seqno'];
  
  $Name = $item['name'];
  $Value = $item['value'];
  $Description = $item['description'];
//  $GroupName = '&nbsp;';
//  if (isset($GroupNames[$GroupID])) $GroupName = $GroupNames[$GroupID];
  $DspMsg = "'確定刪除項目?'";
  $PassArg = "'contactmgm.php?action=delete&cid=$cid'";
  echo '<tr align="center"><td>';
  if ($Valid=='N') {
?>
  <a href="contactmgm.php?action=recover&cid=<?php echo $cid; ?>">
    <img src="../images/recover.gif" border="0" align="absmiddle">
    </a></td>
  <td><STRIKE><?php echo $Name ?></STRIKE></td>
<?php } else { ?>
  <a href="javascript:confirmation(<?php echo $DspMsg ?>, <?php echo $PassArg ?>)">
  <img src="../images/cut.gif" border="0" align="absmiddle"
    alt="按此鈕將本項目作廢"></a>&nbsp;
  <a href="contactmod.php?cid=<?php echo $cid; ?>">
  <img src="../images/edit.gif" border="0" align="absmiddle"
    alt="按此鈕修改本項目"></a>&nbsp;
	<a href = "goodsupload.php?seqno=<?php echo $SeqNo?>">
	<img src="../images/upload.png" border="0" height = "18" align="absmiddle"
	alt ="按此鈕修改上傳照片"></a>&nbsp;
  </td>
  <td><?php echo $Name ?></td>   
<?php } ?>
  <td><img src="getimage.php?seqno=<?php echo $SeqNo ?>" border="0" width="200" /></td>
  <td><?php echo $Value ?></td>  
  <td><?php echo $Description ?></td>  
    
  </tr>
<?php
}
?>
</div>
</body>
</html>