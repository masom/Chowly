<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Application &gt; <?php echo $this->title(); ?></title>
	<?php echo $this->html->style(array('debug', 'lithium', 'cupertino/jquery-ui-1.8.7.custom')); ?>
	<?php echo $this->html->script(array('jquery-1.4.4.min', 'jquery-ui-1.8.7.custom.min', 'jquery.numeric')); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body>
	<div id="container">
		<div id="header">
			<div id="logo">
				<div class="info">
					About Us | Your Restaurant Here | Contact Us
				</div>
				<?=$this->html->image('logo.png');?>
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
			<?php if(isset($messages) && !empty($messages)):?>
				<div class="message-center">
					<?php foreach($messages as $message):?>
						<div class="message <?php echo $message['class'];?>">
							<?=$message['message'];?>
						</div>
					<?php endforeach;?>
				</div>
			<?php endif;?>
			<?php echo $this->content(); ?>
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