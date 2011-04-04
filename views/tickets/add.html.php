<div style="padding-left: 15px; height: 43px; line-height: 43px; color: #ffffff; font-size: 24px; font-weight: bold; background: url(/img/top-ribbon.png);">
	<span>Contact Us</span>
</div>
<div id="content-wrapper">
<div style="width: 600px; margin-left: auto; margin-right: auto; margin-top: 40px;">
	<?php if($isRestaurant):?>
		<p>
			Chowly offers the most efficient and cost-effective way to bring you the one thing you’ve always wanted from advertising—customers! 
			With Chowly, you are in control and decide how many customers you want and when. 
			Turn the volume up or down on demand!
		</p>
		<ul style="list-decoration: disc;">
			<li>Advertise at without risk and with full control</li>
			<li>Reach a targeted audience that is looking for restaurant deals on the nights you want more customers</li>
			<li>Watch your deals get circulated between family and friends. Buzz, word-of-mouth and viral.</li>
			<li>Watch new customers turn into returning customers</li>
			<li>Focus only on times that you have extra capacity</li>
		</ul>
		<p>
			CHOWLY + YOUR RESTAURANT + MEMBERS + FAMILY AND FRIENDS = WIN/WIN
		</p>
	<?php else:?>
		<p>Being one of the first to sign up for our service has its advantages. <br /> Let us know what you would like to see from Chowly.com.</p>
		<p>Think of this as your suggestion box with direct access to our executive team! <br /> Our users are our customers and we will only develop the features and functionality you want to let us serve you better.</p>
	<?php endif;?>
	<?=$this->form->create($ticket);?>
		<?=$this->form->field('name');?>
		<?=$this->form->field('email');?>
		<?php if($isRestaurant):?>
			<?=$this->form->field('restaurant', array('label'=>'Restaurant Name'));?>
			<?=$this->form->field('phone', array('label'=>'Phone Number'));?>
		<?php endif;?>
		<?=$this->form->field('zip', array('label' => 'Postal Code'));?>
		<?=$this->form->field('content', array('type'=>'textarea','style'=>'min-height: 100px;','label' => 'Fill in your comment/question/concern bellow.'));?>
		<?=$this->form->submit('Submit');?>
	<?=$this->form->end();?>
	
</div>
<br />
</div>