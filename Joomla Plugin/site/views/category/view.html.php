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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the SlVendor component
 *
 * @static
 * @package		SlVendor
 * @since 1.0
 */
class SlvendorViewCategory extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;

		// Initialize some variables
		$document	= &JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$pathway	= &$mainframe->getPathway();

		// Get the parameters of the active menu item
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();

		// Get some data from the model
		$items		= &$this->get('data' );
		$total		= &$this->get('total');
		$pagination	= &$this->get('pagination');
		$category	= &$this->get('category' );
		$category->total = $total;
		$state		= &$this->get('state');

		// Get the page/component configuration
		$global_params = &$mainframe->getParams('com_slvendor');

		// Set page title per category
		$document->setTitle( $category->title. ' - '. $global_params->get( 'page_title'));

		//set breadcrumbs
		if(is_object($menu) && $menu->query['view'] != 'category') {
			$pathway->addItem($category->title, '');
		}

		// table ordering
		$lists['order_Dir'] = $state->get('filter_order_dir');
		$lists['order'] = $state->get('filter_order');

		// Set some defaults if not set for params
		$global_params->def('com_description', JText::_('SLVENDOR DESC'));
		// Define image tag attributes
		if (isset( $category->image ) && $category->image != '')
		{
			$attribs['align']  = $category->image_position;
			$attribs['hspace'] = 6;

			// Use the static HTML library to build the image tag
			$category->image = JHTML::_('image', 'images/stories/'.$category->image, JText::_('SLVENDOR'), $attribs);
		}

		// icon in table display
		$icon_size = $global_params->get( 'icon_size', 32 );
		$icon_size = ($icon_size > 32) ? 32 : $icon_size;
		$image['attr'] = "width=".$icon_size."px";
		if ( $global_params->get( 'icon_type' ) == "icon" ) {
			$image['url'] = 'images/M_images/'.$global_params->get( 'icon_icon' );
		}
        else if ( $global_params->get( 'icon_type' ) == "image" ) {
			$image['url'] = 'images/stories/'.$global_params->get( 'icon_image' );
		}

		$k = 0;
		$count = count($items);
		for($i = 0; $i < $count; $i++)
		{
			$item =& $items[$i];

			$item->link = JRoute::_( 'index.php?option=com_slvendor&view=product&catid='.$category->slug.'&id='. $item->slug);

			$menuclass = 'category'.$global_params->get( 'pageclass_sfx' );

			// define if we are using global icon
			$product_params = new JParameter($item->params);
			if ( $global_params->get( 'icon_type' ) == "texture" ) {
				$image['url'] = "http://secondlife.com/app/image/".$item->texture_uuid."/1";
			}
			$icon_size = $product_params->get( 'icon_size', 32 );
			$icon_size = ($icon_size > 32) ? 32 : $icon_size;
			$image['attr'] = "width=".$icon_size."px";
			if ( $product_params->get( 'icon_type' ) == "icon" ) {
				$image['url'] = 'images/M_images/'.$product_params->get( 'icon_icon' );
			}
			else if ( $product_params->get( 'icon_type' ) == "image" ) {
				$image['url'] = 'images/stories/'.$product_params->get( 'icon_image' );
			}
			if ( $product_params->get( 'icon_type' ) == "texture" ) {
				$image['url'] = "http://secondlife.com/app/image/".$item->texture_uuid."/1";
			}
			
			$item->image = JHTML::image($image['url'], 'Product', $image['attr'] );

			$item->odd		= $k;
			$item->count	= $i;
			$k = 1 - $k;
		}

		$this->assignRef('lists',		$lists);
		$this->assignRef('params',		$global_params);
		$this->assignRef('category',	$category);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);

		$this->assign('action',	$uri->toString());

		parent::display($tpl);
	}
}
?>
