// @version slvendor v64
// @package text
// @copyright Copyright wene / ssm2017 Binder (C) 2007-2009. All rights reserved.
// @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
// slvendor is free software and parts of it may contain or be derived from the
// GNU General Public License or other free or open source software licenses.

// constants
integer RESET = 70000;
integer SET_TEXT = 70101;
integer SET_TEXT_COLOR = 70102;
// vars
vector text_color = <1.,1.,1.>;
default {
    on_rez(integer number) {
        llResetScript();
    }
    state_entry() {
        llSetText("", <0,0,0>, 1);
    }
    link_message(integer sender_num, integer num, string str, key id) {
        if (num == SET_TEXT) {
            llSetText(str, text_color, 1);
        }
        else if (num == SET_TEXT_COLOR) {
            text_color = (vector)str;
        }
        else if (num == RESET) {
            llResetScript();
        }
    }
}