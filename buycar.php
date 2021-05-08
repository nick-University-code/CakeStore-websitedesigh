<?php session_start();
if (isset($_POST['Abort'])) {
    header("Location: index1.php");
    exit();
}    
// Authentication 認證
require_once("../include/auth.php");
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
require_once("../include/xss.php");
$seqno = $_GET['seqno'];
$price = $_GET['price'];
$account=$_SESSION['account'];

$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$con =  mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname); 
mysqli_query($con, 'SET NAMES utf8');
 if($con->connect_error) {
      die("Coneection failed: ".$con_connect_error());
      }

$sqll = "select * from `buycar` WHERE account = '$account' AND seqno='$seqno'"; 
$resulta= mysqli_query($con,$sqll);	  
while($data = mysqli_fetch_array($resulta)){
	if($data['account']==$account){
		echo $data['count'];
	}
}
	$sqlcmd = "SELECT * FROM buycar WHERE account = '$account' AND seqno='$seqno'";
    $rs = querydb($sqlcmd, $db_conn);
	if (count($rs) > 0) {
			/*$count++;
            $sqlcmd = "UPDATE buycar SET count='$count' WHERE account = '$account' AND seqno='$seqno'";
            $result = updatedb($sqlcmd, $db_conn);*/
		    echo "<script>alert('您已購買過此商品，欲改數目請至購物車頁面')</script>";
			 echo '<meta http-equiv=REFRESH CONTENT=0;url=index1.php>';
    } 
	else{
	
	//	$sqlcmd='INSERT INTO buycar (account,seqno,price,count) VALUES ('
     //       . "'$account','$seqno','$price','$count')";
		$sqlcmd1 = "INSERT INTO buycar (account, seqno, price, count) VALUES ('$account','$seqno','$price','1')";
        $result = querydb($sqlcmd1, $db_conn);
		header("Location: index1.php");
	}
	
require_once("../include/header.php");
?>
