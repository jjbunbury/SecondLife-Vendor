<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>
<?php // ******************* header ***************** ?>
<?php JToolBarHelper::title(   JText::_( 'SLVENDOR' ), 'generic.png' ); ?>
<?php // ******************* log file buttons ***************** ?>
<?php if ($this->use_log_file && $this->log_file_exists): ?>
	<?php
	$bar = & JToolBar::getInstance('toolbar');
	$bar->appendButton( 'Link', 'preview', JText::_( 'VIEW LOG FILE' ), $this->log_file_url, false);
	if ($this->log_file_is_writable) {
		$bar->appendButton( 'Link', 'cancel', JText::_( 'CLEAR LOG FILE' ), "javascript:if(confirm('".JText::_( 'ARE YOU SURE TO CLEAR LOG ?' )."')) location.href='".$this->clear_log_link."';", false);
	}
	?>
<?php endif; ?>
<?php // ******************* preferences button ***************** ?>
<?php JToolBarHelper::preferences('com_slvendor', '360'); ?>
<?php // ******************* main content ***************** ?>
<form action="index.php" method="post" name="adminForm">
<div align="center">
	<?php echo JText::_( 'SLVENDOR DESCRIPTION' ); ?>
	<ul>
		<li><a href="index.php?option=com_slvendor&view=products"><?php echo JText::_( 'PRODUCTS MENU LINK' ); ?></a></li>
		<li><a href="index.php?option=com_categories&section=com_slvendor"><?php echo JText::_( 'PRODUCTS CATEGORY MENU LINK' ); ?></a></li>
		<li><a href="index.php?option=com_slvendor&view=servers"><?php echo JText::_( 'SERVERS MENU LINK' ); ?></a></li>
		<li><a href="http://joomlacode.org/gf/project/slvendor/wiki/" target="_blank"><?php echo JText::_( 'HELP LINK' ); ?></a></li>
		<li><a href="http://joomlacode.org/gf/project/slvendor/forum/" target="_blank"><?php echo JText::_( 'FORUM LINK' ); ?></a></li>
		<?php if ($this->use_log_file && $this->log_file_exists): ?>
		<li><a href="<?php echo $this->log_file_url; ?>" target="_blank"><?php echo JText::_( 'VIEW LOG FILE' ); ?></a></li>
		<?php if ($this->log_file_is_writable): ?>
		<li><a href="javascript:if(confirm('<?php echo JText::_( 'ARE YOU SURE TO CLEAR LOG ?' ); ?>')) location.href='<?php echo $this->clear_log_link?>';"><?php echo JText::_( 'CLEAR LOG FILE' ); ?></a></li>
		<?php endif; ?>
		<?php endif; ?>
	</ul>
	<div style="color:red;"><?php echo JText::_( 'NOMODIFY ADVERTISEMENT' ); ?></div>
</div>
	<input type="hidden" name="option" value="com_slvendor" />
	<input type="hidden" name="controller" value="slvendor" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>