<div style="margin-left: auto; margin-right: auto; width: 550px;">
	<h2>What Is Chowly?</h2>
	<p>Awesome deals for Ottawa's restaurants.</p>
	
	<h2>How Does It Works?</h2>
	<p>We partner with local restaurants to bring you the best possible deals.</p>
	<p>Unlike most other group buying sites</p>
	<ul style="margin-left:20px;">
		<li>We do not have a minimum of deals to be sold for it to take effect.</li>
		<li>We work hand-in-hand with local restaurants, we do not want to overload them during high times.</li>
		<li>Being a partner with the restaurants, we can offer better deals.</li>
	</ul>
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