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
 * Product class
 *
 * @package    SlVendor
 */
class TableProduct extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var int
	 */
	var $catid = null;

	/**
	* @var string
	*/
	var $name = null;

	/**
	* @var string
	*/
	var $version = null;

	/**
	* @var text
	*/
	var $short_desc = null;

	/**
	* @var text
	*/
	var $description = null;

	/**
	* @var int
	*/
	var $price = null;

	/**
	* @var string
	*/
	var $texture_uuid = null;

	/**
	* @var string
	*/
	var $object_name = null;

	/**
	 * @var int
	 */
	var $server_id = null;

	/**
	 * @var int
	 */
	var $hits = null;

	/**
	 * @var tinyint
	 */
	var $published = null;

	/**
	 * @var int
	 */
	var $checked_out = null;

	/**
	 * @var datetime
	 */
	var $checked_out_time = null;

	/**
	 * @var int
	 */
	var $ordering = null;

	/**
	 * @var text
	 */
	var $params = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableProduct(& $db) {
		parent::__construct('#__slvendor_products', 'id', $db);
	}
	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	*/
	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}
		return parent::bind($array, $ignore);
	}
}
?>
