<?php  session_start();
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");

 $con =  mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname); 
mysqli_query($con, 'SET NAMES utf8');
     if($con->connect_error) {
      die("Coneection failed: ".$con_connect_error());
      }
   

 $password=filter_input(INPUT_POST, 'password');
 $account=$_SESSION['account'];
 $passwordcheck=filter_input(INPUT_POST, 'passwordcheck');

       if(strcmp($passwordcheck,$password)){
            echo "<script>alert('兩次密碼不同');history.go(-1);</script>";
            exit();
      }
       $small="qwertyuiopasdfghjklzxcvbnm";
           $big="QWERTYUIOPASDFGHJKLZXCVBNM";
           $number="0123456789";
           $scount=0;$ncount=0;$bcount=0;
           for($i=0;$i<strlen($password);$i++){
               if(!strchr($small,$password[$i])){
                   $scount++;
               }
               if(!strchr($big,$password[$i])){
                   $bcount++;
               }
               if(!strchr($number,$password[$i])){
                   $ncount++;
               }
           }
       if($ncount==0||$bcount==0||$scount==0){
                 echo "<script>alert('密碼不合規定');history.go(-1);</script>";
                 exit();
           }
 


         $password1 = sha1($password);
         $sqlchangedata="UPDATE `data` SET `password`='$password1' WHERE `account` = '$account'"; 
         mysqli_query($con,$sqlchangedata);
         echo "修改完成";

     header("refresh:1;url=index1.php");
   
    $con->close();

?>
