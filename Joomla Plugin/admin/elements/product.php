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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JElementProduct extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Product';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT a.id, CONCAT( c.title, " - ",a.name ) AS text, a.catid '
		. ' FROM #__slvendor_products AS a'
		. ' INNER JOIN #__categories AS c ON a.catid = c.id'
		. ' WHERE a.published = 1'
		. ' AND c.published = 1'
		. ' ORDER BY c.title, a.catid, a.name'
		;
		$db->setQuery( $query );
		$options = $db->loadObjectList( );

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'text', $value, $control_name.$name );
	}
}
?>