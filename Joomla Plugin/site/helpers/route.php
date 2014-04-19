<?php
/**
 * @version		1.5
 * @package	SlVendor
 * @copyright	Copyright (C) 2007 - 2008 Wene (S.Massiaux). All rights reserved.
 * @license		GNU/GPL, http://www.gnu.org/licenses/gpl-2.0.html
 * SlVendor is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Component Helper
jimport('joomla.application.component.helper');

/**
 * SlVendor Component Route Helper
 *
 * @static
 * @package	SlVendor
 * @since 1.5
 */
class SlvendorHelperRoute
{
	function getCategoryRoute($catid) {
		$needles = array(
			'category' => (int) $catid,
			'categories' => null
		);

		//Find the itemid
		$itemid = SlvendorHelperRoute::_findItem($needles);
		$itemid = $itemid ? '&Itemid='.$itemid : '';

		//Create the link
		$link = 'index.php?option=com_slvendor&view=category&id='.$catid. $itemid;

		return $link;
	}
	function getProductRoute($id, $catid) {
		$needles = array(
			'category' => (int) $catid,
			'categories' => null
		);

		//Find the itemid
		$itemid = SlvendorHelperRoute::_findItem($needles);
		$itemid = $itemid ? '&Itemid='.$itemid : '';

		//Create the link
		$link = 'index.php?option=com_slvendor&view=product&id='. $id . '&catid='.$catid. $itemid;

		return $link;
	}

	function _findItem($needles)
	{
		static $items;

		if (!$items)
		{
			$component =& JComponentHelper::getComponent('com_slvendor');
			$menu = &JSite::getMenu();
			$items = $menu->getItems('componentid', $component->id);
		}

		if (!is_array($items)) {
			return null;
		}

		$match = null;
		foreach($needles as $needle => $id)
		{
			foreach($items as $item)
			{
				if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id)) {
					$match = $item->id;
					break;
				}
			}

			if(isset($match)) {
				break;
			}
		}

		return $match;
	}
}
?>
