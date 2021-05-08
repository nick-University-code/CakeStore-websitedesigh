<?php
if (isset($_POST['Abort'])) {
    header("Location: index1.php");
    exit();
}    
// Authentication 認證
require_once("../include/auth.php");
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
$seqno = $_GET['seqno'];
if (!isset($seqno)) {
    //header ("Location:contactmgm.php");
	echo 'no seqno';
    exit();
}
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
if (isset($GoUpload) && $GoUpload=='1') {
    $fname = $_FILES["userfile"]['name'];
    $ftype = $_FILES["userfile"]['type'];
    if ($_POST["fname"] <> $_POST["orgfn"]) $fname = $_POST["fname"];
    $fsize = $_FILES['userfile']['size'];
    if (!empty($fname) && addslashes($fname)==$fname && $fsize>0 && $fsize<1024000) {
		$fd = fopen($_FILES['userfile']['tmp_name'],'rb');
		$image = fread($fd, $fsize);
		$image = addslashes($image);
        $sqlcmd = "UPDATE goods SET photo='$image',imagetype = '$ftype' WHERE seqno='$seqno'";
        $result = updatedb($sqlcmd, $db_conn);
		//$sqlcmd = "INSERT INTO photo (cid,imagetype,photo) VALUES "
            //. "('$cid','$ftype','$image')";
		//$result = updatedb($sqlcmd, $db_conn);
    } else {
        $ErrMsg = '<font color="Red">'
            . '檔案不存在、大小為0或超過上限(10MBytes)</font>';
    }
}
$sqlcmd = "SELECT * FROM photo WHERE seqno='$seqno' AND valid='Y' LIMIT 0,1";
$rs = querydb($sqlcmd, $db_conn);
$PhotoExist = FALSE;
if (count($rs)>0) $PhotoExist = TRUE;
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
<body>
<div style="text-align:center;margin-top:20px;">
<form enctype="multipart/form-data" method="post" action="" name="ULFile">
<input type="hidden" name="MAX_FILE_SIZE" value="10240000">
<input type="hidden" name="cid" value="<?php echo $cid ?>">
<input type="hidden" name="GoUpload" value="1">
<input type="hidden" name="fname">
<input type="hidden" name="orgfn">
<div style="font-size:24pt;font-weight:bold;">
 商品<?php echo $cid ?>照片修改
</div>
<div style="margin:6px 0;font-size:20px;">
上傳檔名：<input name="userfile" type="file">&nbsp;&nbsp; 
<input type="button" name="upload" value="上傳照片" onclick="startload()">&nbsp;&nbsp;
<input type="submit" name="Abort" value="結束">
</div>
</form>
<div style="margin:6px;font-size:20px;font-weight:bold;">原存影像</div>
<?php if ($PhotoExist) { ?>
<img src="getimage.php?seqno=<?php echo $seqno ?>" border="0" width="200" />
<?php } else { ?>
<img src="../images/nophoto.png" border="0" width="200" />
<?php } ?>
</div>
</body>
</html>
