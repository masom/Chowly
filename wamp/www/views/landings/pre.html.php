
<div style="margin-left: auto; margin-right: auto; width: 90%;">
	<h1>Sign-Up For Restaurant Deals in Ottawa!</h1>
	<div id="orange">
		<h2>What Is Chowly?</h2>
		<p>Chowly is a restaurant deal site that offers great savings on many restaurants.</p>
	</div>
	<div id="apple">
		<h2>How do we do it?</h2>
		<p>We have relationships with restaurants - from the very best local to leading national chains. Chowly helps these restaurants fill additional tables during off peak times that would otherwise go unsold. This means a Chowly customer can get last minute deals for local restaurants at great prices. And unlike other sites, our sevice makes it easy for our customers to pick from a variety of local restaurants and get same day deals. Everybody wins!</p>

	<p>Rely on us for the great, last-minute, deals available at Chowly.com.</p>
	<p>Pick. Eat. Save.</p>
	</div>	
</div>

<div style="width: 400px; margin-top: 30px; margin-bottom:10px; margin-left: auto; margin-right: auto;">
	<?=$this->form->create($user);?>
		<h3>Sign up for the beta!</h3>
		<p>We are currently in development.</p>
		<p>Soon we will be able to provide a select group of beta testers with early access to great deals!</p>

		<?=$this->form->field('email', array('label'=>'Email Address'));?>
		<?=$this->form->field('zip', array('label'=>'Postal Code'));?>
		<p>
			<?=$this->html->link('Why do we ask for your postal code.', array('Pages::view','args'=>array('whypostalcode')));?>
		</p>
		<div style="text-align: center;">
			<input type="image" src="/img/subscribe_button.png" alt="Suscribe" />
		</div>
	<?=$this->form->end();?>
</div>