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
 * Default View
 *
 * @package    SlVendor
 */
class SlvendorViewSlvendor extends JView
{
	/**
	 * display method of server view
	 * @return void
	 **/
	function display($tpl = null)
	{
		$cparams = JComponentHelper::getParams ('com_slvendor');
		$use_log_file = $cparams->get('use_log_file', 1);

		if ($use_log_file)
		{
			// get the log file name
			$logfilename = $cparams->get('logfilename');

			// get the config
			$config =& JFactory::getConfig();

			// get the logfile path
			$log_file_path = $config->getValue('log_path'). DS. $logfilename;

			// get the log file url
			$log_file_url = str_replace(JPATH_ROOT.DS, JURI::root(), $log_file_path);

			// check if the log directory is writable
			$log_dir_is_writable = is_writable($config->getValue('log_path'));
			if (!$log_dir_is_writable)
			{
				JError::raiseWarning('SOME_ERROR_CODE', JText::_( 'LOG DIR IS NOT WRITABLE' ));
			}

			// check if the log file exists
			$log_file_exists = file_exists($log_file_path);

			// check if the log file is writable
			$log_file_is_writable = is_writable($log_file_path);
			if ($log_file_exists && !$log_file_is_writable)
			{
				JError::raiseWarning('SOME_ERROR_CODE', JText::_( 'LOG FILE IS NOT WRITABLE' ));
			}

			// build the link to clear the log file
			$clear_log_link = JURI::root()."/index.php?option=com_slvendor&view=inworld&controller=inworld&format=raw&task=clearLog&hash=".md5($config->getValue('secret').":".$cparams->get('password'));

			$this->assignRef('log_file_path', $log_file_path);
			$this->assignRef('log_file_url', $log_file_url);
			$this->assignRef('log_file_exists', $log_file_exists);
			$this->assignRef('log_file_is_writable', $log_file_is_writable);
			$this->assignRef('clear_log_link',	 $clear_log_link);
			$this->assignRef('logfilename', $logfilename);
		}

		$this->assignRef('use_log_file',	 $use_log_file);
		parent::display($tpl);
	}
}
?>