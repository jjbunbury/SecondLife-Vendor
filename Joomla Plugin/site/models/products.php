<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Products Model
 *
 * @package    SlVendor
 */
class SlvendorModelProducts extends JModel
{
	/**
	 * Category ata array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Category total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		// Get the pagination request variables
		$limit		= 10;
		$limitstart	= JRequest::getVar('start',  "", 'post');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Method to get products item data
	 *
	 * @access public
	 * @return array
	 */
	function getData( $data )
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery( $data );
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	/**
	 * Method to get the total number of product items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal( $data )
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery( $data );
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	function _buildQuery( $data )
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere( $data );
		$orderby	= $this->_buildContentOrderBy( $data );

		$query = " SELECT p.*"
			. " FROM #__slvendor_products AS p "
			. " LEFT JOIN #__categories AS cc ON cc.section='com_slvendor'"
			. $where
			. $orderby
		;

		return $query;
	}

	function _buildContentOrderBy( $data )
	{
		$filter_order		= $data['order'];
		$filter_order_Dir	= $data['order_dir'];

		if ($filter_order == 'ordering')
		{
			$orderby 	= ' ORDER BY cc.ordering, p.ordering '.$filter_order_Dir;
		}
		else if ( $filter_order == 'name' )
		{
			$orderby 	= ' ORDER BY p.name '.$filter_order_Dir;
		}
		else if ( $filter_order == 'price' )
		{
			$orderby 	= ' ORDER BY p.price '.$filter_order_Dir;
		}

		return $orderby;
	}

	function _buildContentWhere( $data )
	{
		$where = array();
		if ( $data['category'] != "all" )
		{
			$where[] = "cc.title='".$data['category']."'";
		}
		$where[] = 'cc.published = 1';
		$where[] = 'p.catid=cc.id';
		$where[] = 'p.published = 1';
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}
	/**
	 * Method to delete all the products from a server
	 *
	 * @access public
	 * @return integer
	 */
	 function deleteProducts( $data )
	 {
		$query="DELETE FROM p USING #__slvendor_products AS p"
						." LEFT JOIN #__slvendor_servers AS s ON s.uuid='".$data['server_key']."'"
						." WHERE p.server_id=s.id";
		$this->_db->setQuery( $query );
		return $this->_db->query();
	 }
}
?>