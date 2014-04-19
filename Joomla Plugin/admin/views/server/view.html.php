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
 * Server View
 *
 * @package    SlVendor
 */
class SlvendorViewServer extends JView
{
	/**
	 * display method of server view
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
		$model	=& $this->getModel();

		//get the server
		$server	=& $this->get('data');
		
		$document =& JFactory::getDocument();
		$document->addScript(JURI::root(true).'/components/com_slvendor/helpers/slvendor.js');

		$this->assign('action', 	$uri->toString());
		$this->assignRef('server',		$server);

		parent::display($tpl);
	}
}
?>