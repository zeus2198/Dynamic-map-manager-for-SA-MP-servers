<?php
require("config.php");
$con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database);
if (!$con)
{
	echo "Connection to MySQL database failed.";
	exit();
}
mysqli_query($con, "CREATE TABLE IF NOT EXISTS `maps` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(25) NOT NULL, `stream` FLOAT(15,7) NOT NULL, PRIMARY KEY (`id`))");
?>
<!--
================================================================================
 *                 Dynamic Web based Map loader/unloader
 *
 * author: BroZeus ( http://forum.sa-mp.com/member.php?u=224655 )
 * web demo: http://plwip.tk/mydemo/mapadder/
 * version: 1.0
 ================================================================================	
CREDITS :

		SA-MP Team      	: For developing and imrpoving SA-MP
		Zeex            	: For zcmd include
		BlueG           	: For MySQL plugin
		Bjoern Klinggaard   : For bPopup, a javascript based plugin used in webscripts
		Alan Williamson     : For Line numberer plugin, a javascript based plugin used in webscripts
		And I guess me too?
-->
<html>
<head>
<title>Map Deleting Facility</title>
<script src="jquery/jquery-1.11.2.min.js"></script>
<script src="jquery/jquery-ui.js"></script>
<script src="jquery/bpop.js"></script>
<style>
	@font-face 
	{
		font-family: air;   
		src: url('fonts/Airstream-webfont.eot'); 
		src: url('fonts/Airstream-webfont.woff') format('woff'),
			 url('fonts/Airstream-webfont.ttf')  format('truetype'); 
    }
	@font-face 
	{
		font-family: aller;   
		src: url('fonts/aller.woff') format('woff'); 
	}
	
	@font-face 
	{
		font-family: Gothic;   
		src: url('fonts/Gothic.ttf'); 
	}
	
	#body_content
	{
		text-align: center;
		margin: 0 auto;
		position: relative;	
		min-height: 500px;
		min-width: 500px;
		width: auto;
		height: auto;
		overflow: visible;
		visibility: visible;
		display: block
	}	

	body 
	{
		background:url('images/hdlast.jpg') #000 no-repeat;
		background-attachment: fixed;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		background-size: cover;
	}
	
	#mh
	{
		font-weight:bold;
		opacity:0.75;
		color:#F0F0F0;
		letter-spacing:1pt;
		word-spacing:2pt;
		font-size:60px;
		text-align:center;
		font-family:air;
		line-height:1;
	}	
	
	#mh2
	{	
		opacity:0.50;
		color:#F0F0F0;
		letter-spacing:10px;
		word-spacing:2pt;
		font-size:20px;
		text-align:center;
		font-family:air;
		line-height:1.2;
	}
	
	#footer 
	{
		font-size: 14px;
		color:#F0F0F0;
		font-family: Arial;
		text-align: center;
		position: absolute;
		bottom: 0px;
		left: 0px;
		width: 100%;
		height: 20px;
		visibility: visible;
		display: block
	}
	
	.button
	{   
		display: inline-block;
		cursor: pointer;
		height: 20px;
		width: 120px;       
		padding:10px;
		background-color: rgba(255,255,255,0.3);
		-webkit-border-radius:40px;
		-moz-border-radius:40px;
		border-radius:40px;
		border: solid #FFFFFF 1px;	
		color: #FFFFFF;
		text-align: center;
		font-family: aller;
		letter-spacing: 2px;
		font-size: 15px;
		text-decoration: none;
		-webkit-transition: all 0.5s ease-out;  
		-moz-transition: all 0.5s ease-out;  
		-ms-transition: all 0.5s ease-out;  
		-o-transition: all 0.5s ease-out;
		transition: all 0.5s ease-out;   	
	}
	
	.button:hover
	{
		background-color:rgba(255, 255, 255, 0.8);		
	}
	
	#pop
	{
		text-align:center;
		position:absolute;
		display:none;
	}
	
	#pop_box
	{
		text-align:center;
		height:auto;
		min-height:110px;
		max-height:600px;
		padding:100px 100px;
		width:550px;
		border-radius:80px;	
		background: white;
		padding:10px 10px;
		box-shadow: -5px -5px 50px #ffffff,
					5px -5px 50px #ffffff,
					-5px 5px 50px #ffffff,
					5px 5px 50px #ffffff;
	}
	
	#hh
	{
		font-size:60px;
		font-family:Gothic;
		color:rgba(255,255,255,0.6);
	}
	
	#clbutton
	{
		display:inline-block;
		padding:7px 7px;
		text-align:center;
		font-family:aller;
		width:80px;
		border-radius:10px;
		border: solid #ff0000 2px;
		color: #ff0000;
		cursor:pointer;
		font-size: 20px;
		transition: all 0.3s ease-out;		
	}
	
	#clbutton:hover
	{
		color: white;
		background: #ff0000;
	}
	
	#con_button
	{
		display:inline-block;
		padding:7px 7px;
		text-align:center;
		font-family:aller;
		width:90px;
		border-radius:10px;
		border: solid #6CCB94 2px;
		color: #6CCB94;
		cursor:pointer;
		font-size: 20px;
		transition: all 0.3s ease-out;		
	}
	
	#con_button:hover
	{
		color: white;
		background: #6CCB94;
	}
	
	hr 
	{
		width:900px;
		color: rgba(255,255,255,0.6);
	}
	
	.m_css
	{
		cursor:pointer;
		transition: all 0.2s ease-out;	
	}
	 
	.m_css:hover
	{
		box-shadow: inset 0px -3px 0px #0c0;
		
	}
	
	.d_css
	{
		cursor:pointer;
		transition: all 0.2s ease-out;	
	}
	 
	.d_css:hover
	{
		box-shadow: inset 0px -3px 0px #00f;
	}
	
	table
	{		
		font-family: aller;  
		font-size: 115%;
		max-height:400px;
		width: 700px;
		overflow:hidden;
		display:inline-block;
	}
	
	table.data
	{		
		overflow:hidden;
		overflow-y:scroll;
	}
	
	th 
	{
		background: black;
		color: white;
		font-weight:normal;
		padding: 10px 20px;
		text-align: center;
		width:184px;
		box-shadow: -5px -5px 50px #000,
					5px -5px 50px #000,
					-5px 5px 50px #000,
					5px 5px 50px #000;
	}
	
	td 
	{    
		color: #000;
		padding: 10px 20px;
		text-align: center;
		width:233px;
	}
	
	tr:nth-child(even) {background: rgba(150,150,150,0.7)}
	tr:nth-child(odd) {background: rgba(255,255,255,0.7)}
    
</style>
<script>
var popid = -1;

function show_map(id)
{
	var a = "<img src='images/load.gif' style='height:100px;width:100px;display:inline-block'></img>";
	$("#pop").html(a);
	popid = $('#pop').bPopup({
		fadeSpeed: 'slow', 
        followSpeed: 'slow',
		modalClose: false,
        opacity: 0.6,
		positionStyle: 'fixed'
		});
	$.ajax({ 
			method: "POST",
			url: "map_info.php",
			data: { mid: id }			
        }).done(function(data) {
			$("#pop").slideUp(function(){
				$("#pop").html(data);
				$("#pop").fadeIn();				
			});		
		});
}

function delete_final(id)
{
	var a = "<img src='images/load.gif' style='height:100px;width:100px;display:inline-block'></img>";
	$("#pop").html(a);
	$.ajax({ 
			method: "POST",
			url: "map_delete.php",
			data: { mid: id }			
        }).done(function(data) {
			$("#pop").slideUp(function(){
				$("#pop").html(data);
				$("#pop").fadeIn();
				$("#tr_"+id).remove();
			});		
		});
}

function delete_map(id)
{
	var a = "<div id='pop_box'><span style=\"font-family:Gothic;;font-size:60px;text-align:center;display:inline-block;color:red\">Are you sure?</span>"+
			"<br><br>"+
			"<span style=\"font-family:Gothic;font-size:15px;text-align:center;display:inline-block;color:black;\">Are you sure you want to delete Map ID <b>"+id+"</b></span><br><br>"+
			"<br><br>"+
			"<div id=\"con_button\" onclick=\"delete_final("+id+")\">Continue</div>&nbsp &nbsp <div id=\"clbutton\" onclick=\"popid.close()\">Close</div>"+
			"<br><br></div>";
	$("#pop").html(a);
	popid = $('#pop').bPopup({
		fadeSpeed: 'slow', 
        followSpeed: 'slow',
		modalClose: false,
        opacity: 0.6,
		positionStyle: 'fixed'
		});	
}


$(function() 
{
	$("#mh, #mh2, #hh, .button, hr, table, #pop").disableSelection();
});

</script>
</head>
<body>
<!-- Hidden content -->
<div id="pop">
</div>
<!-- Hidden content END -->
<div id="body_content">
	<div id="mh"><?php echo $server_name; ?></div>
	<div id ="mh2"><?php echo $server_logo; ?></div>
	<br><br>
	<hr>
	<div id="hh">Map Deleting Facility</div>
	<br><br>
	<table>
		<tr>
			<th>Map ID</th>
			<th>Map Name</th>
			<th>Map Delete</th>
		</tr>	
	</table>
	<br>
	<table class="data">
	<?php	
		$res = mysqli_query($con, "SELECT * FROM `maps` ORDER BY `id` ASC");
		if(mysqli_num_rows($res) == 0)
		{
			echo "<tr>";
			echo "<td>---</td>";
			echo "<td>---</td>";
			echo "<td>---</td>";
			echo "</tr>";
		}
		else
		{
			while($row = mysqli_fetch_assoc($res))
			{
				echo "<tr id ='tr_".$row["id"]."'>";
				echo "<td>".$row["id"]."</td>";
				echo "<td class='m_css' onclick='show_map(".$row["id"].")'>".$row["name"]."</td>";
				echo "<td class='d_css' onclick='delete_map(".$row["id"].")'>Delete</td>";
				echo "</tr>";
			}
		}
		mysqli_free_result($res);
		mysqli_close($con);
	?>		
	</table>
</div>
</body>
</html>