<?php require("config.php"); ?>
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
<title>Dynamic Map Loader/Unloader</title>
<script src="jquery/jquery-1.11.2.min.js"></script>
<script src="jquery/jquery-ui.js"></script>
<style>
	@font-face 
	{
		font-family: air;   
		src: url('fonts/Airstream-webfont.eot'); 
		src: url('fonts/Airstream-webfont.woff') format('woff'),
			 url('fonts/Airstream-webfont.ttf')  format('truetype'); 
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
	
	#horizon        
	{	
		text-align: center;
		position: absolute;
		top: 47%;
		left: 0px;
		width: 100%;	
		overflow: visible;
		visibility: visible;
		display: block;
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
	
</style>
<script>
$(function() 
{
	$("#mh, #mh2, .button").disableSelection();
});
</script>
</head>
<body>
<div id="body_content">
	<div id="horizon">
		<div id="mh"><?php echo $server_name; ?></div>
		<div id ="mh2"><?php echo $server_logo; ?></div>
		<br><br>
		<a href="adder.php" class="button">Add Map</a>&nbsp &nbsp &nbsp <a href="delete.php" class="button" >Delete Map</a>
	</div>	
</div>
</body>
</html>