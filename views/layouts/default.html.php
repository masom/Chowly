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
				<?php endif;?>
				<?=$this->html->link('Home', array('Offers::index'));?>
				&nbsp;|&nbsp;
				<?=$this->html->link('About Us',array('Pages::view','args'=>'about'));?>
				&nbsp;|&nbsp;
				<?=$this->html->link('Your Restaurant Here', array('Tickets::add','args'=>'restaurants'));?>
				&nbsp;|&nbsp;
				<?=$this->html->link('Contact Us', array('Tickets::add'))?>
			</div>
		</div>
		<div style="height: 10px;background: url(/img/roundtop.png) no-repeat;"></div>
		<?=$this->flashMessage->output(); ?>
		<?php echo $this->content(); ?>
		<div style="clear: both; height: 6px; margin-bottom:20px; background: url(/img/roundbottom.png)  no-repeat;"></div>
		<div class="push"></div> 
	</div>
	<div id="footer-container">
		<div id="footer">
			<div>
				<?=$this->html->link('Our Promise To You', array('Pages::view','args'=>'guarantee'));?>
				&nbsp;|&nbsp;
				<?=$this->html->link('Privacy Policy', array('Pages::view','args'=>'privacy'));?>
				&nbsp;|&nbsp;
				<?=$this->html->link('Terms Of Service', array('Pages::view','args'=>'terms'));?>
			</div>
			<div id="copyright">
				&copy; 2010 Chowly Inc. All Rights Reserved.
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