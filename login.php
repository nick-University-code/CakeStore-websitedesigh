<?php  session_start();
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");

$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
//$db_conn=mysqli_connect($dbhost, $dbuser, $dbpwd, $dbname);
//$db_conn=mysqli_connect("127.0.0.1","root","","netdesign");

$_SESSION['checkloginout']=1;
$account=filter_input(INPUT_POST, 'account');
$password=filter_input(INPUT_POST, 'password');
$checkvercode=filter_input(INPUT_POST, 'checkvercode');

 

if((!empty($_SESSION['checkvercode'])) && (!empty(filter_input(INPUT_POST, 'checkvercode')))){  //判斷此兩個變數是否為空
    
     if($_SESSION['checkvercode'] != filter_input(INPUT_POST, 'checkvercode')){
         
          $_SESSION['checkvercode'] = ''; //比對正確後，清空將check_word值
         echo "<script>alert('驗證碼輸入有誤');</script>";
           header("refresh:0;url=login.html");
         exit();
     }

}
else{
	echo "<script>alert('請輸入驗證碼');</script>";
           header("refresh:0;url=login.html");
            exit();
}

if(!empty(filter_input(INPUT_POST, 'submit'))){   
    $sqaccount = "SELECT * FROM data WHERE account='$account'";          //檢查帳號
    //$result=mysqli_query($db_conn,$sqaccount);
	$rs = querydb($sqaccount, $db_conn);
	//$exist=mysqli_num_rows($result);
 //  echo count($rs);
	if (count($rs) > 0) {
		$password1 = sha1($password);
      $sql3 = "select * from `data` where `account` = '$account' and `password` ='$password1'";
      //$result1=mysqli_query($db_conn,$sql3);
      //$exist1=mysqli_num_rows($result1);
	  $rs1 = querydb($sql3, $db_conn);
      if(count($rs1)==1){
       //echo "登入成功";
        $_SESSION['account'] = $account;
		$_SESSION['password'] = $password;
		
		$con =  mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname); 
		mysqli_query($con, 'SET NAMES utf8');
		if (empty($con)) {
			print mysqli_error($con);
			die("資料庫連接失敗！");
			exit; 
		}
		 
		  $sqldata = "select * from data"; 
		  $resultdata= mysqli_query($con,$sqldata);
		  while($data = mysqli_fetch_array($resultdata)){ 
				  if($account==$data['account']){
					$identity=$data['identity'];
					$_SESSION['identity'] = $identity;
					 break;
				  }
			  }  
	//	echo $_SESSION['identity'];
      header("refresh:0;url=index1.php");
    }
	else if(count($rs1)==0){                                      //密碼錯
        echo "<script>alert('密碼錯誤');</script>";
        header("refresh:1;url=login.html");
		exit();
    }
  }
	else{      
		 echo "<script>alert('帳號不存在');</script>";
       header("refresh:3;url=login.html");
	   exit();
  }    
}
   ?>
