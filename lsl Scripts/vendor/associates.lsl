// @version slvendor v64
// @package vendor
// @copyright Copyright wene / ssm2017 Binder (C) 2007-2008. All rights reserved.
// @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
// slvendor is free software and parts of it may contain or be derived from the
// GNU General Public License or other free or open source software licenses.

// **********************
//      USER PREFS
// **********************
// here is the list for the vendor associates.
// fill it with : userKey|percent
// example : 
// list vendorAssociates = [
//      "bf362c0b-67ec-49c7-a852-77968e3f7f56|50",
//      "bf362c0b-67ec-49c7-a852-77968e3f7f56|25"
//      ];
list vendorAssociates = [];
// ***********************
//          STRINGS
// ***********************
string _NOBODY = "nobody";
string _VENDOR_ASSOCIATES_SET_TO = "Vendor associates set to";
string _START_READING_ASSOCIATES_NOTECARD = "Start reading associates notecard.....";
string _ASSOCIATES_NOTECARD_READ = "Associates notecard read.";
string _MERCHANT_ASSOCIATES_SET_TO = "Merchant associates set to";
// ==========================================================
//      NOTHING SHOULD BE MODIFIED UNDER THIS LINE
// ==========================================================
// ***********************
//          VARS
// ***********************
key owner;
string ownerName;
// here is the list for the merchant associates list
list merchantAssociates = [];
// separators
string PRODUCT_SEPARATOR = "|";
string PARAM_SEPARATOR = ";";
// constants
integer RESET = 70000;
integer IS_NOTECARD_READ = 70061;
integer NOTECARD_READ = 70062;
integer SET_ASSOCIATES_QTY = 70071;
integer GET_ASSOCIATES_QTY = 70072;
integer PAY_ASSOCIATES = 72411;
integer GIVE_MONEY = 70081;
// ***********************
//          FUNCTIONS
// ***********************
// say associates
sayAssociates(list associates) {
    integer associatesQty = llGetListLength(associates);
    if (associatesQty) {
        integer i;
        for (i=0; i< associatesQty; ++i) {
            list associate = llParseString2List(llList2String(associates, i), [PRODUCT_SEPARATOR], []);
            llOwnerSay(llList2String(associate,0)+" : "+ llList2String(associate,1)+" %");
        }
    }
    else {
        llOwnerSay(_NOBODY+".");
    }
}
// pay associates
payAssociates(string data, key vendorKey) {
    list dataValues = llParseString2List(data, [PARAM_SEPARATOR], []);
    integer price = llList2Integer(dataValues, 0);
    integer vendorCommission;
    // define if vendor has commission
    if (vendorKey != NULL_KEY) {
        integer vendorPercent = llList2Integer(dataValues, 1);
        // define the vendor commission
        vendorCommission = (integer)(((float)price / (float)100) * (float)vendorPercent);
        price -= vendorCommission;
        // define if there are vendor's associates
        integer vendorAssociatesQty = llGetListLength(vendorAssociates);
        if (vendorAssociatesQty) {
            vendorCommission = payAssociatesSub(vendorAssociates, vendorCommission);
        }
        if (vendorCommission >= 1) {
            llMessageLinked(LINK_THIS, GIVE_MONEY, (string)vendorCommission, vendorKey);
        }
    }
    // define if there are merchant's associates
    integer merchantAssociatesQty = llGetListLength(merchantAssociates);
    if (merchantAssociatesQty) {
        vendorCommission = payAssociatesSub(merchantAssociates, price);
    }
}
// sub function for pay associates
integer payAssociatesSub(list associates, integer price) {
    // get every associate
    integer associatesQty = llGetListLength(associates);
    integer newPrice = price;
    integer i;
    for (i=0; i< associatesQty; i++) {
        list associate = llParseString2List(llList2String(associates, i), [PRODUCT_SEPARATOR], []);
        key associateKey = llList2Key(associate, 0);
        integer commission = (integer)(((float)price / (float)100) * llList2Float(associate, 1));
        if (commission >= 1) {
            newPrice -= commission;
            llMessageLinked(LINK_THIS, GIVE_MONEY, (string)commission, associateKey);
        }
    }
    return newPrice;
}
// *************************
//      READ THE NOTECARD
// *************************
// notecard vars
integer iLine = 0;
key associatesNoteCard;
integer notecardRead = FALSE;
string notecardName = "associatesList";
default {
    on_rez(integer change) {
        llResetScript();
    }
    state_entry() {
        // setup menu
        owner = llGetOwner();
        ownerName = llKey2Name(owner);
        // say the vendorAssociates list
        if (llGetListLength(vendorAssociates)) {
            llOwnerSay("************************");
            llOwnerSay(_VENDOR_ASSOCIATES_SET_TO + " : ");
            sayAssociates(vendorAssociates);
            llOwnerSay("************************");
        }
        // read the associates notecard
        if (llGetInventoryType(notecardName) != INVENTORY_NONE) {
            associatesNoteCard = llGetNotecardLine(notecardName,iLine);
            llOwnerSay(_START_READING_ASSOCIATES_NOTECARD);
        }
        else {
            notecardRead = TRUE;
        }
    }
    dataserver(key queryId, string data) {
        if (queryId == associatesNoteCard) {
            if(data != EOF) {
                if (llGetSubString(data, 0, 1) != "//") {
                    if (data != "") {
                        merchantAssociates = (merchantAssociates=[]) + merchantAssociates + [data];
                    }
                }
                associatesNoteCard = llGetNotecardLine(notecardName,++iLine);
            }
            else {
                notecardRead = TRUE;
                llOwnerSay(_ASSOCIATES_NOTECARD_READ+"...");
                integer merchantAssociatesLength = llGetListLength(merchantAssociates);
                llOwnerSay("************************");
                llOwnerSay(_MERCHANT_ASSOCIATES_SET_TO + " : ");
                sayAssociates(merchantAssociates);
                llOwnerSay("************************");
            }
        }
    }
    link_message(integer sender_num, integer num, string str, key id) {
        if (num == IS_NOTECARD_READ) {
            llMessageLinked(LINK_THIS, NOTECARD_READ, (string)notecardRead, NULL_KEY);
        }
        else if (num == GET_ASSOCIATES_QTY) {
            llMessageLinked(LINK_THIS, SET_ASSOCIATES_QTY, (string)(llGetListLength(merchantAssociates) + llGetListLength(vendorAssociates)), NULL_KEY);
        }
        else if (num == PAY_ASSOCIATES) {
            payAssociates(str, id);
        }
        else if (num == RESET) {
            llResetScript();
        }
    }
}