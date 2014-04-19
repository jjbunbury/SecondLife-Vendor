<?php
/**
 * SlVendor default controller
 * 
 * @package    SlVendor
 * @link http://joomlacode.org/gf/project/slvendor
 * @license		GNU/GPL
 */

jimport('joomla.application.component.controller');

/**
 * SlVendor Component Controller
 *
 * @package    SlVendor
 */
class SlvendorControllerServers extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);

		// Register Extra tasks
		$this->registerTask( 'edit', 'display' );
	}

	function display( )
	{
		switch($this->getTask())
		{
			case 'edit'    :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view'  , 'server');
				JRequest::setVar( 'edit', true );

				// Checkout the product
				$model = $this->getModel('server');
			} break;
			default:
			{
				JRequest::setVar( 'view'  , 'server');
			}break;
		}
		parent::display();
	}

		function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT AN ITEM TO DELETE' ) );
		}

		$model = $this->getModel('server');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_slvendor&view=servers' );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		// Checkin the product
		$model = $this->getModel('server');

		$this->setRedirect( 'index.php?option=com_slvendor&view=servers' );
	}
}
?>
