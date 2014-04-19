<?php
/**
 * Server Model for SlVendor Component
 * 
 * @package    SlVendor
 * @link http://joomlacode.org/gf/project/slvendor
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * SlVendor Server Model
 *
 * @package    SlVendor
 */
class SlVendorModelServer extends JModel
{
	/**
	 * server id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * server data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the Server identifier
	 *
	 * @access	public
	 * @param	int Server identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a server
	 * @return object with data
	 */

	function &getData()
	{
		$query = 'SELECT s.*'.
				' FROM #__slvendor_servers AS s' .
				' WHERE s.id = '.(int) $this->_id;
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObject();
		return $this->_data;
	}

	/**
	 * Method to get a server from its uuid
	 * @return object with data
	 */

	function &getServerId($data)
	{
		$query = "SELECT id FROM #__slvendor_servers"
						." WHERE uuid='".$data['uuid']."'";
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}

	/**
	 * Method to store the server
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable();

		// Bind the form fields to the server table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$new = false;
		// if new item
		if (!$row->id) {
			$new = true;
		}

		// Store the server table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if ($new) {
			$this->_id = $this->_db->insertid();
			return JText::_( 'NEW SERVER REGISTERED')." \n";
		}else{
			$this->_id = $row->id;
			return JText::_( 'SERVER UPDATED')." \n";
		}
	}
}
?>
