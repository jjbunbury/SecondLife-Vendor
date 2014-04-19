// @version slvendor v64
// @package panel
// @copyright Copyright wene / ssm2017 Binder (C) 2007-2008. All rights reserved.
// @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
// slvendor is free software and parts of it may contain or be derived from the
// GNU General Public License or other free or open source software licenses.

// *************************************
//                      USER PREFS
// *************************************
// here is a main identifier for the panel
// this itentifier must be a number and unique in the object's link set
integer panelId = 1;
// here is the order of the panel from the main panel product.
// if you want the first product before the main panel, use productOffset = -1
// if you want the second product before the main panel, use productOffset = -2
// and if you want the first product after the main panel, use productOffset = 1 etc...
integer productOffset = 1;
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
integer multi_sides = 0;
// the texture side where you want the last texture appears
integer prev_side = 4;
// the texture side where you want the next texture appears
integer next_side = 2;
// rotate textures if the vendor is vertical in multi_sides mode (0 = enabled ; 1 = disabled)
integer rotate_texture = 0;
// ==========================================================
//      NOTHING SHOULD BE MODIFIED UNDER THIS LINE
// ==========================================================
// ***********************
//          VARS
// ***********************
integer products_qty = 0;
integer act_product;
list product_values;
string PARAM_SEPARATOR = ";";
// constants
integer RESET = 70000;
integer REQUEST_PRODUCTS_LIST = 72110;
integer GET_PRODUCT_VALUES = 72112;
integer SET_PRODUCT_VALUES = 72113;
integer SET_PRODUCTS_LIST_QTY = 72114;
integer SET_ACT_PRODUCT = 72011;
// ***********************
//      FUNCTIONS
// ***********************
// clear the texure and text
clear() {
    llSetText("",<0,0,0>,1);
    llSetTexture(texture,texture_side);
    llSetColor(<1.,1.,1.>,texture_side);
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
// ***********************
//  MAIN PROGRAM
// ***********************
default {
    on_rez(integer number) {
        llResetScript();
    }
    state_entry() {
        llSetAlpha(0,ALL_SIDES);
        clear();
    }
    link_message(integer sender_num, integer num, string str, key id) {
        if (num == SET_PRODUCTS_LIST_QTY) {
            products_qty = (integer)str;
        }
        else if (num == SET_ACT_PRODUCT) {
            act_product = ((integer)str) + productOffset;
            if (act_product >= products_qty) {
                act_product = llAbs(products_qty - act_product);
            }
            if (products_qty > productOffset) {
                if (multi_sides) {
                    llMessageLinked(LINK_SET, GET_PRODUCT_VALUES, (string)(act_product -1), (key)((string)(20000-panelId)));
                    if (products_qty > (productOffset + 1)) {
                        llMessageLinked(LINK_SET, GET_PRODUCT_VALUES, (string)(act_product), (key)((string)(10000+panelId)));
                    }
                    if (products_qty > (productOffset + 2)) {
                        if ((act_product+1) >= products_qty) {
                            act_product = (llAbs(products_qty - act_product)-2);
                        }
                        llMessageLinked(LINK_SET, GET_PRODUCT_VALUES, (string)(act_product +1), (key)((string)(20000+panelId)));
                    }
                }
                else {
                    llMessageLinked(LINK_SET, GET_PRODUCT_VALUES, (string)(act_product), (key)((string)(10000+panelId)));
                }
            }
        }
        else if (num == SET_PRODUCT_VALUES) {
            product_values = llParseStringKeepNulls(str, [PARAM_SEPARATOR],[]);
            integer dest = (integer)((string)id);
            if (dest == (10000+panelId)) {
                setTexture(llList2String(product_values,1), texture_side);
            }
            else if (dest == (20000-panelId)) {
                setTexture(llList2String(product_values,1), prev_side);
            }
            else if (dest == (20000+panelId)) {
                setTexture(llList2String(product_values,1), next_side);
            }
        }
        else if (num == RESET) {
            llResetScript();
        }
    }
}