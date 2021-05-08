<?php session_start();
// Authentication 認證
require_once("../include/auth.php");
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
require_once("../include/xss.php");
$seqno = $_GET['seqno'];

$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$con =  mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname); 
mysqli_query($con, 'SET NAMES utf8');
 if($con->connect_error) {
      die("Coneection failed: ".$con_connect_error());
      }
	//  echo $seqno."-".$account;
            $sqlcmd = "DELETE FROM `goods` WHERE `seqno` = '$seqno'";
            //$result = updatedb($sqlcmd, $db_conn);
			mysqli_query($con,$sqlcmd);
	header("Location: index1.php");
require_once("../include/header.php");
?>
