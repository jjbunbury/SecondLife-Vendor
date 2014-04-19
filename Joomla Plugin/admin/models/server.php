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
	 * product id
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
	 * Method to remove a server
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__slvendor_servers'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			// unpublish objects linked to this server
			$query = 'UPDATE #__slvendor_products'
				. ' SET published=0, server_id=0'
				. ' WHERE server_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
}
?>
