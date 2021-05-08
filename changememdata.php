<?php  session_start();
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");

$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);

$account=$_SESSION['account'];
     
    $sqcheck = "select * from data where account = '$account'";          //檢查帳號
    $rs = querydb($sqcheck, $db_conn);
    if(count($rs)<=0){
         echo "<script>alert('帳號不存在')</script>";
         header("refresh:0;url=changedata.html");
         exit();
    }
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
            $name=$data['name'];
            $phone=$data['phone'];
            $gender=$data['gender'];
            $address=$data['address'];
            $birthday=$data['birthday'];
             $email=$data['email'];
             break;
          }
      }  
?>

<html>
    <head>
        <title>個人資料修改</title>
           <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
  
        <form action="sendchangedata.php" method="post" style="font-size:16px; line-height:150%;">
            帳號   : <br>
                 <font size="4px" color="gray"><?php echo $account ?> </font>
            <br>
			 密碼   : <br>
                 <font size="4px" color="gray"><?php for($i=0;$i<strlen($_SESSION['password']);$i++){ echo "&nbsp•" ;} ?> </font>
				<font face="Microsoft JhengHei" color="#4A4E69" size="3" ><b>  
				&nbsp;&nbsp;	<?php    echo '<a href="sendpwdinfo.php">更改密碼</a>  <br>'; ?>  
				   </b> </font>   
           姓名 : <br>
                <input type="text" name="name"  value="<?php echo $name ?>">
            <br>
            性別 : <br>
                    <input type="radio" value="M" name="gender" <?php if($gender=='M'){echo'checked'; }?>>男
                    <input type="radio" value="F" name="gender" <?php if($gender=='F'){echo'checked'; }?>>女
                    <input type="radio" value="X" name="gender" <?php if($gender=='X'){echo'checked'; }?>>其他
            <br>
               
               生日 : <br>
                <input type="date" name="birthday" value="<?php echo $birthday ?>" >
              <br>
             信箱 : <br>
               <input type="text" name="email" value="<?php echo $email ?>" required/>
               <br>    
             地址 : <br>
               <input type="text" name="address"  value="<?php echo $address ?>" required/>
               <br>
               
             電話 : <br>
               <input type="text" name="phone"  value="<?php echo "0".$phone ?>" required/>
               <br>    <br>    
  <input type="submit" value="提交">
  <input type="reset" value="清除">
  <input type ="button" onclick="javascript:location.href='index1.php'" value="首頁">
  
        </form>

    </body>
</html>





