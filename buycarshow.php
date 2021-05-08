<html>
    <title>購物車</title>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
</html>
<?php session_start();
// Authentication 認證
require_once("../include/auth.php");
// session_start();
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
require_once("../include/xss.php");
$account=$_SESSION['account'];
$totalcost=0;
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$con =  mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname); 
mysqli_query($con, 'SET NAMES utf8');
 if($con->connect_error) {
      die("Coneection failed: ".$con_connect_error());
      }
	  
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
<div id="logo">購買清單</div>
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
  <td align="right" width="30%">
	<font face="Microsoft JhengHei" color="white" size="4"> <a href="index1.php" style="color:#4F4F4F;" >首頁</a></font>
  </td>
</tr>
</table>
<table class="mistab" width="80%" align="center">
<tr>
  <th width="5%">取消</th>
  <th width="10%">數量</th>
  <th width="20%">商品名稱</th>
  <th width="10%">價格</th>
  <th width="10%">小計</th>
</tr>
<?php
$sqll = "select * from `buycar` WHERE account = '$account'"; 
$resulta= mysqli_query($con,$sqll);	  
while($data = mysqli_fetch_array($resulta)){
	if($data['account']==$account){
		$count=$data['count'];
		$Value=$data['price'];
		$SeqNo=$data['seqno'];
//  $GroupName = '&nbsp;';
//  if (isset($GroupNames[$GroupID])) $GroupName = $GroupNames[$GroupID];
  $DspMsg = "'確定刪除項目?'";
  $PassArg = "'contactmgm.php?action=delete&cid=$cid'";
  echo '<tr align="center"><td>';
  if ($Valid=='N') {
?>
<a href = "cacelterm.php?seqno=<?php echo $SeqNo?>">
<img src="../images/cacel.png" border="0" height = "18" align="absmiddle"
	alt ="按此鈕刪除商品"></a>&nbsp;
</td>
  <td><STRIKE><?php echo $Name ?></STRIKE></td>
<?php } else { ?>
   <a href = "cacelterm.php?seqno=<?php echo $SeqNo?>">
	<img src="../images/cacel.png" border="0" height = "18" align="absmiddle"
	alt ="按此鈕刪除商品"></a>&nbsp;
  </td>
  <td>
	 <form action="https://p9056.isrcttu.net/final/countchange.php" method="post" style="font-size:16px; line-height:150%;">
         <select name="cakechoose" onchange="submit()">
          <?php
            echo '<option value="' ,"$count" ,'">', "$count", '</option>';
            for($i=1; $i <= 10; $i++){
                echo '<option value="' ,$i ,'">', $i, '</option>';
            }
            ?>
        </select>
		 <input type="hidden" value=<?php echo $SeqNo ?> name="seqno">
		 </form>
  </td>
  <td><img src="getimage.php?seqno=<?php echo $SeqNo ?>" border="0" width="200" /><?php 
  $sqlname = "select * from `goods` ORDER BY `seqno` ASC"; 
$resultname= mysqli_query($con,$sqlname);	  
while($data1 = mysqli_fetch_array($resultname)){
	if($data1['seqno']==$SeqNo){
			echo $data1['name'];
	}
}?></td>   
<?php } ?>
  <td><?php echo $Value ?></td>  
  <td><?php echo $Value*$count;
			$totalcost+=$Value*$count;
	?></td>    
  </tr>
<?php
	}
}
?>
<?php
echo "總計 :　".$totalcost; 
?>
	<font face="Microsoft JhengHei" color="#4A4E69" size="3" ><b>  
				&nbsp;&nbsp;	<?php    echo '<a href="checkout.php">結帳</a>  <br>'; ?>  
				   </b> </font>   
</div>
</body>
</html>

