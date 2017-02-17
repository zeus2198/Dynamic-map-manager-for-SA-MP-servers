/*================================================================================
 *                 Dynamic Web based Map loader/unloader
 *
 * author: BroZeus ( http://forum.sa-mp.com/member.php?u=224655 )
 * web demo: http://plwip.tk/mydemo/mapadder/
 * version: 1.0
 ================================================================================
 
 INTRODUCTION :
 
  	This is a map loader and unloader which works with the web scripts that comes with this script.
  	Maps can be Added or deleted from web using web scripts provided with this script.
  	Maps can be loaded or unloaded into server using this script.
  	This package of pawn script and web based scripts use MySQL database for its operation.
  
  Avaliable Commands and thier info :

	-> /mapload 	- Shows a list of 'UNLoaded' maps from database. After clicking on a map from
					  list its information is shown in a dialog provided with two buttons "Load"
					  and "Cancel". As the name suggest "Load" button loads the map into server.

	-> /ml      	- Short form of command '/mapload'
	
	-> /mapunload 	- This command is the opposite of '/mapload' command. This command shows a
					  list of 'Loaded' maps. After clicking on a map from list its information is
					  shown in a dialog with two buttons named "UnLoad" and "Cancel". Again as
					  the name suggests 'UnLoad' button unloads the map.

	-> /mul         - Short form of command '/mapunload'
	
	CREDITS :

		SA-MP Team      	: For developing and imrpoving SA-MP
		Zeex            	: For zcmd include
		BlueG           	: For MySQL plugin
		Bjoern Klinggaard   : For bPopup, a javascript based plugin used in webscripts
		Alan Williamson     : For Line numberer plugin, a javascript based plugin used in webscripts
		And I guess me too?
 */



#include <a_samp>
#include <zcmd>
#include <a_mysql>

//#include <streamer> //optional, works both with streamer and without streamer

//---------------------------------- Settings ---------------------------------------------

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

//--------------------------------------------------------------------------------------

#define SCM SendClientMessage

new db;
new cur[MAX_PLAYERS][25];//current selection of map in dialog

enum dialog_id
{
	M_LOAD,
	M_UNLOAD,
	M_LINFO,
	M_UINFO
}

enum minfo
{
	mName[25],
	mID,          //ID in MySQL table
	Float:mStream,      //stream distance of map
	mObj,         //total objects in map
	bool:mLoaded  //used to check whether map info is loaded in a paticular slot
}

new m_info[MAX_MAPS][minfo];//holds map information for LOADED maps
new obj[MAX_MAPS][MAX_OBJECTS];//holds objects' id

#if defined FILTERSCRIPT

public OnFilterScriptInit()
{
    mysql_log(LOG_ALL);
    db = mysql_connect(mysql_host, mysql_user, mysql_database, mysql_password);
    if(mysql_errno())
    {
        print("\n-----------------------------------------------------------------\n");
        print("Connection to MySQL database failed. GameMode will now be closed.");
        print("\n-----------------------------------------------------------------\n");
        SendRconCommand("exit");
    }
    else
    {
        print(">>> Connection to MySQL database has been made!");
        mysql_tquery(db, "CREATE TABLE IF NOT EXISTS `maps` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(25) NOT NULL, `stream` FLOAT(15,7) NOT NULL, PRIMARY KEY (`id`))", "QueryFinish", "");
    }
	return 1;
}

public OnFilterScriptExit()
{
	mysql_close(db);
	return 1;
}

#else

public OnGameModeInit()
{
 	mysql_log(LOG_ALL);
    db = mysql_connect(mysql_host, mysql_user, mysql_database, mysql_password);
    if(mysql_errno())
    {
        print("\n---------------------------------------------------------------\n");
        print("Connection to MySQL database failed. GameMode will now be closed. ");
        print("\n---------------------------------------------------------------\n");
        SendRconCommand("exit");
    }
    else
    {
        print(">>> Connection to MySQL database has been made!");
        mysql_tquery(db, "CREATE TABLE IF NOT EXISTS `maps` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(25) NOT NULL, `stream` FLOAT(15,7) NOT NULL, PRIMARY KEY (`id`))", "QueryFinish", "");
    }
	return 1;
}

public OnGameModeExit()
{
    mysql_close(db);
	return 1;
}

#endif

main()
{
    print("\n--------------------------------------------\n");
	print(" Web based Dynamic Map Loader/UnLoader loaded");
	print(" Author : BroZeus ");
	print("\n--------------------------------------------\n");
}

//==================== STOCKS ====================

stock GetEmptySlot()
{
	for(new i = 0; i < MAX_MAPS; i++)
	    if(!m_info[i][mLoaded])return i;
	return -1;
}

stock GetSlot(map_name[])
{
    for(new i = 0; i < MAX_MAPS; i++)
    {
    	if((!strcmp(map_name, m_info[i][mName])) && strlen(map_name) == strlen(m_info[i][mName]))return i;
    }
	return -1;
}

//==================== COMMANDS ====================

CMD:mul(playerid, params[])return cmd_mapunload(playerid, params);

CMD:mapunload(playerid, params[])
{
	#pragma unused params
	if(AdminCheck(playerid))return SCM(playerid, -1, "{ff0000}[SERVER]: {ffffff}You are not authorized to use this command.");
	SCM(playerid, -1, "{f0f000}[SERVER]: {ffffff}Loading maps' information, please wait..");
	new dstr[600], g = -1;
	for(new i = 0;i < MAX_MAPS; i++)
	{
	    if(!m_info[i][mLoaded])continue;
	    if(g == 1)strcat(dstr, "\n");
	    ((i+1) % 2) ? strcat(dstr, "{00f0f0}") : strcat(dstr, "{f0f000}");//alternating color to rows in dialog
		strcat(dstr, m_info[i][mName]);
	    g = 1;
  	}
  	if(g == -1)return SCM(playerid, -1, "{ff0000}[SERVER]:{ffffff} No maps loaded! You can load map(s) via /mapload or /ml");
  	ShowPlayerDialog(playerid, M_UNLOAD, DIALOG_STYLE_LIST, "{ff0000}U{ffffff}nload Map", dstr, "Select", "Cancel");
	return 1;
}

CMD:ml(playerid, params[])return cmd_mapload(playerid, params);

CMD:mapload(playerid, params[])
{
	#pragma unused params
	if(AdminCheck(playerid))return SCM(playerid, -1, "{ff0000}[SERVER]: {ffffff}You are not authorized to use this command.");
	SCM(playerid, -1, "{f0f000}[SERVER]: {ffffff}Loading maps' information, please wait..");
	mysql_tquery(db, "SELECT `name` FROM `maps`", "mListLoad", "d", playerid);
	return 1;
}

//==================== Forwards & Publics ====================

forward QueryFinish();
public QueryFinish()return 1;

forward mListLoad(playerid);
public mListLoad(playerid)
{
	if(!IsPlayerConnected(playerid))return 1;
	new rows = cache_get_row_count(db);
   	if(!rows) return SCM(playerid, -1, "{ff0000}[SERVER]: {ffffff}No maps in database!");
   	new temp[30], dstr[600], g = -1;
   	for(new i = 0; i < rows; i++)
   	{
   	    cache_get_row(i, 0, temp, db);
   	    if(GetSlot(temp) != -1)continue;
		((i+1) % 2) ? strcat(dstr, "{00f0f0}") : strcat(dstr, "{f0f000}");//alternating color to rows in dialog
		strcat(dstr, temp);
		if(i < rows-1)strcat(dstr, "\n");
		g = 1;
	}
	if(g == -1)return SCM(playerid, -1, "{ff0000}[SERVER]: {ffffff}All maps are loaded into server. By this command only maps which are not loaded are viewed.");
	ShowPlayerDialog(playerid, M_LOAD, DIALOG_STYLE_LIST, "{00cc00}L{ffffff}oad Map", dstr, "Select", "Cancel");
	return 1;
}

forward ShowMap(playerid);
public ShowMap(playerid)
{
	if(!IsPlayerConnected(playerid))return 1;
    new rows = cache_get_row_count(db);
   	if(!rows) return SCM(playerid, -1, "{ff0000}[SERVER]: {ffffff}Looks like someone deleted that map while you were viewing list.");
	new temp[30], dstr[200];
	format(dstr, sizeof(dstr), "{ffffff}Map ID:\t\t{00f0f0}");
	cache_get_field_content(0, "id", temp), strcat(dstr, temp);
	strcat(dstr, "\n{ffffff}Map Name:\t\t{00f0f0}");
	cache_get_field_content(0, "name", temp), strcat(dstr, temp);
	strcat(dstr, "\n{ffffff}Draw Distance:\t\t{00f0f0}");
	cache_get_field_content(0, "stream", temp), strcat(dstr, temp);
	ShowPlayerDialog(playerid, M_LINFO, DIALOG_STYLE_MSGBOX, "Map Information", dstr, "Load", "Cancel");
	return 1;
}

forward LoadmInfo(playerid);
public LoadmInfo(playerid)
{
    new rows = cache_get_row_count(db);
   	if(!rows) return SCM(playerid, -1, "{ff0000}[SERVER]: {ffffff}Looks like someone deleted that map while you were viewing list.");
	new slot = GetEmptySlot();
	if(slot == -1)return SCM(playerid, -1, "{ff0000}[SERVER]: {ffffff}Max map loading limit has been reached.");
 	new temp[30], query[150];
 	m_info[slot][mLoaded] = true;
    cache_get_field_content(0, "id", temp),m_info[slot][mID] = strval(temp);
    cache_get_field_content(0, "name", temp),format(m_info[slot][mName], 25, temp);
    cache_get_field_content(0, "stream", temp),m_info[slot][mStream] = floatstr(temp);
    format(query, sizeof(query), "SELECT * FROM `%s`", m_info[slot][mName]);
    mysql_tquery(db, query, "ObjLoad", "d", slot);
    return 1;
}

forward ObjLoad(slot);
public ObjLoad(slot)
{
	m_info[slot][mObj] = cache_get_row_count(db);
	new temp[30], mModel, Float:mx, Float:my, Float:mz, Float:mrx, Float:mry, Float:mrz;
	for(new i = 0; i < m_info[slot][mObj]; i++)
	{
	    cache_get_field_content(i, "model", temp),mModel = strval(temp);
	    cache_get_field_content(i, "x", temp),mx = floatstr(temp);
	    cache_get_field_content(i, "y", temp),my = floatstr(temp);
	    cache_get_field_content(i, "z", temp),mz = floatstr(temp);
	    cache_get_field_content(i, "rx", temp),mrx = floatstr(temp);
	    cache_get_field_content(i, "ry", temp),mry = floatstr(temp);
	    cache_get_field_content(i, "rz", temp),mrz = floatstr(temp);
	    #if defined Streamer_IncludeFileVersion
	        obj[slot][i] = CreateDynamicObject(mModel, mx, my, mz, mrx, mry, mrz, -1, -1, -1, 200.0, m_info[slot][mStream]);
		#else
		    obj[slot][i] = CreateObject(mModel, mx, my, mz, mrx, mry, mrz, m_info[slot][mStream]);
		#endif
  	}
	new msg[100];
	format(msg, sizeof(msg), "{00cc00}[SERVER]: {ffffff} Map named {00f0f0}%s {ffffff}has been loaded into server", m_info[slot][mName]);
	SendClientMessageToAll(-1, msg);
  	return 1;
}


public OnDialogResponse(playerid, dialogid, response, listitem, inputtext[])
{
	switch(dialogid)
	{
	    case M_LOAD:
		{
			if(!response)return 1;
			format(cur[playerid], 25, inputtext);
			new query[80];
			format(query, sizeof(query), "SELECT * FROM `maps` WHERE `name` = '%s' LIMIT 1", inputtext);
			mysql_tquery(db, query, "ShowMap", "d", playerid);
			return 1;
		}
		case M_LINFO:
		{
		    if(!response)return 1;
			new query[200];
			format(query, sizeof(query), "{f0f000}[SERVER]: {ffffff}Loading map..");
			SCM(playerid, -1, query);
			if(GetSlot(cur[playerid]) != -1)return SCM(playerid, -1, "{f0f000}[SERVER]: {ffffff}Looks like someone already loaded that map when you were viewing list");
			format(query, sizeof(query), "SELECT * FROM `maps` WHERE `name` = '%s' LIMIT 1", cur[playerid]);
			mysql_tquery(db, query, "LoadmInfo", "d", playerid);
			return 1;
		}
		case M_UNLOAD:
		{
		    if(!response)return 1;
		    format(cur[playerid], 25, inputtext);
			new dstr[200], slot = GetSlot(inputtext);
			format(dstr, sizeof(dstr), "{ffffff}Map Name:\t\t{00f0f0}%s\n{ffffff}Map ID:\t\t{00f0f0}%i\n{ffffff}Total Objects:\t\t{00f0f0}%i\n{ffffff}Draw Distance:\t\t{00f0f0}%f", m_info[slot][mName], m_info[slot][mID], m_info[slot][mObj], m_info[slot][mStream]);
		    ShowPlayerDialog(playerid, M_UINFO, DIALOG_STYLE_MSGBOX, "Map Information", dstr, "UnLoad", "Cancel");
			return 1;
   		}
   		case M_UINFO:
   		{
   			if(!response)return 1;
   			SCM(playerid, -1, "{f0f000}[SERVER]: {ffffff}Map Unloading in progress..");
   			new slot = GetSlot(cur[playerid]);
			m_info[slot][mLoaded] = false;
			new temp[30];
			format(temp, sizeof(temp), m_info[slot][mName]);
			format(m_info[slot][mName], 25, "\0");
			for(new i = 0; i < m_info[slot][mObj]; i++)
			{
			     #if defined Streamer_IncludeFileVersion
	        		 DestroyDynamicObject(obj[slot][i]);
				#else
		    		DestroyObject(obj[slot][i]);
				#endif
				obj[slot][i] = -1;
			}
			m_info[slot][mStream] = -1.0;
			m_info[slot][mObj] = -1;
   			m_info[slot][mID] = -1;
   			new msg[100];
   			format(msg, sizeof(msg), "{FFAF02}[SERVER]: {ffffff}Map named {00f0f0}%s {ffffff}has been unloaded", temp);
   			SendClientMessageToAll(-1, msg);
   			return 1;
		}
	}
	return 0;
}
