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
		<div id="content-wrapper">
			<div id="header">
				<div id="logo">
					<div class="info">
						<?=$this->html->link('Home', array('Landings::pre'));?>
						&nbsp;|&nbsp;
						<?=$this->html->link('About Us',array('Pages::view','args'=>'about'));?>
						&nbsp;|&nbsp;
						<?=$this->html->link('Your Restaurant Here', array('Tickets::add','args'=>'restaurants'));?>
						&nbsp;|&nbsp;
						<?=$this->html->link('Contact Us', array('Tickets::add'))?>
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
			</div>
		</div>
		<div class="push"></div> 
	</div>
	<div id="footer-container">
		<div id="footer">
			<strong>Unbeatable Deals for Local Dining</strong>
			<div>Chowly the easiest way to discover local restaurants.
				<?=$this->html->link('Privacy Policy', array('Pages::view','args'=>'privacy'));?>
				&nbsp;|&nbsp;
				<?=$this->html->link('Our Promise To You', array('Pages::view','args'=>'guarantee'));?>
				</div>
			<div id="copyright">
				&copy; 2010 Chowly Inc. All Rights Reserved.
			</div>
		</div>
	</div>
</body>
</html>
