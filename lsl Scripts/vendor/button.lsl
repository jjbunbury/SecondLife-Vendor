// @version slvendor v64
// @package vendor
// @copyright Copyright wene / ssm2017 Binder (C) 2007-2008. All rights reserved.
// @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
// slvendor is free software and parts of it may contain or be derived from the
// GNU General Public License or other free or open source software licenses.

// constants
integer GO_NEXT = 70051;
integer GO_PREVIOUS = 70052;
integer SHOW_BUTTONS = 70055;
integer HIDE_BUTTONS = 70056;

default {
    state_entry() {
        llSetAlpha(0,ALL_SIDES);
    }
    touch_start(integer total_number) {
        llMessageLinked(LINK_SET, GO_PREVIOUS, "", NULL_KEY); // comment or uncomment this line to define button order
        //llMessageLinked(LINK_SET, GO_NEXT, "", NULL_KEY); // comment or uncomment this line to define button order
    }
    link_message(integer sender_num, integer num, string str, key id) {
        if (num == SHOW_BUTTONS) {
            llSetAlpha(1,ALL_SIDES);
        }
        else if (num == HIDE_BUTTONS) {
            llSetAlpha(0,ALL_SIDES);
        }
    }
}