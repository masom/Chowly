<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Chowly | Pick. Eat. Save.</title>
	<?php echo $this->html->style(array('debug', 'lithium', 'cupertino/jquery-ui-1.8.7.custom')); ?>
	<?php echo $this->html->script(array('jquery-1.4.4.min', 'jquery-ui-1.8.7.custom.min', 'jquery.numeric','jquery.countdown.min')); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body>
	<div id="container">
		<div id="header">
			<div id="logo">
				<div class="info">
					<?=$this->html->link('About Us',array('Pages::view','args'=>'about'));?>
					&nbsp;|&nbsp;
					<?=$this->html->link('Your Restaurant Here', array('Pages::view', 'args'=>'contact'));?>
					&nbsp;|&nbsp;
					<?=$this->html->link('Contact Us', array('Pages::view','args'=>'contact'))?>
				</div>
				<?php echo $this->html->link($this->html->image('logo.png', array('width' =>'150px;','alt'=>'Logo')), array('Landings::pre'),array('escape'=>false));?>
				<h2>
					Pick. Eat. Save.
				</h2>
			</div>
		</div>
		<div id="content">
			<?=$this->flashMessage->output(); ?>
			<?php echo $this->content(); ?>
			<br style="clear:both;"/>
		</div>
		<div id="footer">
			<strong>Unbeatable Deals for Local Dining</strong>
			<p>Chowly is an easy way to get discounts while discovering restaurants in ottawa.</p>
			<div id="copyright">
			
			</div>
			&copy; 2010 Chowly Inc. All Rights Reserved.
		</div>
	</div>
</body>
</html>