<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	// Set toolbar items for the page
	JToolBarHelper::title(   JText::_( 'SERVER' ).': <small><small>[ ' . $text.' ]</small></small>' );
	// for existing items the button is renamed `close`
	JToolBarHelper::cancel( 'cancel', 'Close' );
?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
					<?php echo JText::_( 'SERVER NAME' ); ?>:
			</td>
			<td>
				<?php echo $this->server->name;?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
					<?php echo JText::_( 'SERVER DATA CHANNEL' ); ?>:
			</td>
			<td>
				<?php echo $this->server->data_channel;?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
					<?php echo JText::_( 'SERVER UUID' ); ?>:
			</td>
			<td>
				<?php echo $this->server->uuid;?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<?php echo JText::_( 'SERVER POSITION' ); ?>:
			</td>
			<td>
				<?php $slurl = 'http://slurl.com/secondlife/'.$this->server->region.'/'.$this->server->position.'/?&title='.JText::_( 'SERVER POSITION' ).'&msg='.JText::_( 'SERVER LINK' ); ?>
				<a href="<?php echo $slurl; ?>" target="_blank"><?php echo JText::_( 'SERVER LINK' ); ?></a>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
					<?php echo JText::_( 'SERVER STATUS' ); ?>:
			</td>
			<td>
				<div id="server-online">Loading...</div>
				<script language="javascript" type="text/javascript">window.setTimeout('ajaxRequest("server-online", "<?php echo JURI::root(); ?>/index.php?option=com_slvendor&view=inworld&controller=inworld&format=raw&task=checkServer&uuid=<?php echo $this->server->uuid; ?>", "GET","")', 200);</script>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
	<input type="hidden" name="option" value="com_slvendor" />
	<input type="hidden" name="controller" value="servers" />
	<input type="hidden" name="cid[]" value="<?php echo $this->server->id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>