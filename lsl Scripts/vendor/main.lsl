// @version slvendor v64
// @package vendor
// @copyright Copyright wene / ssm2017 Binder (C) 2007-2009. All rights reserved.
// @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
// slvendor is free software and parts of it may contain or be derived from the
// GNU General Public License or other free or open source software licenses.

// *************************************
//                      USER PREFS
// *************************************
// -----------------------------
//          website
// -----------------------------
// url ex: string url = "http://test.com";
string url = "";
// use this url if url is given by an external file
string url_file = "";
// password : the password you put in the joomla component config
string password = "";
// category : the category you want to use for the vendor ("all" = all categories)
string category = "all";
// order : you can choose between : (ordering, price, name) to order your products
string order = "ordering";
// order _dir : you can choose the direction of ordering (asc or desc)
string order_dir = "asc";
// refresh time between 2 sites updates
float refresh_time = 1800;
// show text on this object or on an external one
integer show_text = TRUE;
// show a product counter
integer show_counter = TRUE;
// -----------------------------
//          texturing
// -----------------------------
// texture you want to display during refresh time or when the vendor is inactive
string texture = "51b4b1b2-3452-57e4-c6d5-ce02198e6265";
// the texture side where you want the main texture appears
integer texture_side = 0;
// choose if you want to use the multiple sides texturing (0 = enabled ; 1 = disabled)
// you can have a main side with the main product and 2 other sides with last and previous product
// to do this, you can build a box then change taper x to 0.63, then you can have 3 textures on one object
integer multi_sides = 1;
// the texture side where you want the last texture appears
integer prev_side = 4;
// the texture side where you want the next texture appears
integer next_side = 2;
// rotate textures if the vendor is vertical in multi_sides mode (0 = enabled ; 1 = disabled)
integer rotate_texture = 0;
// -----------------------------
//          money
// -----------------------------
// put your own key here before distributing the vendor to someone else
string vendor_key = "";
// the percent value you want to keep /!\ BE CARREFULL BECAUSE THIS SYSTEM HAS CHANGED AFTER V33
integer vendor_percent = 85;
// enable or disable the associates function (0 = disabled / 0 = enabled)
// if set to 0, you don't need to ass the associates script and the notecard
integer use_associates = 0;
// -----------------------------
//          utils
// -----------------------------
// script quantity in inventory. If the script finds more than this number, the object will die to prevent hacking.
integer scripts_qty = 2;
// test mode to test the vendor by the owner (0 = normal mode / 1 = test mode)
integer test_mode = 1;
// display info
integer display_info = 0;
// *************************************
//                          STRINGS
// *************************************
// common
string _VENDOR_STOPPED = "Vendor stopped";
string _DEBT_PERMISSION_REFUSED = "You didnt give money permission, the script will stop.";
string _CHOOSE_AN_OPTION = "Choose an option";
string _RESET = "Reset";
string _UPDATE = "Update";
// url
string _REQUESTING_URL = "Requesting url";
string _URL_FOUND = "Url found";
string _URL_NOT_FOUND = "Url not found";
// associates
string _CHECKING_FOR_ASSOCIATES = "Checking for associates...";
string _ASSOCIATES_QTY = "Associates qty";
string _YOU_HAVE_BEEN_PAYED = "You have been payed";
string _BY = "by";
// script protection
string _TRIED_TO_HACK = "tried to add a script in your vendor.";
string _DONT_TRY_TO_HACK = "Dont try to add a new script inside this vendor, please.";
// products
string _REQUESTING_PRODUCTS = "Requesting products...";
string _NO_PRODUCTS_ON_WEBSITE = "No products available on website. Script will stop";
string _PRODUCTS_QTY = "Products Qty";
string _SEE_PRODUCT_ON_WEBSITE = "See the product on the website";
// purchase
string _TOUCH_TO_GET_OBJECT = "Just touch the panel to get the object.";
string _WRONG_AMOUNT = "Wrong amount";
string _GOOD_VALUE_IS = "Good value is";
string _THANK_YOU_FOR_PURCHASE = "Thank you for your purchase. Your object will be delivered soon";
string _SENDING_THE_NOTECARD = "Sending the notecard";
string _BUY = "Buy";
string _TOUCH = "Touch";
string _PRODUCT = "product";
string _NOTECARD = "notecard";
string _GET_NOTECARD = "Get Notecard";
string _WEBSITE = "Website";
string _SEND_IM_TO = "For any problem, send an im to";
// purchase errors
string _SORRY_BUT_THIS = "Sorry but this";
string _IS_NOT_AVAILABLE = "is not available. Please try again later.";
string _WE_WILL_REFUND = "We will give your money back.";
string _UNABLE_TO_SEND = "Unable to send";
string _TO = "to";
string _MSG_MISSING_IN_SERVER = "Server is responding but the object is missing";
string _MSG_MISSING_ON_WEBSITE = "Object is not on the website";
string _MSG_SERVER_MISSING_ON_WEBSITE = "Server is missing on the website";
string _MSG_CHECK_SERVER = "Check the server object";
string _VENDOR_NAME = "Vendor name";
string _VENDOR_REGION = "Vendor region";
string _VENDOR_POSITION = "Vendor position";
string _VENDOR_PARCEL = "Vendor parcel";
string _VENDOR_OWNER_NAME = "Vendor owner name";
string _VENDOR_OWNER_KEY = "Vendor owner key";
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
integer products_qty = 0;
integer act_product = 0;
list product_values;
key owner;
string owner_name;
integer menu_channel;
integer menu_listener;
integer associates_qty = 0;
// separators
string HTTP_SEPARATOR = ";";
string PRODUCT_SEPARATOR = "|";
string PARAM_SEPARATOR = ";";
// constants
integer RESET = 70000;
integer REQUEST_PRODUCTS_LIST = 72110;
integer GET_PRODUCT_VALUES = 72112;
integer SET_PRODUCT_VALUES = 72113;
integer SET_PRODUCTS_LIST_QTY = 72114;
integer SET_ACT_PRODUCT = 72011;
integer IS_NOTECARD_READ = 70061;
integer NOTECARD_READ = 70062;
integer SET_ASSOCIATES_QTY = 70071;
integer GET_ASSOCIATES_QTY = 70072;
integer PAY_ASSOCIATES = 72411;
integer GIVE_MONEY = 70081;
integer GO_NEXT = 70051;
integer GO_PREVIOUS = 70052;
integer SHOW_BUTTONS = 70055;
integer HIDE_BUTTONS = 70056;
integer SET_TEXT = 70101;
integer SET_TEXT_COLOR = 70102;
integer REQUEST_UPDATE = 72001;
// ***********************
//      FUNCTIONS
// ***********************
// clear the texure and text
clear() {
    setText("", <0,0,0>);
    llSetTexture(texture,texture_side);
    llSetColor(<1.,1.,1.>,texture_side);
    llRotateTexture(0 , texture_side);
    if (multi_sides) {
        llSetAlpha(0, prev_side);
        llSetTexture(texture,prev_side);
        llSetColor(<1.,1.,1.>,prev_side);
        llRotateTexture((90*DEG_TO_RAD) , prev_side);
        llSetAlpha(0, next_side);
        llSetTexture(texture,next_side);
        llSetColor(<1.,1.,1.>,next_side);
        llRotateTexture((-90*DEG_TO_RAD) , next_side);
        if (rotate_texture) {
            llRotateTexture((180*DEG_TO_RAD) , prev_side);
            llRotateTexture(0 , next_side);
            llRotateTexture((90*DEG_TO_RAD) , texture_side);
        }
    }
    llMessageLinked(LINK_SET, HIDE_BUTTONS, "", NULL_KEY);
}
// set texture
setTexture(string textureKey, integer side) {
    if (textureKey) {
        llSetAlpha(1, side);
        llSetTexture(textureKey,side);
    }
    else {
        llSetAlpha(0, side);
        llSetTexture(texture,side);
    }
}
// set text
setText(string text, vector color) {
    string counter_text = "\n "+ (string)act_product+ " / "+ (string)products_qty;
    if (show_text && show_counter) {
        text += counter_text;
    }
    if (!show_text && show_counter) {
        text = counter_text;
    }
    if (show_text || show_counter) {
        llSetText(text, color,1);
    }
    llMessageLinked(LINK_SET, SET_TEXT_COLOR, (string)color, NULL_KEY);
    llMessageLinked(LINK_SET, SET_TEXT, text, NULL_KEY);
}
// get perms
string getPerms(integer perms) {
    if (perms == 0) {
        return "Copy / Modify / Transfer";
    }
    else if (perms == 1) {
        return "No Transfer";
    }
    else if (perms == 2) {
        return "No Modify";
    }
    else if (perms == 3) {
        return "No Copy";
    }
    else if (perms == 4) {
        return "No Modify / No Transfer";
    }
    else if (perms == 5) {
        return "No Copy / No Transfer";
    }
    else if (perms == 6) {
        return "No Copy / No Modify";
    }
    else if (perms == 7) {
        return "No Copy / No Modify / No Transfer";
    }
    return "No Copy / No Modify / No Transfer";
}
// call menu
callMenu(key user, string text, list buttons) {
    llListenRemove(menu_listener);
    menu_listener = llListen(menu_channel,"", user,"");
    llDialog(user, text+" : ", buttons, menu_channel);
}
// request the product list
requestProductsList() {
    clear();
    act_product = 0;
    setText(_REQUESTING_PRODUCTS,<0,1,0>);
    llMessageLinked(LINK_THIS, REQUEST_PRODUCTS_LIST, category+";"+order+";"+order_dir, NULL_KEY);
}
// anti hack protection
antiHack() {
    if (llGetInventoryNumber(INVENTORY_SCRIPT) != scripts_qty) {
        if (vendor_key != "") {
            llInstantMessage(vendor_key,(string)owner+" "+_TRIED_TO_HACK);
        }
        llOwnerSay(_DONT_TRY_TO_HACK);
        llDie();
    }    
}
// call multisides
callMultiSides(integer product_id) {
    if (multi_sides) {
        if (products_qty > 2) {
            llMessageLinked(LINK_THIS, GET_PRODUCT_VALUES, (string)(product_id -1), (key)((string)999));
        }
        if (products_qty > 1) {
            integer request_id = product_id+1;
            if (request_id > (products_qty-1)) {
                request_id = 0;
            }
            llMessageLinked(LINK_THIS, GET_PRODUCT_VALUES, (string)(request_id), (key)((string)1001));
        }
    }
}
// ===============
//  HTTP REQUEST
// ===============
// get url
key requestUrlId;
requestUrl() {
    llOwnerSay(_REQUESTING_URL);
    requestUrlId = llHTTPRequest(url_file, [HTTP_METHOD, "GET", HTTP_MIMETYPE, "application/x-www-form-urlencoded"], "");
}
// request product
key requestProductId;
requestProduct(string user_key, integer type) {
    if (type == INVENTORY_OBJECT) {
        llInstantMessage(user_key, _THANK_YOU_FOR_PURCHASE+". "+_SEND_IM_TO+" : "+owner_name);
    }
    else if (type == INVENTORY_NOTECARD) {
        llInstantMessage(user_key, _SENDING_THE_NOTECARD);
    }
    integer keypass = (integer)llFrand(9999)+1;
    string md5pass = llMD5String(password, keypass);
    requestProductId = llHTTPRequest(url+url2,[HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                        "task=requestProduct"
                        +"&app=slvendor"
                        +"&cmd=requestProduct"
                        +"&password="+md5pass
                        +"&key="+(string)keypass
                        +"&type="+(string)type
                        +"&product_name="+ llList2String(product_values,0)
                        +"&product_price="+ llList2String(product_values,2)
                        +"&user_name="+ llKey2Name(user_key)
                        +"&user_key="+ user_key);
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
//  INIT PROGRAM
// ***********************
default {
    on_rez(integer number) {
        llResetScript();
    }
    state_entry() {
        llSetText("", <0,0,0>, 1);
        if (url_file != "") {
            requestUrl();
        }
        else {
            state perms;
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
                    state perms;
                }
                else {
                    llOwnerSay(_URL_NOT_FOUND);
                }
            }
        }
    }
    // anti hack protection
    changed(integer change) {
        if (change & CHANGED_INVENTORY) {
            antiHack();
        }
    }
}
// ***********************
//  ASK FOR PERMS
// ***********************
state perms {
    on_rez(integer number) {
        llResetScript();
    }
    state_entry() {
        // llMessageLinked(LINK_SET, RESET, "", NULL_KEY);
        owner = llGetOwner();
        owner_name = llKey2Name(owner);
        menu_channel = llFloor(llFrand(100000.0)) + 1000;
        clear();
        llRequestPermissions(llGetOwner(), PERMISSION_DEBIT);
    }
    touch_start(integer number) {
        if (llDetectedKey(0) == owner) {
            callMenu(owner, _CHOOSE_AN_OPTION+" : ", [_RESET]);
        }
    }
    run_time_permissions(integer perms) {
        if (perms & PERMISSION_DEBIT) {
            state checkAssociates;
        }
        else {
            llOwnerSay(_DEBT_PERMISSION_REFUSED);
            setText(_VENDOR_STOPPED, <1,1,1>);
            return;
        }
    }
    listen(integer channel, string name, key id, string message) {
        if (channel == menu_channel) {
            if (message == _RESET) {
                llMessageLinked(LINK_SET, RESET, "", NULL_KEY);
                llResetScript();
            }
        }
    }
    // anti hack protection
    changed(integer change) {
        if (change & CHANGED_INVENTORY) {
            antiHack();
        }
    }
}
// ***********************
//  CHECK ASSOCIATES
// ***********************
state checkAssociates {
    on_rez(integer number) {
        llResetScript();
    }
    state_entry() {
        if (use_associates) {
            llOwnerSay(_CHECKING_FOR_ASSOCIATES);
            llMessageLinked(LINK_THIS, IS_NOTECARD_READ, "", NULL_KEY);
        }
        else {
            associates_qty = 0;
            state run;
        }
    }
    link_message(integer sender_num, integer num, string str, key id) {
        if (num == NOTECARD_READ) {
            if ((integer)str) {
                llMessageLinked(LINK_THIS, GET_ASSOCIATES_QTY, "", NULL_KEY);
            }
        }
        else if (num == SET_ASSOCIATES_QTY) {
            llOwnerSay(_ASSOCIATES_QTY + " : "+ str);
            associates_qty = (integer)str;
            state run;
        }
        else if (num == RESET) {
            llResetScript();
        }
    }
    // anti hack protection
    changed(integer change) {
        if (change & CHANGED_INVENTORY) {
            antiHack();
        }
    }
}
// ***********************
//  MAIN PROGRAM
// ***********************
state run {
    on_rez(integer number) {
        llResetScript();
    }
    state_entry() {
        llSetTimerEvent(refresh_time);
        requestProductsList();
    }
    touch_start(integer number) {
        list menuItems;
        key toucher =llDetectedKey(0);
        if (toucher == owner) {
            menuItems = [_UPDATE, _RESET];
            if (test_mode) {
                menuItems = (menuItems=[]) + menuItems + [_WEBSITE, _GET_NOTECARD, _BUY];
            }
        }
        else {
            menuItems = [_WEBSITE, _GET_NOTECARD];
            if (llList2Integer(product_values,2) == 0) {
                menuItems = (menuItems=[]) + menuItems + [_BUY];
            }
        }
        callMenu(toucher, _CHOOSE_AN_OPTION+" : ", menuItems);
    }
    listen(integer channel, string name, key id, string message) {
        if (channel == menu_channel) {
            if (message == _RESET) {
                llMessageLinked(LINK_SET, RESET, "", NULL_KEY);
                llResetScript();
            }
            else if (message == _UPDATE) {
                llMessageLinked(LINK_ALL_OTHERS, RESET, "", NULL_KEY);
                if (url_file != "") {
                    requestUrl();
                }
                else {
                    requestProductsList();
                }
            }
            else if (message == _WEBSITE) {
                llLoadURL(id, _SEE_PRODUCT_ON_WEBSITE, url+ "/index.php?option=com_slvendor&view=product&id="+ llList2String(product_values, 3));
            }
            else if (message == _GET_NOTECARD) {
                requestProduct(id, INVENTORY_NOTECARD);
            }
            else if (message == _BUY) {
                if (llList2Integer(product_values,2) == 0 || id == owner) {
                    requestProduct(id, INVENTORY_OBJECT);
                }
            }
        }
    }
    link_message(integer sender_num, integer num, string str, key id) {
        if (num == SET_PRODUCTS_LIST_QTY) {
            products_qty = (integer)str;
            if (products_qty == 0) {
                llOwnerSay("***********************************************");
                llOwnerSay(_NO_PRODUCTS_ON_WEBSITE);
                llOwnerSay("***********************************************");
                setText(_VENDOR_STOPPED,<1,1,1>);
                return;
            }
            else {
                if (display_info) {
                    llOwnerSay(_PRODUCTS_QTY+" = "+str);
                }
                if (products_qty > 1) {
                    llMessageLinked(LINK_SET, SHOW_BUTTONS, "", NULL_KEY);
                }
                llMessageLinked(LINK_THIS, GET_PRODUCT_VALUES, "0", (key)((string)1000));
                callMultiSides(0);
            }
        }
        else if (num == GO_NEXT) {
            if (act_product == (products_qty-1)) {
                act_product = 0;
            }
            else {
                ++act_product;
            }
            llMessageLinked(LINK_THIS, GET_PRODUCT_VALUES, (string)act_product, (key)((string)1000));
            callMultiSides(act_product);
        }
        else if (num == GO_PREVIOUS) {
            if (act_product == 0) {
                act_product = (products_qty-1);
            }
            else {
                --act_product;
            }
            llMessageLinked(LINK_THIS, GET_PRODUCT_VALUES, (string)act_product, (key)((string)1000));
            callMultiSides(act_product);
        }
        else if (num == SET_PRODUCT_VALUES) {
            list data = llParseStringKeepNulls(str, [PARAM_SEPARATOR],[]);
            integer panelId = (integer)((string)id);
            if (panelId == 1000) {
                product_values = data;
                integer price = llList2Integer(product_values,2);
                string text = llList2String(product_values,0)+" : "+(string)price+"L$";
                string short_desc = llList2String(product_values,4);
                if (short_desc != "") {
                    text += "\n "+short_desc;
                }
                text += "\n"+ getPerms(llList2Integer(product_values,5));
                if (price != 0) {
                    llSetPayPrice(PAY_HIDE, [price, PAY_HIDE, PAY_HIDE, PAY_HIDE]);
                }
                else {
                    llSetPayPrice(PAY_DEFAULT, [PAY_DEFAULT, PAY_DEFAULT, PAY_DEFAULT, PAY_DEFAULT]);
                    text += "\n "+_TOUCH_TO_GET_OBJECT;
                }
                setTexture(llList2String(product_values,1), texture_side);
                setText(text,<1,1,1>);
                llMessageLinked(LINK_ALL_OTHERS, SET_ACT_PRODUCT, (string)act_product, NULL_KEY);
            }
            else if (panelId == 999) {
                setTexture(llList2String(data,1), prev_side);
            }
            else if (panelId == 1001) {
                setTexture(llList2String(data,1), next_side);
            }
        }
        else if (num == GIVE_MONEY) {
            integer amount = (integer)str;
            if (amount >= 1) {
                llGiveMoney(id, amount);
                llInstantMessage(id, _YOU_HAVE_BEEN_PAYED+ " "+(string)(amount) + " " + _BY + " " + llGetObjectName());
            }
        }
        else if (num == RESET) {
            llResetScript();
        }
    }
    money(key giver, integer amount) {
        integer price = llList2Integer(product_values,2);
        if(amount != price) {
            // Refund and say correct price
            llInstantMessage(giver,_WRONG_AMOUNT+". "+_GOOD_VALUE_IS+" : L$" + (string)price);
            llDialog(giver, _WRONG_AMOUNT+". "+_GOOD_VALUE_IS+" : L$" + (string)price,[], -9999);
            llGiveMoney(giver,amount);
        }
        else {
            requestProduct((string)giver, INVENTORY_OBJECT);
        }
    }
    http_response(key request_id, integer status, list metadata, string body) {
        body = llStringTrim(body , STRING_TRIM);
        if (status != 200) {
            getServerAnswer(status, body);
        }
        else {
            if (request_id == requestProductId) {
                list values = llParseStringKeepNulls(body,[PARAM_SEPARATOR],[]);
                string response            = llList2String(values, 0);
                string sent_password   = llList2String(values, 1);
                string keypass              = llList2String(values, 2);
                string item_name      = llList2String(values, 5);
                integer item_type         = llList2Integer(values, 4);
                string item_type_name = "";
                if (item_type == INVENTORY_NOTECARD) {
                    item_type_name = _NOTECARD;
                }
                else if (item_type == INVENTORY_OBJECT) {
                    item_type_name = _PRODUCT;
                }
                string user_key            = llList2String(values, 7);
                integer price                = llList2Integer(values, 8);
                // check the password
                if (llMD5String(password,(integer)keypass) == sent_password) {
                    if (response == "sending") {
                        if ( item_type != INVENTORY_NOTECARD) {
                            if (associates_qty) {
                                // pay to vendor and associates
                                if (vendor_key == "") {
                                    vendor_key = "NONE";
                                }
                                string data = (string)price+PARAM_SEPARATOR+(string)vendor_percent;
                                llMessageLinked(LINK_THIS, PAY_ASSOCIATES, data, vendor_key);
                            }
                            else {
                                // define value to give commission
                                if (vendor_key != "") {
                                    integer commission = (integer)(((float)price / (float)100) * (float)vendor_percent);
                                    if (commission >= 1) {
                                        llGiveMoney(vendor_key,commission);
                                    }
                                }
                            }
                        }
                    }
                    else {
                        // refound and say excuses
                        string msg = _SORRY_BUT_THIS+ " "+ item_type_name+ " "+ _IS_NOT_AVAILABLE;
                        if (price != 0 && item_type != INVENTORY_NOTECARD) {
                            msg += _WE_WILL_REFUND;
                            llGiveMoney(user_key, price);
                        }
                        llInstantMessage(user_key,msg);
                        llDialog(user_key, msg,[],-9999);
                        string message = _UNABLE_TO_SEND+ " "+ item_type_name+" : "+item_name+" "+_TO+" : "+ llKey2Name(user_key) +". ";
                        if (response == "missing in server") {
                            message += _MSG_MISSING_IN_SERVER+".\n";
                        }
                        else if (response == "missing on website") {
                            message+= _MSG_MISSING_ON_WEBSITE+".\n";
                        }
                        else if (response == "server missing on website") {
                            message+= _MSG_SERVER_MISSING_ON_WEBSITE+".\n";
                        }
                        else {
                            message += _MSG_CHECK_SERVER+".\n";
                        }
                        message += _VENDOR_NAME+" : "+                  llList2String(values,9)+"\n";
                        message += _VENDOR_REGION+" : "+               llList2String(values,10)+"\n";
                        message += _VENDOR_POSITION+" : "+           llList2String(values,11)+"\n";
                        message += _VENDOR_OWNER_NAME+" : "+    llList2String(values,12)+"\n";
                        message += _VENDOR_OWNER_KEY+" : "+        llList2String(values,13)+"\n";
                        message += _SERVER_NAME+" : "+                    llList2String(values,14)+"\n";
                        message += _SERVER_REGION+" : "+                 llList2String(values,15)+"\n";
                        message += _SERVER_POSITION+" : "+             llList2String(values,16)+"\n";
                        llInstantMessage(owner,message);
                    }
                }
            }
            else if (request_id == requestUrlId) {
                if (body !="") {
                    url = body;
                    if (display_info) {
                        llOwnerSay(_URL_FOUND);
                    }
                    requestProductsList();
                }
                else {
                    llOwnerSay(_URL_NOT_FOUND);
                }
            }
        }
    }
    timer() {
        if (url_file != "") {
            requestUrl();
        }
        else {
            requestProductsList();
        }
        llMessageLinked(LINK_THIS, REQUEST_UPDATE, "", NULL_KEY);
    }
    // anti hack protection
    changed(integer change) {
        if (change & CHANGED_INVENTORY) {
            antiHack();
        }
    }
}