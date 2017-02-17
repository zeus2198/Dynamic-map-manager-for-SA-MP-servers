<?php
if(!isset($_POST['objs']) || !isset($_POST['nmap']) || !isset($_POST['ddraw']))
{
	echo "You are not authorized to view this page.";
	exit();
}
require("config.php");
$con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database);
if (!$con) 
{
    echo "<span style='color:#ff0000;font-size:40px;font-family:Gothic;display:inline-block;'>Uh-Oh!</span><br><br><span style='color:#000000;font-size:15px;font-family:Gothic;display:inline-block;'>Looks like connection to MySQL database failed. Please report to webamaster.<br>Reason : <b>".mysqli_connect_error()."</b></span><br><br>";
	exit();
}
mysqli_query($con, "CREATE TABLE IF NOT EXISTS `maps` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(25) NOT NULL, `stream` FLOAT(15,7) NOT NULL, PRIMARY KEY (`id`))");

//---------------------Table check to be inserted here

$name = mysqli_real_escape_string($con, $_POST['nmap']);
$obj = json_decode($_POST['objs'], true);
$query = "CREATE TABLE IF NOT EXISTS `$name` (`model` int(11) NOT NULL, `x` FLOAT(15, 7) NOT NULL, `y` FLOAT(15, 7) NOT NULL, `z` FLOAT(15, 7) NOT NULL, `rx` FLOAT(15, 7) NOT NULL, `ry` FLOAT(15, 7) NOT NULL, `rz` FLOAT(15, 7) NOT NULL)";
mysqli_query($con, $query);
mysqli_query($con, "INSERT INTO `maps`(`name`, `stream`) VALUES('$name', '".$_POST['ddraw']."')");
for($i = 0; $i < sizeof($obj); $i++)
{
	mysqli_query($con, "INSERT INTO `$name`(`model`, `x`, `y`, `z`, `rx`, `ry`, `rz`) VALUES('".$obj[$i]['model']."', '".$obj[$i]['x']."', '".$obj[$i]['y']."', '".$obj[$i]['z']."', '".$obj[$i]['rx']."', '".$obj[$i]['ry']."', '".$obj[$i]['rz']."')");
}
mysqli_close($con);
echo "<span style='color:#00cc00;font-size:40px;font-family:Gothic;display:inline-block;'>Done!</span><br><br><span style='color:#000000;font-size:15px;font-family:Gothic;display:inline-block;'>Map named <b>$name</b><br> has been sucessfully inserted into database</span><br><br>";
?>