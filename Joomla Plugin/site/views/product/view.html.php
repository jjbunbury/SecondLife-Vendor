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

jimport('joomla.application.component.view');

/**
 * HTML View class for the SlVendor component
 *
 * @static
 * @package	SlVendor
 * @since 1.0
 */
class SlvendorViewProduct extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		$user			= &JFactory::getUser();
		$pathway		= &$mainframe->getPathway();
		$document	= & JFactory::getDocument();
		$model			= &$this->getModel();
		$db 				= & JFactory::getDBO();

		// Get the parameters of the active menu item
		$menus	= &JSite::getMenu();
		$menu    = $menus->getActive();

		// Push a model into the view
		$model		= &$this->getModel();
		$modelCat	= &$this->getModel('Category');

		// Selected Request vars
		// ID may come from the product switcher
		if (!($productId	= JRequest::getInt('product_id',	0))) {
			$productId	= JRequest::getInt('id', $productId);
		}

		// query options
		$options['id']	= $productId;
		$options['aid']	= $user->get('aid', 0);

		$product	= $model->getData($options);

		// check if we have a product
		if (!is_object($product)) {
			JError::raiseError(404, 'Product not found');
			return;
		}

		// Set the document page title
		$document->setTitle(JText::_('PRODUCT').' - '.$product->name);

		//set breadcrumbs
		if (isset($menu) && isset($menu->query['view']) && $menu->query['view'] != 'product'){
			$pathway->addItem($product->name, '');
		}

		// define if we are using global icon
		$product_params = new JParameter($product->params);
		$product->image['attr'] = "width=".$product_params->get('icon_size', 32)."px";
		switch ($product_params->get('icon_type', "texture")) {
			case "icon":
				$product->image['url'] = 'images/M_images/'.$product_params->get('icon_icon');
				break;
			case "image":
				$product->image['url'] = 'images/stories/'.$product_params->get('icon_image');
				break;
			case "texture":
				$product->image['attr'] = "100px";
				$product->image['url'] = "http://secondlife.com/app/image/".$product->texture_uuid."/1";
				break;
		}

		// get the perms
		switch($product_params->get('perms', 0)) {
			case 0:
				$product->perms = JText::_('Copy / Modify / Transfer');
				break;
			case 1:
				$product->perms = JText::_('No Transfer');
				break;
			case 2:
				$product->perms = JText::_('No Modify');
				break;
			case 3:
				$product->perms = JText::_('No Copy');
				break;
			case 4:
				$product->perms = JText::_('No Modify / No Transfer');
				break;
			case 5:
				$product->perms = JText::_('No Copy / No Transfer');
				break;
			case 6:
				$product->perms = JText::_('No Copy / No Modify');
				break;
			case 7:
				$product->perms = JText::_('No Copy / No Modify / No Transfer');
				break;
		}

		// check if user is logged in
		$user_id = $user->get('id');
		if ($product->price == 0) {
			if ($user_id > 0) {

				// check if slcontact_xt is installed
				$query = "SELECT id" .
						" FROM #__components" .
						" WHERE `option`='com_slcontact_xt' AND `enabled`=1";
				$db->setQuery($query);
				$slcontact_xt_exists = $db->loadResult();
				// $slcontact_xt_exists = JComponentHelper::isEnabled('slcontact_xt');
				if ($slcontact_xt_exists) {
					// check if the user has a contact
					$query = "SELECT id FROM #__slcontact_xt"
								." WHERE user_id=". $user_id;
					$db->setQuery($query);
					$contact = $db->loadResult();
					if ($contact) {
						$link = JRoute::_('index.php?option=com_slvendor&controller=inworld&task=requestProductFromWeb&ref_view=product&product_id='. $product->id);
						$this->assign('download_link', $link);
					}
				}
			}
		}

		// get the slcontact model
		$this->assignRef('product', $product);

		parent::display($tpl);
	}
}
?>