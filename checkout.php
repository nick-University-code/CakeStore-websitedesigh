<html>
    <title>結帳頁面</title>
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
<div id="logo">結帳</div>
<table border="0" width="90%" align="center" cellspacing="0"
  cellpadding="2">
<tr>
  <td align="left">
	<font face="Microsoft JhengHei" color="white" size="4"> <a href="index1.php" style="color:#4F4F4F;" >首頁</a></font>
  </td>
</tr>
</table>
<table class="mistab" width="60%" align="center">
<tr>
  <th width="20%">商品名稱</th>
   <th width="10%">數量</th>
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
  echo '<tr align="center"><td>';
  if ($Valid=='N') {
?>
  <td><STRIKE><?php echo $Name ?></STRIKE></td>
<?php } else { ?>
	<?php 
		  $sqlname = "select * from `goods` ORDER BY `seqno` ASC"; 
		$resultname= mysqli_query($con,$sqlname);	  
		while($data1 = mysqli_fetch_array($resultname)){
			if($data1['seqno']==$SeqNo){
					echo $name=$data1['name'];
			}
}?>  
  </td>

<?php } ?>
  <td><?php echo $count ?></td>  
  <td><?php echo $Value*$count;
			$totalcost+=$Value*$count;
	?></td>    
  </tr>
<?php
	}
}
?>
<table border="0" width="90%" align="center" cellspacing="0"
  cellpadding="2">
    <td align="right" width="30%">
	<font face="Microsoft JhengHei" color="black" size="4"> 
	<?php
	echo "總計 :　".$totalcost;  
	
	$sqlname = "select * from `data`"; 
		$resultname= mysqli_query($con,$sqlname);	  
		while($data = mysqli_fetch_array($resultname)){
			if($data['account']==$account){
					$accountname=$data['name'];
					$phone=$data['phone'];
					$address=$data['address'];
			}
		}
	?>
	</font>
  </td>
</table>

<script>
function creditpay(index){
	var check=index;
	if(check==2){
	document.getElementById( 'cardnumber' ).type = 'text';
	}
	else{ 
	document.getElementById( 'cardnumber' ).type = 'hidden';
	} 
}
</script>

 <div  style="color:black;    position: relative; left:30%">
     <form action="" method="post" style="font-size:16px; line-height:150%;">
            姓名   : <br>
                <input type="text" name="account" value="<?php echo $accountname;?>">
            <br>
            電話   : <br>
                <input type="text" name="phone" value="<?php echo "0".$phone;?>">

            <br>
            地址   : <br>
                <input type="text" name="address" value="<?php echo $address;?>">
            <br>
            備註 : <br>
                <input type="text" name="tt" >
            <br>
            付款方式 : <br>
              <select name="pay" id="payselect" onChange="creditpay(this.selectedIndex);">
			   <option value="">-請選擇付款方式-</option>
			   <option value="facetoface">貨到付款</option>
				<option value="credit">信用卡</option>
               </select> 
			   <br>
			
			 <br>
                <input type="hidden" name="creditnumber" id="cardnumber" placeholder="信用卡卡號">
            <br>
  </form>

</div>
</div>
</body>
</html>

