<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip');
	JToolBarHelper::title(   JText::_( 'SERVERS MANAGER' ), 'generic.png' );
	JToolBarHelper::deleteList(JText::_( 'DO YOU REALLY WANT TO DELETE SERVERS ?' ));
?>

<form action="index.php" method="post" name="adminForm">
<table>
<tr>
	<td align="left" width="100%">
		<?php echo JText::_( 'Filter' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_catid').value='0';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
	</td>
</tr>
</table>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th class="title">
				<?php echo JText::_( 'SERVER NAME' ); ?>
			</th>
			<th class="title">
				<?php echo JText::_( 'SERVER REGION' ); ?>
			</th>
			<th class="title">
				<?php echo JText::_( 'SLURL' ); ?>
			</th>
			<th class="title">
				<?php echo JText::_( 'SERVER STATUS' ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JText::_( 'ID' ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="9">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$link 	= JRoute::_( 'index.php?option=com_slvendor&controller=servers&task=edit&cid[]='. $row->id );
		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$ordering = ($this->lists['order'] == 'a.ordering');
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td align="center">
					<a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
			</td>
			<td align="center">
				<?php echo $row->region; ?>
			</td>
			<td align="center">
				<?php $slurl = 'http://slurl.com/secondlife/'.$row->region.'/'.$row->position.'/?&title='.JText::_( 'SERVER POSITION' ).'&msg='.JText::_( 'SERVER LINK' ); ?>
				<a href="<?php echo $slurl; ?>" target="_blank"><?php echo JText::_( 'SERVER LINK' ); ?></a>
			</td>
			<td align="center">
				<div id="server-online-<?php echo $i; ?>">Loading...</div>
				<script language="javascript" type="text/javascript">window.setTimeout('ajaxRequest("server-online-<?php echo $i; ?>", "<?php echo JURI::root(); ?>/index.php?option=com_slvendor&view=inworld&controller=inworld&format=raw&task=checkServer&uuid=<?php echo $row->uuid; ?>&sleep=<?php echo $i*4; ?>", "GET","")', 200);</script>
			</td>
			<td align="center">
				<?php echo $row->id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
</div>

	<input type="hidden" name="option" value="com_slvendor" />
	<input type="hidden" name="controller" value="servers" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>