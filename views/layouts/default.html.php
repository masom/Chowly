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
						<?=$this->html->link('Home',array('Offers::index'));?>
						&nbsp;|&nbsp;
						<?=$this->html->link('About Us',array('Pages::view','args'=>'about'));?>
						&nbsp;|&nbsp;
						<?=$this->html->link('Your Restaurant Here', array('Tickets::add'));?>
						&nbsp;|&nbsp;
						<?=$this->html->link('Contact Us', array('Tickets::add'))?>
					</div>
					<?php echo $this->html->link($this->html->image('logo.png', array('width' =>'150px;','alt'=>'Logo')), array('Offers::index'),array('escape'=>false));?>
					<h2>
						Pick. Eat. Save.
					</h2>
				</div>
			</div>
			<div id="content">
				<?=$this->flashMessage->output(); ?>
				<?php echo $this->content(); ?>
			</div>
			<div style="clear: both;"></div>
		</div>
		<div class="push"></div> 
	</div>
	<div id="footer-container">
		<div id="footer">
			<strong>Unbeatable Deals for Local Dining</strong>
			<p>Chowly the easiest way to discover local restaurants.</p>
			<div id="copyright">
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
