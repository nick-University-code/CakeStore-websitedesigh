<?php
session_start(); 
require_once ("../include/gpsvars.php");
require_once ("../include/configure.php");
require_once ("../include/db_func.php");
require_once ("../include/xss.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$ErrMsg = '';
$con =  mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname); 
		mysqli_query($con, 'SET NAMES utf8');
		if (empty($con)) {
			print mysqli_error($con);
			die("資料庫連接失敗！");
			exit; 
		}
$account = $_SESSION['account'] ;		 
		  $sqldata = "select * from data"; 
		  $resultdata= mysqli_query($con,$sqldata);
		  while($data = mysqli_fetch_array($resultdata)){ 
				  if($account==$data['account']){
					$eMail=$data['email'];
					$_SESSION['email'] = $eMail;
					 break;
				  }
			  }  
if (empty($ErrMsg)) {
    $ReqID = sha1($eMail . date('His'));
    $sqlcmd = "UPDATE data SET reqid='$ReqID'"
        . "WHERE email='$eMail' AND account='$account'";
    $result = updatedb($sqlcmd, $db_conn);
    $Link = 'https://p9056.isrcttu.net/final/adminsetpwd.php?ReqID=' 
    . $ReqID;
    // Notify user about the account and password  
    $From = "Mail Master <mailmaster@gm.ttu.edu.tw>";
    $To = $eMail;
    $Subject = '開放資料管理系統 管理者密碼重置通知';
    $Recipient = $eMail;
    $Message = "\n有用戶透過重設密碼功能申請開放資料管理系統管理者密碼重置，"
        . "如果不是您所申請，則可不予理會\n\n"
        . "請點選下列連結進入系統設定新密碼：\n\n"
        . $Link . "\n\n"
        . "This is an automactically generated response email. "
        . "If you do not expect to receive it, then someone might regist your "
        . "email address in our Opendata management system. "
        . "If this is the case, please accept our apology.";
    require_once('sendmail_inc.php');	
}
//require_once("../include/sendmessage.php");
?>
<body>
<?php if (empty($ErrMsg)) { ?>
  <div style="text-align:center;margin:8px 0 0 0;">
  <?php  echo "<script>alert('密碼重置郵件已寄到您的電子郵件信箱，請依指示重設您的密碼。');</script>";
   header("refresh:0;url=index1.php");
	exit(); 
	?>
  </div>
<?php } else { ?>
  <div style="text-align:center;margin:8px 0 0 0;">
  資料有錯誤，請回上一頁重新輸入。
  </div>
<?php }?>
<div style="text-align:center;margin:12px 0;">
<a href="changememdata.php">返回登入頁面</a>
</div>
</body>
</html>
