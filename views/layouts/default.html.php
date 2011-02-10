<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Chowly | Pick. Eat. Save.</title>
	<?php echo $this->html->style(array('debug', 'lithium', 'cupertino/jquery-ui-1.8.7.custom')); ?>
	<?php echo $this->html->script(array('jquery-1.4.4.min', 'jquery-ui-1.8.7.custom.min')); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body>
	<div id="container">
		<div id="header_cta">
			<div style="padding: 10px; margin-left: 10px; margin-right: 10px; padding-top: 15px;">
			Get Daily Restaurant Deals in <strong>Ottawa</strong>:
				<?=$this->form->create($user,array('url'=>'Landings::pre','style'=>'float:right;'));?>
					<?=$this->form->field('email',array('template'=>'{:input}', 'id'=>'cta_email_field','value'=>'Your email here...'));?>
					<input type="hidden" name="zip" value="" />
					<input type="image" src="/img/subscribe_button.png" alt="Suscribe" />
				<?=$this->form->end();?>

			</div>
		</div>
		<div style="background-image: url(/img/subscribe_rounded_bottom.png); height: 11px; margin-bottom: 20px;"></div>
		<div style="height: 11px;background-image: url(/img/main_rounded_top.png);"></div>
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
					<?php echo $this->html->link($this->html->image('logo.png', array('width' =>'170px;','alt'=>'Logo')), array('Landings::pre'),array('escape'=>false));?>
					<!--<h2>
						Pick. Eat. Save.
					</h2>-->
				</div>
			</div>
			<div id="content">
				
				<?php echo $this->content(); ?>
			</div>
		</div>
		<div style="height: 11px; margin-bottom:20px; background-image: url(/img/main_rounded_bottom.png);"></div>
		<div class="push"></div> 
	</div>
	<div id="footer-container">
		<div id="footer">
			<strong>Unbeatable Deals for Local Dining</strong>
			<div>Chowly the easiest way to discover local restaurants.
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
	(function(){
		$('#cta_email_field').bind('focus', function(){
			if($('#cta_email_field').val() == 'Your email here...'){
				$('#cta_email_field').val('')
			}
		});
	})();
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
