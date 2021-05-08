<html>
    <title>不開心蛋糕店</title>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
</html>
<?php session_start();
$identity = $_SESSION['identity'];

// Authentication 認證
require_once("../include/auth.php");
// session_start();
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
if(isset($search)){
	echo $key;
	header("Location: goodssearch.php?key=$key");
}
if (!isset($GID)) $GID='';
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
    echo ">$p</option>\n";
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
	<font face="Microsoft JhengHei" color="white" size="4"> <a href="changememdata.php" style="color:#4F4F4F;" >個人資料修改</a></font>
 
   <font face="Microsoft JhengHei" color="white" size="4"> <a href="buycarshow.php" style="color:#4F4F4F;" >購物車</a></font>

	 <?php  if( $identity == 2){ ?>
		 <font face="Microsoft JhengHei" color="white" size="4"> <a href="goodsadd.php" style="color:#4F4F4F;" >新增</a></font>
	 <?php	 } ?>
	</font>
   <font face="Microsoft JhengHei" color="white" size="4"> <a href="loginout.php" style="color:#4F4F4F;"  >登出</a></font> 
  </td>
</tr>
</table>
  <?php  if( $identity == 1){ ?> 
  <table class="mistab" width="90%" align="center">
<tr>
  <th width="10%">購買</th>
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
<?php } else { ?>

   <a href = "buycar.php?seqno=<?php echo $SeqNo?>&price=<?php echo $Value; ?>">
	<img src="../images/buycake.png" border="0" height = "18" align="absmiddle"
	alt ="按此鈕送出購買量"></a>&nbsp;
	
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
  
  <?php	 } ?>
  
<?php  if( $identity == 2){ ?> 
  <table class="mistab" width="90%" align="center">
<tr>
<th width="5%">處理</th> 
  <th width="10%">購買</th>
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
	<a href = "goodsupload.php?seqno=<?php echo $SeqNo?>">
	<img src="../images/upload.png" border="0" height = "18" align="absmiddle"
	alt ="按此鈕修改上傳照片"></a>&nbsp;
	<a href = "cacellist.php?seqno=<?php echo $SeqNo?>">
	<img src="../images/cacel.png" border="0" height = "18" align="absmiddle"
	alt ="按此鈕刪除列表商品"></a>&nbsp;
  </td>
  <td>

   <a href = "buycar.php?seqno=<?php echo $SeqNo?>&price=<?php echo $Value; ?>">
	<img src="../images/buycake.png" border="0" height = "18" align="absmiddle"
	alt ="按此鈕送出購買量"></a>&nbsp;

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
  
  <?php	 } ?>
</div>
</body>
</html>  
<?php   
/*
	<form method="post" action="https://p9056.isrcttu.net/final/buycar.php">
   <select name="cakenumber" >
          <?php
            echo '<option value="' ,0 ,'">', "數量選擇", '</option>';
            for($i=1; $i <= 5; $i++){
                echo '<option value="' ,$i ,'">', $i, '</option>';
            }
            ?>
   </select>
	</form>*/ ?>