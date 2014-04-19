<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip');
$editor =& JFactory::getEditor();

	// Set toolbar items for the page
	$edit		= JRequest::getVar('edit',true);
	$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
	JToolBarHelper::title(   JText::_( 'PRODUCT' ).': <small><small>[ ' . $text.' ]</small></small>' );
	JToolBarHelper::save();
	if (!$edit)  {
		JToolBarHelper::cancel();
	} else {
		// for existing items the button is renamed `close`
		JToolBarHelper::cancel( 'cancel', 'Close' );
	}
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
    var text = <?php echo $editor->getContent( 'description' ); ?>
		if (form.catid.value == "0"){
			alert( "<?php echo JText::_( 'YOU MUST SELECT A CATEGORY', true ); ?>" );
		} else {
			<?php	echo $editor->save( 'description' ); ?>
			submitform( pressbutton );
		}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-70">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DESCRIPTION' ); ?></legend>
		<table class="admintable" width="100%">
			<tr>
			<td width="100" align="right" class="key">
				<label for="price">
					<?php echo JText::_( 'SHORT DESCRIPTION' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" id="short_desc" name="short_desc" size="50" maxlength="50" value="<?php echo $this->product->short_desc;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'PRODUCT DESCRIPTION' ); ?>:
				</label>
			</td>
			<td>
            <?php echo $editor->display( 'description',  $this->product->description, '100%', '500px', '75', '20', array('pagebreak', 'readmore') ) ; ?>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="col width-30">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
					<?php echo JText::_( 'NAME' ); ?>:
			</td>
			<td>
				<?php echo $this->product->name;?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
					<?php echo JText::_( 'VERSION' ); ?>:
			</td>
			<td>
				<?php echo $this->product->version;?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
					<?php echo JText::_( 'OBJECT NAME' ); ?>:
			</td>
			<td>
				<?php echo $this->product->object_name;?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<?php echo JText::_( 'SERVER POSITION' ); ?>:
			</td>
			<td>
				<?php $slurl = 'http://slurl.com/secondlife/'.$this->product->server_region.'/'.$this->product->server_position.'/?&title='.JText::_( 'SERVER POSITION' ).'&msg='.JText::_( 'SERVER LINK' ); ?>
				<a href="<?php echo $slurl; ?>" target="_blank"><?php echo JText::_( 'SERVER LINK' ); ?></a>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
					<?php echo JText::_( 'SERVER STATUS' ); ?>:
			</td>
			<td>
				<div id="server-online">Loading...</div>
				<script language="javascript" type="text/javascript">window.setTimeout('ajaxRequest("server-online", "<?php echo JURI::root(); ?>/index.php?option=com_slvendor&view=inworld&controller=inworld&format=raw&task=checkServer&uuid=<?php echo $this->product->server_uuid; ?>", "GET","")', 200);</script>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="price">
					<?php echo JText::_( 'PRODUCT PRICE' ); ?>:
				</label>
			</td>
			<td colspan="3">
				<input class="text_area" type="text" id="price" name="price" size="6" maxlength="6" value="<?php echo $this->product->price;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="texture_uuid">
					<?php echo JText::_( 'TEXTURE KEY' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="texture_uuid" id="texture_uuid" size="32" maxlength="250" value="<?php echo $this->product->texture_uuid;?>" />
			</td>
		</tr>
		<?php if ($this->product->server_id != 0): ?>
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'Published' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="catid">
					<?php echo JText::_( 'Category' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['catid']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="ordering">
					<?php echo JText::_( 'Ordering' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
	</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Parameters' ); ?></legend>
		<table class="admintable">
		<tr>
			<td colspan="2">
				<?php echo $this->params->render();?>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>

	<input type="hidden" name="option" value="com_slvendor" />
	<input type="hidden" name="controller" value="products" />
	<input type="hidden" name="cid[]" value="<?php echo $this->product->id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
