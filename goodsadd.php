<?php
// 使用者點選放棄新增按鈕
if (isset($_POST['Abort'])) header("Location: index1.php");
// Authentication 認證
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
require_once("../include/xss.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$sqlcmd = "SELECT * FROM goods WHERE valid='Y'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');
$UserGroupID = $rs[0]['groupid'];
if (!isset($Description))  $Description ='';
if (!isset($Name)) $Name = '';
if (!isset($Value)) $Phone = '';
// 取出群組資料
/*$sqlcmd = "SELECT * FROM groups WHERE valid='Y' AND (groupid='$UserGroupID' "
    . "OR groupid IN (SELECT groupid FROM userpriv "
    . "WHERE loginid='$LoginID' AND privilege > 1 AND valid='Y'))";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs)<=0) die('No group could be found!');  */
$GroupNames = array();
/*foreach ($rs as $item) {
    $ID = $item['groupid'];
    $GroupNames[$ID] = $item['groupname'];
}*/


$GroupIDs = '';
$cGender = array("Male","Female","US");
foreach ($GroupNames as $ID => $GroupName) $GroupIDs .= "','" . $ID;
$GroupIDs = "(" . substr($GroupIDs,2) . "')";
if (isset($Confirm)) {   // 確認按鈕
    if (empty($Name)) $ErrMsg = '姓名不可為空白\n';
    if (empty($Value)) $ErrMsg = '價格不可為空白\n';
    //if (empty($Description) || $GroupID<>addslashes($GroupID)) $ErrMsg = '群組資料錯誤\n';
    //$PYear = substr($Birthday,0,4);
	//$CYear = date("Y");
	//if (isset($GoUpload) && $GoUpload=='1') {
		$fname = $_FILES["userfile"]['name'];
		$ftype = $_FILES["userfile"]['type'];
		if ($_POST["fname"] <> $_POST["orgfn"]) $fname = $_POST["fname"];
		$fsize = $_FILES['userfile']['size'];
		echo $fname;
		echo '<br>';
		echo $ftype;
		echo '<br>';
		echo $fsize;
		echo '<br>';
		if (!empty($fname) && addslashes($fname)==$fname && $fsize>0 && $fsize<1024000) {
			$fd = fopen($_FILES['userfile']['tmp_name'],'rb');
			$image = fread($fd, $fsize);
			$image = addslashes($image);
			//$sqlcmd = "UPDATE photo SET valid='N' WHERE cid='$cid'";
			//$result = updatedb($sqlcmd, $db_conn);
			//$sqlcmd = "INSERT INTO goods (imagetype,photo) VALUES "
				//. "('$ftype','$image')";
			//$result = updatedb($sqlcmd, $db_conn);
		} else {
			$ErrMsg = '<font color="Red">'
				. '檔案不存在、大小為0或超過上限(10MBytes)</font>';
		}
		
	//}
	//if(($CYear-$PYear)<3) $ErrMsg = '年齡小於3歲';
    if (empty($ErrMsg)) {
        // 確定此用戶可設定所選定群組的聯絡人資料
        $sqlcmd = "SELECT privilege FROM userpriv "
            . "WHERE loginid='$LoginID' AND groupid='$GroupID' AND privilege>0";
        $rs = querydb($sqlcmd, $db_conn);
        // 若權限表未設定權限，則設為用戶的群組
        if (count($rs) <= 0) $GroupID = $UserGroupID;
		$Name = xsspurify(addslashes($Name));
		//$Address = xsspurify(addslashes($Address));
        $sqlcmd='INSERT INTO goods (name,photo,imagetype,value,description,valid) VALUES ('
            . "'$Name','$image','$ftype','$Value','$Description','Y')";
        $result = updatedb($sqlcmd, $db_conn);

        $sqlcmd = "SELECT count(*) AS reccount FROM namelist WHERE groupid IN $GroupIDs ";
        $rs = querydb($sqlcmd, $db_conn);
        $RecCount = $rs[0]['reccount'];
        $TotalPage = (int) ceil($RecCount/$ItemPerPage);
        $_SESSION['CurPage'] = $TotalPage; 

        header("Location: index1.php");
    }
	
	
}
$PageTitle = '示範新增人員資料';
require_once("../include/header.php");
?>
<script type="text/javascript">
<!--
function startload() {
    var Ary = document.ULFile.userfile.value.split('\\');
    document.ULFile.fname.value=Ary[Ary.length-1];
    document.ULFile.orgfn.value=document.ULFile.userfile.value
    document.forms['ULFile'].submit();
    return true;
}
-->
</script>
<div align="center">
<form enctype="multipart/form-data" action="" method="post" name="inputform">
<b>新增商品</b>
<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
<tr height="30">
  <th width="40%">圖片</th>
  <td><input name="userfile" type="file"></td>
  
</tr>
<tr height="30">
  <th width="40%">名字</th>
  <td><input type="text" name="Name" value="<?php echo $Name ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">價錢</th>
  <td><input type="text" name="Value" value="<?php echo $Value?>" size="30"></td>
</tr>
<tr height="30">
  <th width="40%">描述</th>
  <td><input type="text" name="Description" value="<?php echo $Description ?>" size="50"></td>
</tr>

</table>
<input type="submit" name="Confirm" value="存檔送出" onclick = "startload()" >&nbsp;
<input type="submit" name="Abort" value="放棄新增">
</form>
</div>
<?php 
require_once ('../include/footer.php');
?>