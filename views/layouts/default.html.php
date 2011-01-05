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
					About Us | Your Restaurant Here | Contact Us
				</div>
				<?php echo $this->html->link($this->html->image('logo.png', array('width' =>'150px;','alt'=>'Logo')), array('Offers::index'),array('escape'=>false));?>
				<h2>
					Pick. Eat. Save.
				</h2>
			</div>
			<?php if(isset($breadCrumbs)):?>
				<h3><?php echo $this->html->link('Home', array('Pages::view', 'args'=> array('home')));?>
					<?php foreach($breadCrumbs as $crumb):?>
					&gt; <?php echo $crumb ?>
					<?php endforeach;?>
				</h3>
			<?php endif;?>
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