<div id="ribbon">
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
		<h3>Risk Free Advertising</h3>
		<p>There are no up front costs with Chowly. Use us to sell off your excess capacity while receiving compensation for every deal sold.</p>
		<h3>Reach the Right Audience</h3>
		<p>Chowly connects spontaneous last minute dinners with the best local restaurants. Chowly is only for meals meaning 100% of the visitors to our site are looking to eat somewhere today!</p>
		<h3>Get Word-Of-Mouth Buzz</h3>
		<p>Let Chowly users promote your restaurant online without you lifting a finger. Friends will talk about the deal and get other friends to go as well.</p>
		<h3>New and Returning Customers</h3>
		<p>Chowly will help get you infront of the online crowd and send you a predictable and reliable stream of customers.</p>
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
</div>