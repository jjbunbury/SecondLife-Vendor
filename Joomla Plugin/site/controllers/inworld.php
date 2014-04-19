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

jimport('joomla.application.component.controller');
define('PRODUCT_SEPARATOR', '|');
define('PARAM_SEPARATOR', ';');
define('NOTECARD_SUFFIX', '.info');
define('SECONDLIFE_LINDEN_SERVERS', '8.2.32.0/22,63.210.156.0/22,64.129.40.0/22,64.129.44.0/22,64.154.220.0/22,8.4.128.0/22,8.10.144.0/21,216.82.0.0/18');

class SlvendorControllerInworld extends JController {

	var $allowed = FALSE;
	var $values = array();
	var $post = array();
	var $log_separator = ";";

	/**
	* Constructor
	*/
	function __construct() {
		parent::__construct();

		// build the view
		JRequest::setVar('view', 'inworld');
		$document = &JFactory::getDocument();
		$doc =& JDocument::getInstance('raw');
		$document = $doc;
		$this->view = &$this->getView('inworld', 'raw');

		// check if access is allowed
		$this->checkAccess();
		if ($this->allowed) {
			// get values
			$this->getValues();
			// Register Extra tasks
			$this->registerTask( 'updateServer', 'updateServer' );
			$this->registerTask( 'requestProductsList', 'requestProductsList' );
			$this->registerTask( 'requestUpdate', 'requestUpdate' );
			$this->registerTask( 'requestProduct', 'requestProduct' );
			$this->registerTask( 'clearProducts', 'clearProducts' );
			$this->registerTask( 'updateProducts', 'updateProducts' );
		}
		$this->registerTask('requestProductFromWeb', 'requestProductFromWeb');
		$this->registerTask('checkServer', 'checkServer');
		$this->registerTask('clearLog', 'clearLog');
	}

	/**
	* Render output
	*/
	function render($output) {
		$this->view->assign('output', $output);
		parent::display();
	}

	/**
	* Check allowed access
	*/
	function checkAccess() {
		// get params
		$params = &JComponentHelper::getParams( 'com_slvendor' );
		$this->allowed = FALSE;
		$ll_subnets = explode(",", $params->get('allowed_ips', SECONDLIFE_LINDEN_SERVERS));
		$remote_addr = $_SERVER['REMOTE_ADDR'];
		if ($params->get('use_proxy', 0)) {
			$tempa = split(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			$remote_addr = trim($tempa[1]);
		}
		foreach($ll_subnets as $network) {
			if($this->checkNetmatch($network, $remote_addr)) {
				$this->allowed = TRUE;
				break;
			}
		}
	}

	/**
	* Check the ip.
	* Authors: Falados Kapuskas, JoeTheCatboy Freelunch
	*/
	function checkNetmatch($network, $ip) {
		// determines if a network in the form of 192.168.17.1/16 or
		// 127.0.0.1/255.255.255.255 or 10.0.0.1 matches a given ip
		$ip_arr = explode('/', $network);
		$network_long = ip2long($ip_arr[0]);

		$x = ip2long($ip_arr[1]);
		$mask =  long2ip($x) == $ip_arr[1] ? $x : 0xffffffff << (32 - $ip_arr[1]);
		$ip_long = ip2long($ip);

		return ($ip_long & $mask) == ($network_long & $mask);
	}

	/**
	* Get values
	*/
	function getValues() {

		// get post data
		$this->post	= JRequest::get('POST',4);

		// get server values
		$this->post['object_key'] 		= $_SERVER['HTTP_X_SECONDLIFE_OBJECT_KEY'];
		$this->post['uuid']				= $this->post['object_key'];
		$this->post['object_name'] 	= $_SERVER['HTTP_X_SECONDLIFE_OBJECT_NAME'];
		$this->post['owner_key'] 		= $_SERVER['HTTP_X_SECONDLIFE_OWNER_KEY'];
		$this->post['owner_name'] 	= $_SERVER['HTTP_X_SECONDLIFE_OWNER_NAME'];
		$this->post['region'] 			= $_SERVER['HTTP_X_SECONDLIFE_REGION'];
		$this->post['position'] 			= $_SERVER['HTTP_X_SECONDLIFE_LOCAL_POSITION'];

		preg_match_all('/(.*) \((\d+), (\d+)\)/', $this->post['region'], $temp);
		$this->post['region_name'] 	= $temp[1][0];
		$this->post['region_x'] 			= $temp[2][0];
		$this->post['region_y'] 			= $temp[3][0];

		preg_match_all('/\((.*), (.*), (.*)\)/', $this->post['position'], $temp);
		$this->post['position_x']  = $temp[1][0];
		$this->post['position_y']  = $temp[2][0];
		$this->post['position_z']  = $temp[3][0];

		preg_match_all('/\((.*)\\d{4}, (.*)\\d{4}, (.*)\\d{4}\)/is', $this->post['position'], $temp);
		$this->post['short_position'] = $temp[1][0].','.$temp[2][0].','.$temp[3][0];
	}

	/**
	* Check password
	*/
	function checkPassword($password, $key) {
		// check the password
		$params = &JComponentHelper::getParams( 'com_slvendor' );
		if ($password != md5($params->get('password').":".$key)) {
			$this->render('error'.PARAM_SEPARATOR.JText::_('WRONG PASSWORD'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	* Update server
	*/
	function updateServer() {

		// check password
		if (!$this->checkPassword($this->post['password'], $this->post['key'])) {
			return;
		}

		// get the server model
		$server_model = $this->getModel('server');
		// check if server exists
		$this->post['id'] = &$server_model->getServerId($this->post);
		// store the server
		$this->post['region'] = $this->post['region_name'];
		$this->post['position'] = $this->post['short_position'];
		$msg = $server_model->store($this->post);
		if ($msg != false) {
			$output = "server updated".PARAM_SEPARATOR.$msg;
		}
		else {
			$output = "error".PARAM_SEPARATOR.JText::_("ERROR SAVING SERVER");
		}
		$this->render($output);
		return;
	}

	/**
	* Check server
	*/
	function checkServer() {

		// get the xml-rpc helper
		require_once (JPATH_COMPONENT.DS.'helpers'.DS.'lslrpc.inc');

		// get values
		$uuid = JRequest::getVar('uuid',  "", 'get');
		$sleep = JRequest::getVar('sleep',  0, 'get');

		// get the server model
		$model = $this->getModel('server');
		// check if server exists
		$model->_id = &$model->getServerId(array('uuid'=>$uuid));
		// get the server values
		$server = &$model->getData();
		if (!$server) {
			$this->render(JText::_('SERVER NOT REGISTERED'));
			return;
		}
		//sleep($sleep);
		$result = sl_rpc($server->data_channel, 70010, "check server");
		if ($result["string"] == "online") {
			$output = "online";
		}
		else {
			$output = "offline";
		}
		$this->render($output);
		return;
	}

	/**
	* Request products list
	*/
	function requestProductsList() {

		// check password
		if (!$this->checkPassword($this->post['password'], $this->post['key'])) {
			return;
		}

		// get the products model
		$model = $this->getModel('products');
		// get the products
		$products = $model->getData($this->post);
		$output = array();
		$output[] = "products".PARAM_SEPARATOR.$model->getTotal($this->post).PARAM_SEPARATOR.$this->post['start'];
		foreach ($products as $product) {
			// get the product params
			$product_params = new JParameter($product->params);
			// build the output
			$output[] = $product->name.PARAM_SEPARATOR // 0
								.$product->texture_uuid.PARAM_SEPARATOR // 1
								.$product->price.PARAM_SEPARATOR // 2
								.$product->id.PARAM_SEPARATOR // 3
								.$product->short_desc.PARAM_SEPARATOR //4
								.$product_params->get('perms', 0); //5
		}
		$this->render(implode(PRODUCT_SEPARATOR,$output));
		return;
	}

	/**
	* Request update
	*/
	function requestUpdate() {

		// get values
		$this->post['request_type'] = "update";

		// check password
		if (!$this->checkPassword($this->post['password'], $this->post['key'])) {
			return;
		}

		// get the product model
		$product_model = $this->getModel("product");
		// check if product is available
		$product_data = &$product_model->getProductByName($this->post);
		if (!$product_data->id) {
			$varsStr = $this->post['product_name'].PARAM_SEPARATOR // 3
			.$this->post['product_object_name'].PARAM_SEPARATOR // 4
			.$this->post['user_name'].PARAM_SEPARATOR // 5
			.$this->post['user_key'].PARAM_SEPARATOR // 6
			.$this->post['price'].PARAM_SEPARATOR // 7
			.$this->post['object_name'].PARAM_SEPARATOR // 8
			.$this->post['region'].PARAM_SEPARATOR // 9
			.$this->post['position'].PARAM_SEPARATOR // 10
			.$this->post['region_name'].PARAM_SEPARATOR // 11
			.$this->post['owner_name'].PARAM_SEPARATOR // 12
			.$this->post['owner_key']; // 13
			// writing the log file
			$this->writeLog(gmdate('Y-m-d H:i:s').PARAM_SEPARATOR // 0
				."missing on website".PARAM_SEPARATOR // 1
				.$this->post['request_type'].PARAM_SEPARATOR // 2
				.$varsStr);
			$this->render("no product available".PARAM_SEPARATOR.$this->post['password'].PARAM_SEPARATOR.$this->post['key'] .PARAM_SEPARATOR.JText::_('NO PRODUCT AVAILABLE'));
			return;
		}
		// check the version
		if ($this->post['version'] == $product_data->version) {
			$this->render("no update available".PARAM_SEPARATOR.$this->post['password'].PARAM_SEPARATOR.$this->post['key'].PARAM_SEPARATOR.JText::_('NO UPDATE AVAILABLE'));
			return;
		}
		// send the product
		$this->post['product_object_name'] = $product_data->object_name;
		$this->render($this->sendProduct());
		return;
	}

	/**
	* Request product
	*/
	function requestProduct() {

		switch ($this->post['type']) {
			case 6: // object
				$this->post['item_type'] = "object";
				break;
			case 7: // notecard
				$this->post['item_type'] = "notecard";
				break;
		}

		// check password
		if (!$this->checkPassword($this->post['password'], $this->post['key'])) {
			return;
		}

		// get the product model
		$product_model = $this->getModel("product");
		// check if product is available
		$product_data = &$product_model->getProductByName($this->post);
		if (!$product_data->id) {
				$varsStr = $this->post['product_name'].PARAM_SEPARATOR // 3
				.$this->post['type'].PARAM_SEPARATOR // 4
				.$this->post['product_object_name'].PARAM_SEPARATOR // 5
				.$this->post['user_name'].PARAM_SEPARATOR // 6
				.$this->post['user_key'].PARAM_SEPARATOR // 7
				.$this->post['price'].PARAM_SEPARATOR // 8
				.$this->post['object_name'].PARAM_SEPARATOR // 9
				.$this->post['region'].PARAM_SEPARATOR // 10
				.$this->post['short_position'].PARAM_SEPARATOR // 11
				.$this->post['owner_name'].PARAM_SEPARATOR // 12
				.$this->post['owner_key']; // 13
				// writing the log file
				$this->writeLog(gmdate('Y-m-d H:i:s').PARAM_SEPARATOR
					."missing on website".PARAM_SEPARATOR
					.$this->post['request_type'].PARAM_SEPARATOR
					.$varsStr);
			$this->render("missing on website".PARAM_SEPARATOR.$this->post['password'].PARAM_SEPARATOR.$this->post['key'].PARAM_SEPARATOR.$varsStr);
			return;
		}
		// get the product
		$this->post['product_object_name'] = $product_data->object_name;
		$this->post['price'] = $product_data->price;
		// send the object
		$this->render($this->sendProduct());
		return;
	}

	/**
	* Request product
	*/
	function requestProductFromWeb() {

		// get database object
		$db =& JFactory::getDBO();

		// get data
		$this->get = JRequest::get('GET', 4);

		// build the redirection route
		$route = JRoute::_('index.php?option=com_slvendor&view='.$this->get['ref_view'].'&id='.$this->get['product_id']);

		// check if the user is logged in
		$user = &JFactory::getUser();
		$user_id = $user->get('id');
		if ($user_id == 0) {
			$msg = JText::_( 'YOU MUST BE LOGGUED');
			JController::setRedirect($route, $msg);
			JController::redirect();
		}

		// check if slcontact_xt is enabled
		$query = "SELECT id" .
					" FROM #__components" .
					" WHERE `option`='com_slcontact_xt' AND `enabled`=1";
		$db->setQuery($query);
		$slcontact_xt_exists = $db->loadResult();
		if (!$slcontact_xt_exists) {
			$msg = JText::_( 'SLCONTACT_XT IS MISSING');
			JController::setRedirect($route, $msg);
			JController::redirect();
		}

		// check if the user has a contact
		$query = "SELECT * FROM #__slcontact_xt"
					." WHERE user_id=". $user_id;
		$db->setQuery($query);
		$contact = $db->loadObject();
		if (!$contact) {
			$msg = JText::_( 'YOUR AVATAR IS NOT REGISTERED ON THIS WEBSITE');
			JController::setRedirect($route, $msg);
			JController::redirect();
		}

		// get type
		$this->post['request_type'] = "website request";
		$this->post['type'] = $this->get['type'];
		switch ($this->post['type']) {
			case 6: // object
				$this->post['item_type'] = "object";
				break;
			case 7: // notecard
				$this->post['item_type'] = "notecard";
				break;
		}

		// get the product model
		$product_model = $this->getModel("product");
		$product_model->setId($this->get['product_id']);

		// check if product is available
		$product_data = &$product_model->getData();
		if (!$product_data->id) {
				$varsStr = $this->get['product_name'].PARAM_SEPARATOR // 3
				.$this->get['type'].PARAM_SEPARATOR // 4
				.''.PARAM_SEPARATOR // 5
				.$contact->user_name.PARAM_SEPARATOR // 6
				.$contact->user_key; // 7
				// writing the log file
				$this->writeLog(gmdate('Y-m-d H:i:s').PARAM_SEPARATOR // 0
					."missing on website".PARAM_SEPARATOR // 1
					.$this->post['request_type'].PARAM_SEPARATOR // 2
					.$varsStr);
			$msg = JText::_( 'OBJECT MISSING ON WEBSITE');
			JController::setRedirect($route, $msg);
			JController::redirect();
		}

		// check if product is free
		if ($product_data->price != 0) {
			$msg = JText::_( 'THIS PRODUCT IS NOT FOR FREE');
			JController::setRedirect($route, $msg);
			JController::redirect();
		}

		// get the config
		$config =& JFactory::getConfig();
		$cparams = JComponentHelper::getParams ('com_slvendor');

		// filling values
		$this->post['password'] = md5($cparams->get('password').":1234");
		$this->post['key'] = '1234';
		$this->post['user_key'] = $contact->user_key;
		$this->post['user_name'] = $contact->user_name;
		$this->post['product_name'] = $product_data->product_name;
		$this->post['product_object_name'] = $product_data->object_name;

		// send the object
		$this->sendProduct();
		switch ($this->post['response']) {
			case 'sending':
				$msg = JText::_( 'SERVER IS SENDING OBJECT');
				break;
			case 'missing in server':
				$msg = JText::_( 'OBJECT MISSING IN SERVER');
				break;
			default:
				$msg = JText::_( 'ERROR SENDING OBJECT');
				break;
		}
		JController::setRedirect($route, $msg);
		JController::redirect();
	}

	/**
	* Send product
	*/
	function sendProduct() {
		// get database object
		$db		=& JFactory::getDBO();
        // get component params
        $params = &JComponentHelper::getParams( 'com_slvendor' );
		// vars to string
		$varsStr = $this->post['product_name'].PARAM_SEPARATOR // 3
				.$this->post['type'].PARAM_SEPARATOR // 4
				.$this->post['product_object_name'].PARAM_SEPARATOR // 5
				.$this->post['user_name'].PARAM_SEPARATOR // 6
				.$this->post['user_key'].PARAM_SEPARATOR // 7
				.$this->post['price'].PARAM_SEPARATOR // 8
				.$this->post['object_name'].PARAM_SEPARATOR // 9
				.$this->post['region'].PARAM_SEPARATOR // 10
				.$this->post['short_position'].PARAM_SEPARATOR // 11
				.$this->post['owner_name'].PARAM_SEPARATOR // 12
				.$this->post['owner_key']; // 13
				// get the server values
		$query = "SELECT s.* FROM #__slvendor_servers AS s"
			." LEFT JOIN #__slvendor_products AS p ON p.object_name='".$this->post['product_object_name']."'"
			." WHERE s.id=p.server_id";
		$db->setQuery($query);
		$SLVserver = $db->loadObject();
		$this->post['response'] = "";
		if ($SLVserver) {
			// get notecard name
			switch ($this->post['type']) {
				case 7: // notecard
					$this->post['product_object_name'] .= $params->get('notecard_suffix', NOTECARD_SUFFIX);
					break;
			}
			// update the vars string
			$varsStr .= 	PARAM_SEPARATOR.$SLVserver->name.PARAM_SEPARATOR // 14
						.$SLVserver->region.PARAM_SEPARATOR // 15
						.$SLVserver->position; // 16
			// call the server inworld
			require_once (JPATH_COMPONENT.DS.'helpers'.DS.'lslrpc.inc');
			$result = sl_rpc($SLVserver->data_channel, 70091, // 70091 = give object
									$this->post['password'].PARAM_SEPARATOR
									.$this->post['key'].PARAM_SEPARATOR
									.$this->post['user_key'].PARAM_SEPARATOR
									.$this->post['product_object_name'].PARAM_SEPARATOR
									.$this->post['request_type']);
			if ($result["string"] == "sending" || $result["string"] == "missing in server") {
				$this->post['response'] = $result["string"];
			}
			else {
				$this->post['response'] = "server offline";
				$this->post['response'] = $result["string"];
			}
		}
		else {
			$this->post['response'] = "server missing on website";
		}
		// writing the log file
		$this->writeLog(gmdate('Y-m-d H:i:s').PARAM_SEPARATOR // 0
					.$this->post['response'].PARAM_SEPARATOR // 1
					.$this->post['request_type'].PARAM_SEPARATOR // 2
					.$varsStr);
		// return the result inworld
		return $this->post['response'].PARAM_SEPARATOR // 0
			.$this->post['password'].PARAM_SEPARATOR // 1
			.$this->post['key'].PARAM_SEPARATOR // 2
			.$varsStr;
	}

	/**
	* Clear products
	*/
	function clearProducts() {

		// check password
		if (!$this->checkPassword($this->post['password'], $this->post['key'])) {
			return;
		}
		// get the product model
		$model = $this->getModel("products");
		// delete products
		if ($model->deleteProducts($this->post)) {
			$this->render(JText::_('OBJECTS DELETED'));
			return;
		}
		$this->render(JText::_('ERROR DELETING OBJECTS'));
		return;
	}

	/**
	* Update products
	*/
	function updateProducts() {

		// check password
		if (!$this->checkPassword($this->post['password'], $this->post['key'])) {
			return;
		}

		// get the server model
		$server_model = $this->getModel('server');

		// load database object
		$db		=& JFactory::getDBO();

		// get params
		$params = &JComponentHelper::getParams( 'com_slvendor' );

		// check if server exists
		$server = array();
		$server['name'] = $this->post['object_name'];
		$server['uuid'] = $this->post['uuid'];
		$server['region'] = $this->post['region_name'];
		$server['position'] = $this->post['short_position'];
		$server['id'] = &$server_model->getServerId($server);

		// store the server
		if (!$server_model->store($server)) {
			$this->render("error".PARAM_SEPARATOR.JText::_("ERROR SAVING SERVER"));
			return;
		}
		$server['id'] = $server_model->_id;

		// check if default category exists
		$query = "SELECT id FROM #__categories"
			." WHERE section='com_slvendor' AND title='".$params->get('default_cat_name', 'default')."'";
		$db->setQuery($query);
		$default_category_id = $db->loadResult();

		// create category if not exists
		if (!$default_category_id) {
			$category_name = $params->get('default_cat_name', 'default');
			$category = new stdclass;
			$category->section = "com_slvendor";
			$category->title = $category_name;
			$category->name = $category_name;
			$category->alias = $category_name;
			$category->published = 1;
			$db->insertObject("#__categories", $category);
			$default_category_id = $db->insertid();
		}

		// split names
		$output = "\n";
		$version_separator = $params->get('version_separator', '-');
		$objects_names_arr = explode(PRODUCT_SEPARATOR, $this->post['objects_names']);
		$texures_uuids_arr = explode(PRODUCT_SEPARATOR, $this->post['textures']);
		$products_names_arr = array();

		// split names and version as : productname-version
		foreach($objects_names_arr as $k => $object_name) {
			$product = array();
			$product['object_name'] = $object_name;
			$product['catid'] = $default_category_id;
			$product['server_id'] = $server['id'];
			$product['texture_uuid'] = $texures_uuids_arr[$k];
			$product_arr = explode($version_separator, $object_name);
			$product['name'] = $product_arr[0];
			$product['product_name'] = $product['name'];
			$products_names_arr[] = $product['name'];
			if (count($product_arr) == 1 || $product_arr[1] == "") {
				$output .= JText::_('NO VERSION FOR THIS PRODUCT')." : ".$product['name']." \n";
				continue;
			}
			else {
				$product['version'] = $product_arr[1];
			}

			// get the product model
			$product_model = $this->getModel('product');
			// get product values if exists
			$product_data = &$product_model->getProductByName($product);
			$product['id'] = $product_model->_data->id;
			// store the product
			$stored_product = $product_model->store($product);
			if ($stored_product) {
				$output .= $stored_product;
			}
		}

		// check if an object was deleted
		$query = "SELECT name FROM #__slvendor_products"
			." WHERE server_id='".$server['id']."'";
		$db->setQuery($query);
		$existing_products_names = $db->loadResultArray();
		$diff = array_diff($existing_products_names, $products_names_arr);
		if (count($diff) != 0) {
			$deletedProducts = implode("','",$diff);
			$query = "DELETE FROM #__slvendor_products WHERE name IN ('".$deletedProducts."') AND server_id='".$server['id']."'";
			$db->setQuery($query);
			$db->query();
			$output .= JText::_('REMOVED')." : ".$deletedProducts;
		}
		$this->render($output);
		return;
	}

	/**
	* Write log
	*/
	function writeLog($text = "") {
		// get params
		$params = &JComponentHelper::getParams( 'com_slvendor' );
		$logfilename 	= $params->get('logfilename');
		$logseparator 	= $params->get('logseparator', ';');
		$use_log_file 	= $params->get('use_log_file', 1);

		// check if config allows the use of log file
		if (!$use_log_file) {
			return;
		}

		// get the file name
		$config =& JFactory::getConfig();
		$fileName = $config->getValue('log_path'). DS. $logfilename;

		// check if the log dir is writable
		if (!is_writable($config->getValue('log_path'))) {
			return;
		}

		// check if the log file exists
		$exists = is_file($fileName);

		// check if the log file is writable
		if ($exists && !is_writable($fileName)) {
			return;
		}

		$file = fopen($fileName, 'a+');
		if (!$exists) {
			$headings = "date;" // 0
						."type of transaction;" // 1
						."object or update;" // 2
						."product name;" // 3
						."item name;" // 4
						."item type;" // 5
						."user name;" // 6
						."user key;" // 7
						."price;" // 8
						."vendor name;" // 9
						."vendor region;" // 10
						."vendor position;" // 11
						."vendor owner name;" // 12
						."vendor owner key;" // 13
						."server name;" // 14
						."server region;" // 15
						."server position"; // 16
			fputcsv($file, split(';', $headings), $logseparator);
		}
		fputcsv($file, split(PARAM_SEPARATOR, $text), $logseparator);
		fclose($file);
	}

	/**
	* Clear log
	*/
	function clearLog() {
		// get params
		$params = &JComponentHelper::getParams( 'com_slvendor' );
		$password 		= $params->get('password');
		$logfilename 	= $params->get('logfilename');
		$logseparator 	= $params->get('logseparator');

		// check for the hash
		$config =& JFactory::getConfig();
		if (md5($config->getValue('secret').":".$password) != JRequest::getVar('hash',  "", 'get')) {
			$this->render("<script> alert('".JText::_('ERROR')."'); window.history.go(-1); </script>\n");
			return;
		}

		// check if the filename exists
		if ($logfilename == "") {
			$this->render("<script> alert('".JText::_('NO LOG FILE')."'); window.history.go(-1); </script>\n");
			return;
		}

		// get the file name
		$fileName = $config->getValue('log_path'). DS. $logfilename;

		// clear the log
		$file = fopen($fileName, 'w');
		$headings = "date".PARAM_SEPARATOR // 0
				."type of transaction".PARAM_SEPARATOR // 1
				."object or update".PARAM_SEPARATOR // 2
				."item name".PARAM_SEPARATOR // 3
				."item type".PARAM_SEPARATOR // 4
				."object name".PARAM_SEPARATOR // 5
				."user name".PARAM_SEPARATOR // 6
				."user key".PARAM_SEPARATOR // 7
				."price".PARAM_SEPARATOR // 8
				."vendor name".PARAM_SEPARATOR // 9
				."vendor region".PARAM_SEPARATOR // 10
				."vendor position".PARAM_SEPARATOR // 11
				."vendor owner name".PARAM_SEPARATOR // 12
				."vendor owner key".PARAM_SEPARATOR // 13
				."server name".PARAM_SEPARATOR // 14
				."server region".PARAM_SEPARATOR // 15
				."server position"; // 16
		fputcsv($file, split(PARAM_SEPARATOR, $headings), $logseparator);
		fclose($file);
		$this->render("<script> alert('".JText::_('LOG FILE CLEARED')."'); window.history.go(-1); </script>\n");
	}
}
?>