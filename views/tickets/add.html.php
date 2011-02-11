
<div style="width: 500px; margin-left: auto; margin-right: auto;">
<h1>Contact Us</h1>

<p>Being one of the first to sign up for our service has its advantages. <br /> Let us know what you would like to see from Chowly.com.</p>
<p>Think of this as your suggestion box with direct access to our executive team! <br /> Our users are our customers and we will only develop the features and functionality you want to let us serve you better.</p>

	<?=$this->form->create($ticket);?>
		<?=$this->form->field('name');?>
		<?=$this->form->field('email');?>
		<?=$this->form->field('zip', array('label' => 'Postal Code'));?>
		<?=$this->form->textarea('content', array('style'=>'min-height: 100px;','label' => 'Fill in your comment/question/concern bellow.'));?>
		<?=$this->form->submit('Submit');?>
	<?=$this->form->end();?>
</div>