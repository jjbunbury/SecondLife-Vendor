<?php
/**
 * SlVendor table class
 * 
 * @package    SlVendor
 * @link http://joomlacode.org/gf/project/slvendor
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Server class
 *
 * @package    SlVendor
 */
class TableServer extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $name = null;

	/**
	 * @var string
	 */
	var $data_channel = null;

	/**
	* @var string
	*/
	var $uuid = null;

	/**
	* @var string
	*/
	var $region = null;

	/**
	* @var string
	*/
	var $position = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableServer(& $db) {
		parent::__construct('#__slvendor_servers', 'id', $db);
	}
}
?>
