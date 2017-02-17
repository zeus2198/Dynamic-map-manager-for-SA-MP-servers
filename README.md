## What exactly is this?
This is a dynamic map adder that helps you to add or delete maps from web in server. With this you don't need to upload maps as a FilterScript from FTP, you can just simply insert using a webpage. You can delete maps, see map info and add maps from web, and those maps can be loaded in server via a command.

** Note that the pawn script is based on MySQL plugin by BlueG version r39-2 yet to be updated to latest version **

## Pics of web page in action
<img src="https://i.gyazo.com/ff3cd3387f753e5579007b88142e3ce2.jpg" width="200"/> <img src="https://i.gyazo.com/2b86fc8bee45628ad006a991c6927202.png" width="200"/> <img src="https://i.gyazo.com/5a328f504085907d9baf92d07cf2b16a.png" width="200"/> <img src="https://i.gyazo.com/616c154595807a6cd4738876ec150adb.png" width="200"/> <img src="https://i.gyazo.com/39a7c6079897fb046d3c0bc6f6fe10d2.png" width="200"/>

## Pics of in-game script in action 
<img src="http://i.imgur.com/suqjs9x.png" width="200"/> <img src="http://i.imgur.com/CmtSQrM.png" width="200"/> <img src="http://i.imgur.com/8d2sRDp.png" width="200"/> <img src="http://i.imgur.com/X04TvBr.png" width="200"/> <img src="http://i.imgur.com/KGTNUhl.png" width="200"/> <img src="http://i.imgur.com/iudWfAU.png" width="200"/>

## Getting started
To get started you just need to edit the settings as told below

** For web part: **

Open config.php and edit the code in it.
config.php looks something like this :
```php
<?php 
$mysql_host = "127.0.0.1"; 
$mysql_user = "root"; 
$mysql_password = ""; 
$mysql_database = "DB_Name"; 

$server_name = "Your Server Name"; 
$server_logo = "Demo Server Logo Here"; 
?>
```

** For in-game part: **

Edit the #define's at top of pawn script
It looks something like this :
```pawn
#define mysql_host       "127.0.0.1"
#define mysql_user       "root"
#define mysql_password   ""
#define mysql_database   "DB_NAME"

#define AdminCheck(%0) !IsPlayerAdmin(%0)// Method to check if player is admin or not, see below

/*
	Do NOT change the part '#define AdminCheck(%0)', you need to change the part '!IsPlayerAdmin(%0)'
	Example :

		Lets say I have a variable named pInfo[playerid][pAdmin] to check for player admin and I
		want that only admins with admin level 5 or more can use map loading/unloading command.
		So I will do something like this :
		
		    #define AdminCheck(%0) pInfo[%0][pAdmin] < 5
		    
		PS. '%0' is the playerid, use '%0' not playerid in #define
*/


#define MAX_MAPS 100 //max maps that can be loaded at a time in server
```
## In-game Commands

Command | Description
--- | ---
/mapload | Shows a list of 'UnLoaded' maps from database. After clicking on a map from list its information is shown in a dialog provided with two buttons "Load" and "Cancel". As the name suggest "Load" button loads the map into server.
/ml | Short form of command '/mapload'
/mapunload | This command is the opposite of '/mapload' command. This command shows a list of 'Loaded' maps. After clicking on a map from list its information is shown in a dialog with two buttons named "UnLoad" and "Cancel". Again as the name suggests 'UnLoad' button unloads the map.
/mul | Short form of command '/mapunload'
