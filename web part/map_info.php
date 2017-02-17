<?php
if(!isset($_POST['mid']))
{
	echo "You are not authorized to view this page.";
	exit();
}
require("config.php");
$con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database);
if (!$con) 
{
    echo "<div id='pop_box'><span style='color:#ff0000;font-size:40px;font-family:Gothic;display:inline-block;'>Uh-Oh!</span><br><br><span style='color:#000000;font-size:15px;font-family:Gothic;display:inline-block;'>Looks like connection to MySQL database failed. Please report to webamaster.<br>Reason : <b>".mysqli_connect_error()."</b></span><br><br><div id='clbutton' onclick='popid.close()'>Close</div><br><br></div>";
	exit();
}
$id = $_POST['mid'];
$res = mysqli_query($con, "SELECT * FROM `maps` WHERE `id` = '$id'");
if(mysqli_num_rows($res) == 0)
{
	echo "<div id='pop_box'><span style='color:#ff0000;font-size:40px;font-family:Gothic;display:inline-block;'>Uh-Oh!</span><br><br><span style='color:#000000;font-size:15px;font-family:Gothic;display:inline-block;'>Looks like someone deleted that map while you were viewing the page. Please reload the page.</span><br><br><div id='clbutton' onclick='popid.close()'>Close</div><br><br></div>";
	mysqli_free_result($res);
	mysqli_close($con);
	exit();
}
$row = mysqli_fetch_assoc($res);
$name = $row["name"];
$stream = $row["stream"];
mysqli_free_result($res);
$res = mysqli_query($con, "SELECT COUNT(*) FROM `$name`");
$row = mysqli_fetch_assoc($res);
$obj = $row['COUNT(*)'];
mysqli_free_result($res);
mysqli_close($con);
echo "<div id='pop_box'><span style='color:#00cc00;font-size:40px;font-family:Gothic;display:inline-block;'>Map Information</span><br><br><span style='color:#000000;font-size:15px;font-family:Gothic;display:inline-block;'>Map ID : <b>$id</b><br>Map Name : <b>$name</b><br>Number of Objects : <b>$obj</b><br>Objects' Stream distance : <b>$stream</b><br> </span><br><br><div id='clbutton' onclick='popid.close()'>Close</div><br><br></div>";
?>