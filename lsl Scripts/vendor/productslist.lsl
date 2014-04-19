// @version slvendor v64
// @package vendor
// @copyright Copyright wene / ssm2017 Binder (C) 2007-2009. All rights reserved.
// @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
// slvendor is free software and parts of it may contain or be derived from the
// GNU General Public License or other free or open source software licenses.

// **********************
//      USER PREFS
// **********************
// url ex: string url = "http://test.com";
string url = "";
// use this url if url is given by an external file
string url_file = "";
// password : the password you put in the joomla component config
string password = "";
// product qty by server request
integer product_qty = 10;
// display info
integer display_info = 0;
// **********************
//      STRINGS
// **********************
string _REQUESTING_URL = "Requesting url";
string _URL_FOUND = "Url found";
string _URL_NOT_FOUND = "Url not found";
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
// **********************
//  DEFAULT VARS
// **********************
list products = [];
string paramsList;
integer request_products = FALSE;
string PRODUCT_SEPARATOR = "|";
string PARAM_SEPARATOR = ";";
// constants
integer RESET = 70000;
integer REQUEST_PRODUCTS_LIST = 72110;
integer GET_PRODUCT_VALUES = 72112;
integer SET_PRODUCT_VALUES = 72113;
integer SET_PRODUCTS_LIST_QTY = 72114;
// **********************
//  HTTP REQUEST
// **********************
// get url
key requestUrlId;
requestUrl() {
    if (display_info) {
        llOwnerSay(_REQUESTING_URL);
    }
    requestUrlId = llHTTPRequest(url_file, [HTTP_METHOD, "GET", HTTP_MIMETYPE, "application/x-www-form-urlencoded"], "");
}
key requestProductsListId;
requestProductsList(integer start) {
    list params = llParseStringKeepNulls(paramsList,[PARAM_SEPARATOR],[]);
    integer keypass = (integer)llFrand(9999)+1;
    string md5pass = llMD5String(password, keypass);
    requestProductsListId = llHTTPRequest(url+url2,
                        [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                        "task=requestProductsList"
                        +"&password="+md5pass
                        +"&key="+(string)keypass
                        +"&category="+llList2String(params,0)
                        +"&start="+(string)start
                        +"&order="+llList2String(params,1)
                        +"&order_dir="+llList2String(params,2)
                       );
}
// get server answer
getServerAnswer(integer status, string body) {
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
// ***********************
//      MAIN PROGRAM
// ***********************
default {
    on_rez(integer number) {
        llResetScript();
    }
    state_entry() {
        if (url_file != "") {
            requestUrl();
        }
    }
    link_message(integer sender_num, integer num, string str, key id) {
        if (num == REQUEST_PRODUCTS_LIST) {
            request_products = TRUE;
            products = [];
            paramsList = str;
            if (url_file != "") {
                requestUrl();
            }
            else {
                requestProductsList(0);
            }
        }
        else if (num == GET_PRODUCT_VALUES) {
            llMessageLinked(LINK_SET, SET_PRODUCT_VALUES, llList2String(products,(integer)str), id);
        }
        else if (num == RESET) {
            llResetScript();
        }
    }
    http_response(key request_id, integer status, list metadata, string body) {
        if (status != 200) {
            getServerAnswer(status, body);
        }
        else {
            body = llStringTrim(body , STRING_TRIM);
            if (request_id == requestUrlId) {
                if (body !="") {
                    url = body;
                    if (display_info) {
                        llOwnerSay(_URL_FOUND);
                    }
                    if (request_products) {
                        request_products = FALSE;
                        requestProductsList(0);
                    }
                }
                else {
                    if (display_info) {
                        llOwnerSay(_URL_NOT_FOUND);
                    }
                }
            }
            else if (request_id == requestProductsListId) {
                body = llStringTrim(body , STRING_TRIM);
                list values = llParseString2List(body,[PRODUCT_SEPARATOR],[]);
                list params = llParseString2List(llList2String(values,0),[PARAM_SEPARATOR],[]);
                if (llList2String(params,0) == "products") {
                    integer qty = llList2Integer(params,1);
                    integer start = llList2Integer(params,2);
                    products = (products=[]) + products + llDeleteSubList(values,0,0);
                    if ((start+product_qty) >= qty) {
                        llMessageLinked(LINK_SET, SET_PRODUCTS_LIST_QTY, (string)llGetListLength(products), NULL_KEY);
                    }
                    else {
                        requestProductsList(start+product_qty);
                    }
                }
                else {
                    llOwnerSay(body);
                    llMessageLinked(LINK_SET, RESET, "", NULL_KEY);
                }
            }
        }
    }
}