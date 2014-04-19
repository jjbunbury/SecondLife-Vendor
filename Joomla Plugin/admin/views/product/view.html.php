<?php
/**
 * Product for SlVendor Component
 * 
 * @package    SlVendor
 * @link http://joomlacode.org/gf/project/slvendor
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Product View
 *
 * @package    SlVendor
 */
class SlvendorViewProduct extends JView
{
	/**
	 * display method of product view
	 * @return void
	 **/
	function display($tpl = null)
	{
		global $mainframe;

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe, $option;

		$db		=& JFactory::getDBO();
		$uri 		=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();

		$lists = array();

		//get the product
		$product	=& $this->get('data');
		$isNew		= ($product->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'THE PRODUCT' ), $product->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Edit or Create?
		if (!$isNew)
		{
			$model->checkout( $user->get('id') );
		}
		else
		{
			// initialise new record
			$product->published = 1;
			$product->approved 	= 1;
			$product->order 	= 0;
			$product->catid 	= JRequest::getVar( 'catid', 0, 'post', 'int' );
		}

		// build the html select list for ordering
		$query = 'SELECT ordering AS value, name AS text'
			. ' FROM #__slvendor_products'
			. ' WHERE catid = ' . (int) $product->catid
			. ' ORDER BY ordering';

		$lists['ordering'] 			= JHTML::_('list.specificordering',  $product, $product->id, $query, 1 );

		// build list of categories
		$lists['catid'] 			= JHTML::_('list.category',  'catid', $option, intval( $product->catid ) );
		// build the html select list
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $product->published );
		
		$document =& JFactory::getDocument();
		$document->addScript(JURI::root(true).'/components/com_slvendor/helpers/slvendor.js');

		// get the params model
		$file 	= JPATH_COMPONENT.DS.'models'.DS.'product.xml';
		$params = new JParameter( $product->params, $file );

		$this->assign('action', 	$uri->toString());
		$this->assignRef('lists',		$lists);
		$this->assignRef('product',		$product);
		$this->assignRef('params',		$params);

		parent::display($tpl);
	}
}
?>