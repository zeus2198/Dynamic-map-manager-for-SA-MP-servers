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
<title>Map Adding Facility</title>
<script src="jquery/jquery-1.11.2.min.js"></script>
<script src="jquery/jquery-ui.js"></script>
<script src="jquery/bpop.js"></script>
<script src="jquery/jquery-linedtextarea.js"></script>
<link href="css/jquery-linedtextarea.css" type="text/css" rel="stylesheet" />
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
		font-family: fsource;   
		src: url('fonts/source.woff') format('woff'); 
	}
	
	@font-face 
	{
		font-family: Gothic;   
		src: url('fonts/Gothic.ttf'); 
	}
	
	@font-face 
	{
		font-family: Broken;   
		src: url('fonts/Broken.ttf'); 
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
		display:inline-block;
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
	
	#txtarea
	{
		height:300px;
		width:850px;
		background: none;
		color:white;
		line-height:28px;
		letter-spacing:2px;
		font-family:fsource;
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
	
	hr 
	{
		width:900px;
		color: rgba(255,255,255,0.6);
	}
    
	
	::-webkit-input-placeholder 
	{ 
		font-size:20px;
		font-family:Broken;
		color:    rgba(255,255,255,0.4);		
	}
	
	:-moz-placeholder 
	{ 
		color:    rgba(255,255,255,0.4);
		opacity:  1;
		font-size:20px;
		font-family:Broken;
	}
	
	::-moz-placeholder 
	{
		color:    rgba(255,255,255,0.4);
		opacity:  1;
		font-size:20px;
		font-family:Broken;
	}
	
	:-ms-input-placeholder 
	{ 
		color:    rgba(255,255,255,0.4);
		font-size:20px;
		font-family:Broken;
	}
	
</style>
<script>
var popid = -1;

$.fn.selectRange = function(start, end) {
    if(!end) end = start; 
    return this.each(function() {
        if (this.setSelectionRange) {
            this.focus();
            this.setSelectionRange(start, end);
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
} 
function isFloat(n) {
      return (!isNaN(parseFloat(n))) && n != "" && !isNaN(n);
}

function isInteger(n) {
  return n % 1 === 0 && n != "" && !isNaN(n);
}

function pop_close()//a function to close our popup
{
	popid.close();
	$("#pop").html("<img src='images/load.gif' style='height:100px;width:100px;display:inline-block'></img><br><span style=\"color:rgba(255,255,255,0.7);font-size:30px;font-family:Gothic;\">Checking code for any error..</span>");
}

function InsertMap(mname)//the function to insert map, 'mname' = name of map
{
	//Now here we seperate parameters from CreateObject and store them into an array
	//then we pass that array to php script with ajax
	//the php script inserts it into MySQL database
	var info = new Array();//the array to hold object(s) info
	var obj = $("#txtarea").val().split("\n");
	for(var i = 0; i < obj.length; i++)
	{
		obj[i] = obj[i].trim();
		if(obj[i].length < 10)continue;
		var params = obj[i].split("(");			
		params = params[1].split(")");
		params = params[0].split(",");
		info.push({'model': params[0].trim(), 'x': params[1].trim(), 'y': params[2].trim(), 'z': params[3].trim(), 'rx': params[4].trim(), 'ry': params[5].trim(), 'rz': params[6].trim()});
	}
	//now passing array to php script with help of ajax
	$.ajax({
		    method: "POST",
			url: "map_insert.php",
           data: { objs: JSON.stringify(info), nmap: mname, ddraw: $("#draw").val() }
        }).done(function(data) {
			$("#pop").slideUp(function(){
				$("#pop").html("<div id='pop_box'>"+data+"</div>");
				$("#pop").fadeIn();
			});
		});	
}

$(function() 
{
	$("#draw").val(100);
	$("#mh, #mh2, #hh, .button, hr").disableSelection();
	
	$("#txtarea").linedtextarea();
	
	$('#txtarea').bind('input propertychange', function() 
	{
		//this the part which replaces a ',' with ', ' took almost 1 hour to make this part :-/
		var c = $('#txtarea').prop("selectionStart");
		var l1 = this.value.length;
		var a = this.value.replace(/,([0-9]|-|-)/g, ', $1');
		$('#txtarea').val(a);
		if(a.length != l1)$('#txtarea').selectRange(c+1);
	});

	$(".button").click(function()
	{
		if($("#draw").val() > 10000)
		{
			$("#err").append("<span id='merr' style='display:none;color:#ff0000;font-family:aller;font-size:20px'>Max draw distance <b>10000</b></span>");
			$("#merr").slideDown();
			return;
		}
		popid = $('#pop').bPopup({
		fadeSpeed: 'slow', 
        followSpeed: 'slow',
		modalClose: false,
        opacity: 0.6,
		positionStyle: 'fixed'
		});
		
		// ERROR Detection Part -->
		
		var error_array = new Array();
		var obj = $("#txtarea").val().split("\n");
		var s = 0;
		for(var i = 0; i < obj.length; i++)
		{
			obj[i] = obj[i].trim();
			if(obj[i].length < 10)continue;
			var params = obj[i].split("(");
			if(params.length == 1)
			{
				error_array.push({'line': (i+1), 'error': 'Syntax Error.'});
				continue;
			}
			params = params[1].split(")");
			params = params[0].split(",");
			if(params.length < 7)
			{
				error_array.push({'line': (i+1), 'error': 'Seven or more parameters expected.'});
				continue;
			}			
			if(!isInteger(params[0]))
			{
				error_array.push({'line': (i+1), 'error': '<b>First</b> paramerter is expected to be an <b>integer</b>'});
				continue;
			}
			for(var ii = 1; ii <= 6; ii++)
			{				
				if(!isFloat(params[ii]) && !isInteger(params[ii]))
				error_array.push({'line': (i+1), 'error': 'Parameter <b>'+(ii+1)+'</b> is expected to be float or integer'});
			}
			s++;
		}
		if(s < 3)//check if atleast 3 valid Create Object lines are there or not
		{			
			var a = "<div id='pop_box'>"+
				"<span style='color:red;font-size:30px;font-family:Gothic'>U wot m8</span><br><br>"+
				"<div style='line-height:2;color:black;font-family:Arial;max-height:300px;display:inline-block;text-align:left;'>Enter atleast more than 3 lines of objects</div>"+
				"<br><br><div id='clbutton' onclick='pop_close()'>Close</div><br><br></div>";
			$("#pop").slideUp(function(){
				$("#pop").html(a);
				$("#pop").fadeIn();
			});		
			return;
		}
		if(error_array.length > 0)//check if any errors found or not
		{
			//Yes, there are errors </3
			//showing errors -->
			var a = "<div id='pop_box'>"+
					"<span style='color:red;font-size:30px;font-family:Gothic'>Error(s) Found!</span><br><br>"+
					"<div style='width:500px;line-height:2;color:black;font-family:Arial;max-height:300px;overflow:hidden;overflow-y:scroll;display:inline-block;text-align:left;'>";
			for(var i = 0; i < error_array.length; i++)
			{
				a += "<b>Line "+error_array[i]['line']+":</b> "+error_array[i]['error']+"<br>";				
			}
			a += "</div><br><br><div id='clbutton' onclick='pop_close()'>Close</div><br><br></div>";
			$("#pop").slideUp(function(){
				$("#pop").html(a);
				$("#pop").fadeIn();
			});					
		}
		else
		{	//No errors in code, YAY!
			var a = "<div id='pop_box'>"+
					"<span style='color:#00cc00;font-size:40px;font-family:Gothic'>No Errors!</span><br><br>"+
					"<span style='font-family:Gothic;font-size:15px;'>Please enter the name of map below.<br>Press <b>Enter</b> key after you have entered the name.</span><br><br>"+
					"<input id='map_name' style='font-family:Gothic;width:300px;height:30px;' maxlength='25' type='text' placeholder='Map Name' /><br><br>"+
					"</div>";
			$("#pop").slideUp(function(){
				$("#pop").html(a);
				$("#pop").fadeIn();
			});
		}		
	});//end of button click callback
	
	$(document).keydown(function(e) 
	{
		if(e.which == 13 && $("#map_name").is(":focus"))//check if enter key is pressed and if user is typing map name
		{
			var m_name = $("#map_name").val().trim();
			if(m_name.length < 5)//if map name is less than 5 characters 
			{
				var a = "<div id='wrong' style='color:#ff0000;font-size:15px;font-family:Gothic;display:none;'>Map name should have atleast 5 characters</div>";
				$("#pop_box").append(a);
				$("#wrong").slideDown('slow');
				return;
			}			
			//ok, now lets first show a "Inserting map.. Please wait" thingy on screen
			$("#pop").fadeOut(function(){
				$("#pop").html("<img src='images/load.gif' style='height:100px;width:100px;display:inline-block'></img><br><span style=\"color:rgba(255,255,255,0.7);font-size:30px;font-family:Gothic;\">Inserting Map.. Please wait</span>");
				$("#pop").fadeIn();
				InsertMap(m_name);//call the function to insert map
			});
		}
	});//end of keydown callback	

});
</script>
</head>
<body>
<!-- Hidden content -->
<div id="pop">
	<img src='images/load.gif' style='height:100px;width:100px;display:inline-block'></img>
	<br>
	<span style="color:rgba(255,255,255,0.7);font-size:30px;font-family:Gothic;">
		Checking code for any error..
	</span>
</div>
<!-- Hidden content END -->
<div id="body_content">
	<div id="mh"><?php echo $server_name; ?></div>
	<div id ="mh2"><?php echo $server_logo; ?></div>
	<br><br>
	<hr>
	<div id="hh">Map Adding Facility</div>
	<br><br>
	<textarea id="txtarea" spellcheck="false" placeholder="Paste Objects Here"></textarea>
	<br><br>
	<div id="err">
		<span style="color:white;font-family:aller;font-size:17px">Draw Distance: </span>
		<input type="number" max="10000" id="draw" onkeypress="return isNumberKey(event)" /><br>
	</div>
	<br>
	<div class="button">Proceed</div>
</div>
</body>
</html>