<?php

require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
//require_once ('../include/footer.php');

$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
//$db_conn=mysqli_connect($dbhost, $dbuser, $dbpwd, $dbname);
//$db_conn=mysqli_connect("127.0.0.1","root","","netdesign");

 mysqli_query($db_conn, 'SET NAMES utf8');


   if($db_conn->connect_error) {
   die("Coneection failed: ".$db_conn_connect_error());
   }
   
//if (isset(filter_input(INPUT_POST, 'submit'))) {   // 確認按鈕
	$name=filter_input(INPUT_POST, 'name');
	$account=filter_input(INPUT_POST, 'account');
	$password1=filter_input(INPUT_POST, 'password');
	$check=filter_input(INPUT_POST, 'check');
	$phone=filter_input(INPUT_POST, 'phone');
	$gender=filter_input(INPUT_POST, 'gender');
	$address=filter_input(INPUT_POST, 'address');
	$birthday=filter_input(INPUT_POST, 'birthday');
	$email=filter_input(INPUT_POST, 'email');
	$check=filter_input(INPUT_POST, 'check');
     $day = date("Y-m-d");
     $a=strtotime($day);
     $b=strtotime($birthday);    
	
if($b>=$a){
	echo "<script>alert('生日不合規定，請重新輸入');history.go(-1);</script>";
	exit();
}
else{    
    if (empty($name)) $ErrMsg = '姓名不可為空白\n';
    if (empty($phone)) $ErrMsg = '電話不可為空白\n';
	$sql1 = "select * from data where account = '$account'";          //檢查帳號
    //$result=mysqli_query($db_conn,$sql1);
    //$exist=mysqli_num_rows($result);
    $result = querydb($sql1, $db_conn);
    //if($exist==1){
    if(count($result)==1){
        echo "<script>alert('帳號已存在');history.go(-1);</script>";
		exit();
      //   header("refresh:1;url=signup.html");
    }
	else{
    if($account &&  $password1){
        if(strcmp($check,$password1)){
            echo "<script>alert('兩次密碼不同');history.go(-1);</script>";
			exit();
         //    header("refresh:0;url=signup.html");  //兩次密碼不同
      }
    if (empty($ErrMsg)) {
		$password = sha1($password1);	 
        $sqlcmd="INSERT INTO data (name, account, password, gender, address, birthday, phone, email) VALUES ('$name','$account','$password','$gender','$address','$birthday','$phone','$email')";
		$result = querydb($sqlcmd, $db_conn);
	
		
    }
   }
  }
  header("refresh:1;url=index.php");
 //}
}
?>
