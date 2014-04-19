// @version slupdater v64
// @package server
// @copyright Copyright wene / ssm2017 Binder (C) 2007-2009. All rights reserved.
// @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
// slvendor is free software and parts of it may contain or be derived from the
// GNU General Public License or other free or open source software licenses.

// **********************
//      USER PREFS
// **********************
// url ex: string url = "http://test.com";
string url = "";
// password
string password = "";
// server refresh period
float refresh = 3600;
// use this url if url is given by an external file
string url_file = "";
// enable inventory change detection
integer inventory_changed = FALSE;
// you can change the items suffixes if needed
string TEXTURE_SUFFIX = "PIC";
string NOTECARD_SUFFIX = ".info";
// **********************
//  JOOMLA VERSION URL
// **********************
// == JOOMLA 1.5.X ==
string url2 = "/index.php?option=com_slvendor&controller=inworld";
// **********************
//      STRINGS
// **********************
// symbols
string _SYMBOL_RIGHT = "✔";
string _SYMBOL_WRONG = "✖";
string _SYMBOL_WARNING = "⚠";
string _SYMBOL_RESTART = "⟲";
string _SYMBOL_HOR_BAR_1 = "⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌";
string _SYMBOL_HOR_BAR_2 = "⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊";
string _SYMBOL_ARROW = "⤷";
// url
string _MISSING_VAR_NAMED = "Missing var named";
string _IN_SCRIPT_NAMED = "in script named";
string _REQUESTING_URL = "Requesting url";
string _URL_FOUND = "Url found";
string _URL_NOT_FOUND = "Url not found";
// deletion
string _DELETING_ALL_ITEMS = "Deleting all items that are not objects, textures or notecards...";
string _DELETED_ITEM_NAMED = "Deleted item named";
string _DELETING = "Deleting";
string _ITEMS_DELETED = "Items Deleted.";
string _DELETING_ALL_CONTENT = "Deleting all content...";
// check
string _GETTING_LIST_OF_OBJECTS = "Getting list of objects to update the server...";
string _LIST_IS_EMPTY = "The list is empty.";
string _CHECKING_TEXTURES_AND_NOTECARDS = "Checking textures and notecards...";
string _THE_OBJECT = "The object";
string _NEEDS_A_TEXTURE_NAMED = "needs a texture named";
string _NEEDS_A_NOTECARD_NAMED = "needs a notecard named";
// update
string _DO_YOU_REALLY_WANT_TO_UPDATE = "Do you really want to update the server with these objects ?";
string _DO_YOU_REALLY_WANT_TO_CLEAR_ALL = "Do you really want to clear all objects ?";
string _UPDATE = "Update";
string _UPDATING_SERVER = "Updating Server...";
string _UPDATE_PRODUCTS = "Updating Products...";
string _DELETING_ON_WEBSITE = "Deleting products in the web server...";
// wait mode
string _SERVER_PAUSED = "Server paused.";
string _WAITING_FOR_TOUCH = "Waiting for a touch.";
string _CHOOSE_OPTION = "Choose an option";
string _UPDATE_SERVER = "Update Server";
string _CLEAR_ALL = "Clear All";
string _WAIT = "Wait";
string _CLEAR = "Clear";
string _RESET = "Reset";
string _SCRIPT_RESETING = "Script reseting...";
string _INVENTORY_HAS_CHANGED = "The server content has changed. Update the server again please.";
string _ENTERING_WAIT_MODE = "Entering wait mode.";
// http errors
string _REQUEST_TIMED_OUT = "Request timed out";
string _FORBIDDEN_ACCESS = "Forbidden access";
string _PAGE_NOT_FOUND = "Page not found";
string _INTERNET_EXPLODED = "the internet exploded!!";
string _SERVER_ERROR = "Server error";
// ============================================================
//          NOTHING SHOULD BE MODIFIED UNDER THIS LINE
// ============================================================
// ***********************
//          VARS
// ***********************
// owner
key owner;
string owner_name;
string HTTP_SEPARATOR = ";";
string PRODUCT_SEPARATOR = "|";
// menu
integer menu_listener;
integer menu_channel;
// constants
integer REQUEST_UPDATE = 72001;
// **********************
//      BUILD POS
// **********************
string buildPos() {
    vector pos = llGetPos();
    return (string)llFloor(pos.x)+"/"+(string)llFloor(pos.y)+"/"+(string)llFloor(pos.z);
}
string getParcelName() {
    list parcelName = llGetParcelDetails(llGetPos(),[PARCEL_DETAILS_NAME]);
    return llList2String(parcelName,0);
}
// **********************
//      CHECK CONTENT
// **********************
list objects_list;
list textures_list;
checkContent() {
    llOwnerSay(_SYMBOL_HOR_BAR_2);
    llOwnerSay(_DELETING_ALL_ITEMS);
    deleteContent(FALSE);
    llOwnerSay(_SYMBOL_HOR_BAR_2);
    llOwnerSay(_GETTING_LIST_OF_OBJECTS);
    objects_list = [];
    objects_list = getObjectsList(INVENTORY_OBJECT);
    if (llGetListLength(objects_list) == 0) {
        llOwnerSay(_SYMBOL_ARROW+ " "+ _LIST_IS_EMPTY);
        llOwnerSay(_SYMBOL_HOR_BAR_2);
        return;
    }
    else {
        if (checkItems()) {
            menu_listener = llListen(menu_channel,"", owner,"");
            llDialog(owner, _DO_YOU_REALLY_WANT_TO_UPDATE, [_UPDATE], menu_channel);
        }
        else {
            return;
        }
    }
}
integer checkItems() {
    llOwnerSay(_SYMBOL_HOR_BAR_2);
    llOwnerSay(_CHECKING_TEXTURES_AND_NOTECARDS);
    integer objectsCount = llGetListLength(objects_list);
    string objectName;
    // textures vars
    list textures_list_names;
    string textureName;
    key textureKey;
    textures_list = [];
    // notecards vars
    list notecards_list_names;
    string notecardName;
    key notecardKey;
    integer i;
    while (i < objectsCount) {
        objectName = llList2String(objects_list,i);
        // textures
        textureName = objectName+TEXTURE_SUFFIX;
        textureKey = llGetInventoryKey(textureName);
        if (textureKey == NULL_KEY) {
            llOwnerSay(_SYMBOL_WARNING+ " "+ _THE_OBJECT+" : "+ objectName+ " "+ _NEEDS_A_TEXTURE_NAMED+ " \""+ textureName+ "\"");
            return FALSE;
        }
        else {
            textures_list = (textures_list=[]) + textures_list + [(string)textureKey];
            textures_list_names = (textures_list_names=[]) + textures_list_names + [textureName];
        }
        // notecards
        notecardName = objectName+NOTECARD_SUFFIX;
        notecardKey = llGetInventoryKey(notecardName);
        if (notecardKey == NULL_KEY) {
            llOwnerSay(_SYMBOL_WARNING+ " "+ _THE_OBJECT+" : "+ objectName+ " "+ _NEEDS_A_NOTECARD_NAMED+ " \""+ notecardName+ "\"");
            return FALSE;
        }
        else {
            notecards_list_names = (notecards_list_names=[]) + notecards_list_names + [notecardName];
        }
        ++i;
    }
    // check for orphan items
    checkOrphans(textures_list_names, INVENTORY_TEXTURE);
    checkOrphans(notecards_list_names, INVENTORY_NOTECARD);
    return TRUE;
}
// check orphans
checkOrphans(list items, integer type) {
    list existingItems = getObjectsList(type);
    integer existingItemsCount = llGetListLength(existingItems);
    string itemName = "";
    integer i = 0;
    while (i < existingItemsCount) {
        itemName = llList2String(existingItems,i);
        if (llListFindList(items, [itemName]) == -1) {
            llRemoveInventory(itemName);
            llOwnerSay(_SYMBOL_WRONG+ " "+ _DELETED_ITEM_NAMED+ " : "+ itemName);
        }
        ++i;
    }
}
list getObjectsList(integer type) {
    list       result = [];
    integer    n = llGetInventoryNumber(type);
    integer    i = 0;
    string name;
    while(i < n) {
        name = llGetInventoryName(type, i);
        if (llGetSubString(name, 0, 6) != "update-") {
            result = (result=[]) + result + [name];
            llOwnerSay(_SYMBOL_RIGHT+ " "+ name);
        }
        ++i;
    }
    return result;
}
integer count = 0;
deleteContent(integer all) {
    integer n = llGetInventoryNumber(INVENTORY_ALL);
    string  name;
    integer type;
    integer delete = 0;
    integer i = 0;
    while(i < n) {
        delete = 0;
        name = llGetInventoryName(INVENTORY_ALL, i);
        if (name == "") {
            deleteContent(all);
            return;
        }
        type = llGetInventoryType(name);
        if (type != INVENTORY_OBJECT && type != INVENTORY_TEXTURE && type != INVENTORY_NOTECARD) {
            if (type == INVENTORY_SCRIPT && name == llGetScriptName()) {
                delete = 0;
            }
            else {
                delete = 1;
            }
        }
        if (type == INVENTORY_OBJECT && all == TRUE) {
            delete = 1;
        }
        if (type == INVENTORY_TEXTURE && all == TRUE) {
            delete = 1;
        }
        if (type == INVENTORY_NOTECARD && all == TRUE) {
            delete = 1;
        }
        if (delete) {
            llOwnerSay(_SYMBOL_WRONG+ " "+ _DELETING+ " : "+ name);
            llRemoveInventory(name);
            ++count;
            delete = 0;
        }
        ++i;
    }
    if (all == TRUE && i == n) {
        clearProducts();
    }
    llOwnerSay(_SYMBOL_ARROW+ " "+ (string)count+" "+_ITEMS_DELETED);
}
// check if object exists
string sendItem(string item_name, string msg_userKey) {
    if (llGetInventoryType(item_name) != INVENTORY_NONE) {
        llGiveInventory(msg_userKey, item_name);
        return "sending";
    }
    else {
        return "missing in server";
    }
}
// **********************
//          HTTP
// **********************
// check vars
integer checkVars() {
    string script_name = llGetScriptName();
    integer checked = TRUE;
    if (url == "") {
        llOwnerSay(_SYMBOL_WARNING+ " "+ _MISSING_VAR_NAMED+ " \"url\" "+ _IN_SCRIPT_NAMED+ " \""+ script_name+ "\"");
        checked = FALSE;
    }
    if (password == "") {
        llOwnerSay(_SYMBOL_WARNING+ " "+ _MISSING_VAR_NAMED+ " \"password\" "+ _IN_SCRIPT_NAMED+ " \""+ script_name+ "\"");
        checked = FALSE;
    }
    return checked;
}
// get url
key requestUrlId;
requestUrl() {
    llOwnerSay(_REQUESTING_URL);
    requestUrlId = llHTTPRequest(url_file, [HTTP_METHOD, "GET", HTTP_MIMETYPE, "application/x-www-form-urlencoded"], "");
}
// update server
key server_channel;
key updateServerId;
updateServer() {
    if (!checkVars()) {
        return;
    }
    llOwnerSay(_SYMBOL_HOR_BAR_2);
    llOwnerSay(_UPDATING_SERVER);
    // building password
    integer keypass = (integer)llFrand(9999)+1;
    string md5pass = llMD5String(password, keypass);
    // sending values
    updateServerId = llHTTPRequest(url+url2, [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                    "task=updateServer"
                    +"&name="+llGetObjectName()
                    +"&data_channel="+(string)server_channel
                    +"&password="+md5pass
                    +"&key="+(string)keypass);
}
// update products
key updateProductsId;
updateProducts() {
    if (!checkVars()) {
        return;
    }
    llOwnerSay(_SYMBOL_HOR_BAR_2);
    llOwnerSay(_UPDATE_PRODUCTS);
    // building password
    integer keypass = (integer)llFrand(9999)+1;
    string md5pass = llMD5String(password, keypass);
    // sending values
    updateProductsId = llHTTPRequest(url+url2, [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                        "task=updateProducts"
                        +"&password="+md5pass
                        +"&key="+(string)keypass
                        +"&objects_names="+llDumpList2String(objects_list, PRODUCT_SEPARATOR)
                        +"&textures="+llDumpList2String(textures_list, PRODUCT_SEPARATOR));
}
// clear products
key clearProductsId;
clearProducts() {
    if (!checkVars()) {
        return;
    }
    llOwnerSay(_SYMBOL_HOR_BAR_2);
    llOwnerSay(_DELETING_ON_WEBSITE);
    // building password
    integer keypass = (integer)llFrand(9999)+1;
    string md5pass = llMD5String(password, keypass);
    // sending values
    updateServerId = llHTTPRequest(url+url2, [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                    "task=clearProducts"
                    +"&password="+md5pass
                    +"&key="+(string)keypass
                    +"&server_key="+(string)llGetKey());
}
// get server answer
getServerAnswer(integer status, string body) {
    if (status == 499) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _REQUEST_TIMED_OUT);
    }
    else if (status == 403) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _FORBIDDEN_ACCESS);
    }
    else if (status == 404) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _PAGE_NOT_FOUND);
    }
    else if (status == 500) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _SERVER_ERROR);
    }
    else if (status != 403 && status != 404 && status != 500) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _INTERNET_EXPLODED);
        llOwnerSay(body);
    }
}
// ************************
//          MAIN
// ************************
default {
    on_rez(integer nbr) {
        llResetScript();
    }
    state_entry() {
        menu_channel = llFloor(llFrand(100000.0)) + 1000;
        owner = llGetOwner();
        llOwnerSay(_SYMBOL_HOR_BAR_1);
        llOwnerSay(_SERVER_PAUSED);
        llOwnerSay(_SYMBOL_ARROW+ " "+ _WAITING_FOR_TOUCH);
        llOwnerSay(_SYMBOL_HOR_BAR_1);
        llSetColor(<1.,0.,0.>,ALL_SIDES);
        if (url_file != "") {
            requestUrl();
        }
    }
    touch_start(integer total_number) {
        if (llDetectedKey(0) == owner) {
            menu_listener = llListen(menu_channel,"", owner,"");
            llDialog(owner, _CHOOSE_OPTION+" : ", [_UPDATE_SERVER, _CLEAR_ALL, _WAIT, _RESET], menu_channel);
        }
    }
    listen(integer channel, string name, key id, string message) {
        if (channel == menu_channel) {
            if (message == _UPDATE_SERVER) {
                checkContent();
            }
            else if (message == _UPDATE) {
                llOpenRemoteDataChannel();
            }
            else if (message == _CLEAR_ALL) {
                llDialog(owner, _DO_YOU_REALLY_WANT_TO_CLEAR_ALL, [_CLEAR], menu_channel);
            }
            else if (message == _CLEAR) {
                llOwnerSay(_SYMBOL_HOR_BAR_2);
                llOwnerSay(_DELETING_ALL_CONTENT);
                deleteContent(TRUE);
                llOwnerSay(_SYMBOL_RESTART+ " "+ _SCRIPT_RESETING);
                llResetScript();
            }
            else if (message == _WAIT) {
                state waiting;
            }
            else if (message == _RESET) {
                llOwnerSay(_SYMBOL_RESTART+ " "+ _SCRIPT_RESETING);
                llResetScript();
            }
        }
    }
    http_response(key request_id, integer status, list metadata, string body) {
        body = llStringTrim(body , STRING_TRIM);
        if (status != 200) {
            getServerAnswer(status, body);
        }
        else {
            if (request_id == requestUrlId) {
                if (body !="") {
                    url = body;
                    llOwnerSay(_URL_FOUND);
                }
                else {
                    llOwnerSay(_SYMBOL_WARNING+ " "+ _URL_NOT_FOUND);
                }
            }
            else {
                list data = llParseString2List(body, [HTTP_SEPARATOR],[]);
                string command = llList2String(data,0);
                if (command == "server updated") {
                    llOwnerSay(_SYMBOL_ARROW+ " "+ llList2String(data,1));
                    updateProducts();
                }
                else if (command == "product cleared") {
                    llOwnerSay(_SYMBOL_ARROW+ " "+ llList2String(data,1));
                }
                else if (command == "error") {
                    llOwnerSay(_SYMBOL_ARROW+ " "+ llList2String(data,1));
                }
                else if (command == "products updated") {
                    state waiting;
                }
                else {
                    llOwnerSay(body);
                }
            }
        }
    }
    remote_data(integer type, key channel, key message_id, string sender, integer ival, string sval)  {
        if (type & REMOTE_DATA_CHANNEL) {
            server_channel = channel;
            updateServer();
        } 
    }
    changed(integer change) {
        if (change & CHANGED_INVENTORY) {
            if (inventory_changed) {
                llOwnerSay(_INVENTORY_HAS_CHANGED);
                state default;
            }
        }
    }
}
// ******************************
//  SERVER WAITING FOR A COMMAND
// ******************************
state waiting {
    state_entry() {
		llOwnerSay(_SYMBOL_HOR_BAR_1);
        llOwnerSay(_ENTERING_WAIT_MODE);
		llOwnerSay(_SYMBOL_HOR_BAR_1);
        llSetTimerEvent(refresh);
        llOpenRemoteDataChannel();
        llSetColor(<0.,1.,0.>,ALL_SIDES);
    }
    touch_start(integer number) {
        if (llDetectedKey(0) == owner) {
            state default;
        }
    }
    timer() {
        //llCloseRemoteDataChannel(server_channel);
        llOpenRemoteDataChannel();
        llMessageLinked(LINK_THIS, REQUEST_UPDATE, "", NULL_KEY);
    }
    remote_data(integer type, key channel, key message_id, string sender, integer ival, string sval)  {
        if (type & REMOTE_DATA_CHANNEL) {
            server_channel = channel;
            updateServer();
        }
        else if (type & REMOTE_DATA_REQUEST) {
            list values = llParseString2List(sval,[HTTP_SEPARATOR],[]);
            string answer;
            if (ival == 70010) { // get status
                answer = "online";
            }
            else if (ival == 70091) { // give object
                string msg_password = llList2String(values,0);
                string msg_key      = llList2String(values,1);
                string msg_userKey  = llList2String(values,2);
                string object_name = llList2String(values,3);
                integer request_type = llList2Integer(values,4);
                string target_object = "";
                // check password
                if (msg_password == llMD5String(password, (integer)msg_key)) {
                    // check if request is an update
                    if (request_type == 1) { // update
                        target_object = "update-"+object_name;
                        // check if update exists
                        if (llGetInventoryKey(target_object) != NULL_KEY) {
                            llGiveInventory(msg_userKey, target_object);
                            answer = "sending";
                        }
                        else {
                            // check if object exists
                            answer = sendItem(object_name, msg_userKey);
                        }
                    }
                    else {
                        // check if object exists
                        answer = sendItem(object_name, msg_userKey);
                    }
                }
                else {
                    answer = "wrong password";
                }
            }
            llRemoteDataReply(channel,message_id,answer,1);
        }
    }
    http_response(key request_id, integer status, list metadata, string body) {
        body = llStringTrim(body , STRING_TRIM);
        if (status != 200) {
            getServerAnswer(status, body);
        }
        else {
            list data = llParseString2List(body, [HTTP_SEPARATOR],[]);
            string command = llList2String(data,0);
            if (command == "server updated") {
                llOwnerSay(_SYMBOL_ARROW+ " "+ llList2String(data,1));
            }
            else {
                llOwnerSay(body);
            }
        }
    }
    changed(integer change) {
        if (change & CHANGED_INVENTORY) {
            if (inventory_changed) {
                llOwnerSay(_INVENTORY_HAS_CHANGED);
                state default;
            }
        }
    }
}