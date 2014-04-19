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
class SlvendorControllerProducts extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);

		// Register Extra tasks
		$this->registerTask( 'add',  'display' );
		$this->registerTask( 'edit', 'display' );
	}

	function display( )
	{
		switch($this->getTask())
		{
			case 'add'     :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view'  , 'product');
				JRequest::setVar( 'edit', false );

				// Checkout the product
				$model = $this->getModel('product');
				$model->checkout();
			} break;
			case 'edit'    :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view'  , 'product');
				JRequest::setVar( 'edit', true );

				// Checkout the product
				$model = $this->getModel('product');
				$model->checkout();
			} break;
			default:
			{
				JRequest::setVar( 'view'  , 'products');
			}break;
		}

		parent::display();
	}

	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$post	= JRequest::get('post',2);
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];

		$model = $this->getModel('product');

		if ($model->store($post)) {
			$msg = JText::_( 'PRODUCT SAVED' );
		} else {
			$msg = JText::_( 'ERROR SAVING PRODUCT' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$link = 'index.php?option=com_slvendor&view=products';
		$this->setRedirect($link, $msg);
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

		$model = $this->getModel('product');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_slvendor&view=products' );
	}

	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT AN ITEM TO PUBLISH' ) );
		}

		$model = $this->getModel('product');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_slvendor&view=products' );
	}

	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT AN ITEM TO UNPUBLISH' ) );
		}

		$model = $this->getModel('product');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_slvendor&view=products' );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		// Checkin the product
		$model = $this->getModel('product');
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_slvendor&view=products' );
	}

	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$model = $this->getModel('product');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_slvendor&view=products');
	}

	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$model = $this->getModel('product');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_slvendor&view=products');
	}

	function saveorder()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('product');
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$this->setRedirect( 'index.php?option=com_slvendor&view=products', $msg );
	}
}
?>
