<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Chowly | Pick. Eat. Save.</title>
	<?php echo $this->html->style(array('debug', 'chowly', 'cupertino/jquery-ui-1.8.7.custom')); ?>
	<?php echo $this->html->script(array('jquery-1.4.4.min', 'jquery-ui-1.8.7.custom.min', 'jquery.numeric','jquery.countdown.min')); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body>
	<div id="container">
		<div id="header">
			<?php echo $this->html->link($this->html->image('logo-slogan.png', array('width' =>'212px;','alt'=>'Logo')), '/',array('escape'=>false));?>

			<div class="info">
				<?php if($this->session->read('user')):?>
					<?php echo $this->View()->render(array('element'=>'menu'));?>
					<br />
				<?php else:?>
					<?=$this->html->link('Home', array('Offers::index'));?>
					<?=$this->html->link('About Us',array('Pages::view','args'=>'about'));?>
					<?=$this->html->link('Your Restaurant Here', array('Tickets::add','args'=>'restaurants'));?>
					<?=$this->html->link('Contact Us', array('Tickets::add'))?>
				<?php endif;?>
			</div>
		</div>
		<div id="top-corners"></div>
		<?=$this->flashMessage->output(); ?>
		<?php echo $this->content(); ?>
		<div id="bottom-corners"></div>
		<?=$this->view()->render(array('element'=>'bottomboxes'));?>
		
		<div class="push"></div>
	</div>
	<div id="footer-container">
		<div id="footer">
			<div class="footer-section">
				<h1>Main Menu</h1>
				<ul>
					<li><?=$this->html->link('Your Restaurant Here', array('Tickets::add','args'=>'restaurant'));?></li>
					<li><?=$this->html->link('About Us', array('Pages::view','args'=>'about'));?></li>
					<li><?=$this->html->link('Contact Us', array('Tickets::add'));?></li>
				</ul>
			</div>
			<div class="footer-section">
				<h1>Site</h1>
				<ul>
					<li><?=$this->html->link('Sitemap', array('Pages::view', 'args'=>'sitemap'));?></li>	
					<li><?=$this->html->link('Our Promise To You', array('Pages::view','args'=>'guarantee'));?></li>
					<li><?=$this->html->link('Privacy Policy', array('Pages::view','args'=>'privacy'));?></li>
					<li><?=$this->html->link('Terms Of Service', array('Pages::view','args'=>'terms'));?></li>
					<li><?=$this->html->link('Open Source', array('Pages::view','args'=>'opensource'));?></li>
				</ul>
			</div>
			<div class="footer-section">
				&copy; 2011 Chowly Inc. All Rights Reserved.
			</div>
		</div>
	</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20371187-1']);
  _gaq.push(['_setDomainName', '.chowly.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>