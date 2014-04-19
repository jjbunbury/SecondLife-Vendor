// @version slvendor v64
// @package product
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
// product
string product_name = "";
string version = "";
// use this url if url is given by an external file
string url_file = "";
// display info
integer display_info = TRUE;
// avatar uuid to send the error message
key av_error_msg_target = "";
// ***********************
//          STRINGS
// ***********************
// checking vars
string _THE_VAR_NAMED = "The var named";
string _IS_MISSING = "is missing";
string _IN_SCRIPT_NAMED = "in script named";
//
string _REQUESTING_URL = "Requesting url";
string _URL_NOT_FOUND = "Url not found";
string _REQUESTING_UPDATE = "Requesting update...";
string _SERVER_SENDING_UPGRADE = "The server is sending you the upgrade";
string _UPGRADE_NOT_AVAILABLE = "Sorry but this upgrade is not available. Please try again later. ";
string _UNABLE_TO_SEND_THE_UPGRADE = "Unable to send the upgrade";
string _TO = "to";
string _MSG_MISSING_IN_SERVER = "Server is responding but the object is missing";
string _MSG_MISSING_ON_WEBSITE = "Object is not on the website";
string _CHECK_SERVER = "Check the server";
string _OBJECT_NAME = "Object name";
string _OBJECT_REGION = "Object region";
string _OBJECT_POSITION = "Object position";
string _OBJECT_PARCEL = "Object parcel";
string _OBJECT_OWNER_NAME = "Object owner name";
string _OBJECT_OWNER_KEY = "Object owner key";
string _SERVER_NAME = "Server name";
string _SERVER_REGION = "Server region";
string _SERVER_POSITION = "Server position";
// http errors
string _REQUEST_TIMED_OUT = "Request timed out";
string _FORBIDDEN_ACCESS = "Forbiden access";
string _PAGE_NOT_FOUND = "Page not found";
string _INTERNET_EXPLODED = "the internet exploded!!";
string _SERVER_ERROR = "Server error";
// ==========================================================
//      NOTHING SHOULD BE MODIFIED UNDER THIS LINE
// ==========================================================
// **********************
//  JOOMLA VERSION URL
// **********************
// == JOOMLA 1.5.X ==
string url2 = "/index.php?option=com_slvendor&controller=inworld";
// ***********************
//          VARS
// ***********************
// owner
key owner;
string owner_name;
// constants
integer RESET = 70000;
integer REQUEST_UPDATE = 72001;
// build pos
string buildPos() {
    vector pos = llGetPos();
    return (string)llFloor(pos.x)+"/"+(string)llFloor(pos.y)+"/"+(string)llFloor(pos.z);
}
// get parcel name
string getParcelName() {
    list parcelName = llGetParcelDetails(llGetPos(),[PARCEL_DETAILS_NAME]);
    return llList2String(parcelName,0);
}
// check vars
integer checkVars() {
    integer passed = TRUE;
    string script_name = llGetScriptName();
    if (password == "") {
        llOwnerSay(_THE_VAR_NAMED+ " \"password\" "+ _IS_MISSING+ " "+ _IN_SCRIPT_NAMED+ " \""+ script_name+ "\"");
        passed = FALSE;
    }
    if (product_name == "") {
        llOwnerSay(_THE_VAR_NAMED+ " \"product_name\" "+ _IS_MISSING+ " "+ _IN_SCRIPT_NAMED+ " \""+ script_name+ "\"");
        passed = FALSE;
    }
    if (version == "") {
        llOwnerSay(_THE_VAR_NAMED+ " \"version\" "+ _IS_MISSING+ " "+ _IN_SCRIPT_NAMED+ " \""+ script_name+ "\"");
        passed = FALSE;
    }
    if (av_error_msg_target == "") {
        llOwnerSay(_THE_VAR_NAMED+ " \"av_error_msg_target\" "+ _IS_MISSING+ " "+ _IN_SCRIPT_NAMED+ " \""+ script_name+ "\"");
        passed = FALSE;
    }
    return passed;
}
// **********************
//          HTTP
// **********************
string HTTP_SEPARATOR = ";";
// update products
key requestUrlId;
requestUrl() {
    if (display_info) {
        llOwnerSay(_REQUESTING_URL);
    }
    requestUrlId = llHTTPRequest(url_file, [HTTP_METHOD, "GET", HTTP_MIMETYPE, "application/x-www-form-urlencoded"], "");
}
// update products
key requestUpdateId;
requestUpdate() {
    if (display_info) {
        llOwnerSay(_REQUESTING_UPDATE);
    }
    // building password
    integer keypass = (integer)llFrand(9999)+1;
    string md5pass = llMD5String(password, keypass);
    // sending values
    requestUpdateId = llHTTPRequest(url+url2, [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                "task=requestUpdate"
                +"&user_key="+(string)owner
                +"&password="+md5pass
                +"&key="+(string)keypass
                +"&product_name="+product_name
                +"&version="+version
                +"&user_name="+llKey2Name(owner)
                +"&vendor_name="+llGetObjectName()
                +"&region="+(string)llGetRegionName()
                +"&position="+buildPos()
                +"&parcel="+getParcelName()
               );
}
// get server answer
getServerAnswer(integer status, string body) {
    if (display_info) {
        if (status == 499) {
            llOwnerSay((string)status+ " "+ _REQUEST_TIMED_OUT);
        }
        else if (status == 403) {
            llOwnerSay((string)status+ " "+ _FORBIDDEN_ACCESS);
        }
        else if (status == 404) {
            llOwnerSay((string)status+ " "+ _PAGE_NOT_FOUND);
        }
        else if (status == 500) {
            llOwnerSay((string)status+ " "+ _SERVER_ERROR);
        }
        else if (status != 403 && status != 404 && status != 500) {
            llOwnerSay((string)status+ " "+ _INTERNET_EXPLODED);
            llOwnerSay(body);
        }
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
        owner = llGetOwner();
        owner_name = llKey2Name(owner);
        if (checkVars()) {
            if (url_file != "") {
                requestUrl();
            }
            else {
                requestUpdate();
            }
        }
    }
    link_message(integer sender_num, integer num, string str, key id) {
        if (num == REQUEST_UPDATE) {
            requestUpdate();
        }
        else if (num == RESET) {
            llResetScript();
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
                    requestUpdate();
                }
                else {
                    if (display_info) {
                        llOwnerSay(_URL_NOT_FOUND);
                    }
                }
            }
            else if (request_id == requestUpdateId) {
                list values = llParseStringKeepNulls(body,[HTTP_SEPARATOR],[]);
                string response            = llList2String(values,0);
                string sentPassword     = llList2String(values,1);
                string keypass             = llList2String(values,2);
                string answer             = llList2String(values,3);
                // check the password
                if (llMD5String(password,(integer)keypass) == sentPassword) {
                    if (response == "sending") {
                        llOwnerSay(_SERVER_SENDING_UPGRADE);
                    }
                    else if (response == "no product available" || response == "no update available") {
                        if (display_info) {
                            llOwnerSay(answer);
                        }
                    }
                    else {
                        // say excuses
                        string msg = _UPGRADE_NOT_AVAILABLE;
                        llInstantMessage(owner,msg);
                        llDialog(owner, msg,[],9999);
                        string message = _UNABLE_TO_SEND_THE_UPGRADE+" : "+product_name+" "+_TO+" : "+ owner_name +". ";
                        if (response == "missing in server") {
                            message += _MSG_MISSING_IN_SERVER+".\n";
                        }
                        else if (response == "missing on website") {
                            message+= _MSG_MISSING_ON_WEBSITE+".\n";
                        }
                        else {
                            message += _CHECK_SERVER+".\n";
                        }
                        message += _OBJECT_NAME+" : "+               product_name+"\n";
                        message += _OBJECT_REGION+" : "+            (string)llGetRegionName()+"\n";
                        message += _OBJECT_POSITION+" : "+        buildPos()+"\n";
                        message += _OBJECT_PARCEL+" : "+              getParcelName()+"\n";
                        message += _OBJECT_OWNER_NAME+" : "+ owner_name+"\n";
                        message += _OBJECT_OWNER_KEY+" : "+     (string)owner+"\n";
                        message += _SERVER_NAME+" : "+                 llList2String(values,14)+"\n";
                        message += _SERVER_REGION+" : "+              llList2String(values,15)+"\n";
                        message += _SERVER_POSITION+" : "+          llList2String(values,16)+"\n";
                        llInstantMessage(av_error_msg_target, message);
                    }
                }
                else {
                    llOwnerSay(body);
                }
            }
        }
    }
}