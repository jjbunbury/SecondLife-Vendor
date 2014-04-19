<h1><?php echo $this->product->name; ?></h1>
<img <?php echo $this->product->image['attr']; ?> src="<?php echo $this->product->image['url'];?>" />
<ul>
	<li>Name : <?php echo $this->product->name; ?></li>
	<li>Version : <?php echo $this->product->version; ?></li>
	<li>Object Name : <?php echo $this->product->object_name; ?></li>
	<li>Price : <?php echo $this->product->price; ?></li>
	<li>Perms : <?php echo $this->product->perms; ?></li>
	<?php if ($this->download_link != ""): ?>
	<li><a href="<?php echo $this->download_link; ?>&type=6"><?php echo JText::_( 'GET OBJECT' ); ?></a></li>
	<li><a href="<?php echo $this->download_link; ?>&type=7"><?php echo JText::_( 'GET NOTECARD' ); ?></a></li>
	<?php endif; ?>
</ul>
Description : <?php echo $this->product->description; ?>