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

jimport('joomla.application.component.model');

/**
 * SlVendor Component Contact Model
 *
 * @author wene
 * @package	SlVendor
 * @since 1.5
 */
class SlvendorModelProduct extends JModel
{
	/**
	 * product id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * product data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);
	}

	/**
	 * Method to set the product identifier
	 *
	 * @access	public
	 * @param	int Contact identifier
	 */
	function setId($id)
	{
		// Set product id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a product from its name
	 *
	 */
	function &getProductByName( $data )
	{
		$query = "SELECT id FROM #__slvendor_products"
					." WHERE name='".$data["product_name"]."'";
		$this->_db->setQuery( $query );
		$this->_id = $this->_db->loadResult();
		// Load the product data
		if ($this->_loadData())
		{
			// Initialize some variables
			$user = &JFactory::getUser();

			// Make sure the product is published
			if (!$this->_data->published) {
				return false;
			}

			// Check to see if the category is published
			if (!$this->_data->cat_pub) {
				return false;
			}

			// Check whether category access level allows access
			if ($this->_data->cat_access > $user->get('aid', 0)) {
				return false;
			}
		}
		else  $this->_initData();

		return $this->_data;
	}
	/**
	 * Method to get a product
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the product data
		if ($this->_loadData())
		{
			// Initialize some variables
			$user = &JFactory::getUser();

			// Make sure the product is published
			if (!$this->_data->published) {
				JError::raiseError(404, JText::_("Resource Not Found"));
				return false;
			}

			// Check to see if the category is published
			if (!$this->_data->cat_pub) {
				JError::raiseError( 404, JText::_("Resource Not Found") );
				return;
			}

			// Check whether category access level allows access
			if ($this->_data->cat_access > $user->get('aid', 0)) {
				JError::raiseError( 403, JText::_('ALERTNOTAUTH') );
				return;
			}
			jimport( 'joomla.filter.output' );
			$this->_data->slug = $this->_data->id.':'.JFilterOutput::stringURLSafe($this->_data->name);
		}
		else  $this->_initData();

		return $this->_data;
	}

	/**
	 * Method to increment the hit counter for the product
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function hit()
	{
		global $mainframe;

		if ($this->_id)
		{
			$product = & $this->getTable();
			$product->hit($this->_id);
			return true;
		}
		return false;
	}

	/**
	 * Method to load content product data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT w.*, cc.title AS category,' .
					' cc.published AS cat_pub, cc.access AS cat_access'.
					' FROM #__slvendor_products AS w' .
					' LEFT JOIN #__categories AS cc ON cc.id = w.catid' .
					' WHERE w.id = '. (int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the product data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$product = new stdClass();
			$product->id = 0;
			$product->catid = 0;
			$product->name = null;
			$product->version = null;
			$product->description = null;
			$product->price = null;
			$product->texture_uuid = null;
			$product->object_name = null;
			$product->server_id = null;
			$product->hits = 0;
			$product->published = 0;
			$product->checked_out = 0;
			$product->checked_out_time = 0;
			$product->ordering = 0;
			$product->params = null;
			$this->_data					= $product;
			return (boolean) $this->_data;
		}
		return true;
	}
	/**
	 * Method to store the product
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable();

		// Bind the form fields to the product table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$new = false;
		// if new item, order last in appropriate group
		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
			$new = true;
		}

		// check for the category
		if ( !$new )
		{
			$query = 'SELECT catid FROM #__slvendor_products WHERE id='.$row->id;
			$this->_db->setQuery($query);
			$row->catid = $this->_db->loadResult();
		}

		// Store the product table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if ($new) {
			return $row->name." ".JText::_( 'ADDED')." \n";
		}else{
			return $row->name." ".JText::_( 'UPDATED')." \n";
		}
	}
}
?>
