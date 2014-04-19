<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
	</div>
<?php endif; ?>

<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php if ($this->params->def('image', -1) != -1): ?>
<tr>
	<td valign="top" align="<?php echo $this->params->get( 'image_align' ); ?>" class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php if ( isset($this->image) ) :  echo $this->image; endif; ?>
	</td>
</tr>
<?php endif; ?>
<?php if ( $this->params->def('show_intro_text', 1) ) : ?>
<tr>
	<td valign="top" class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php
		echo $this->params->get('intro_text');
	?>
	</td>
</tr>
<?php endif; ?>
</table>
<ul>
<?php foreach ( $this->categories as $category ) : ?>
	<li>
		<a href="<?php echo $category->link; ?>" class="category<?php echo $this->params->get( 'pageclass_sfx' ); ?>"><?php echo $category->title;?></a>
        <?php if ( $this->params->def('show_products_qty', 1) ) : ?>
		&nbsp;<span class="small">(<?php echo $category->numlinks;?>)</span>
		<?php endif; ?>
		<?php if ( $this->params->def('show_cat_desc', 1) ) : ?>
		&nbsp;<span class="small"><?php echo $category->description;?></span>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>