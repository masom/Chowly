<h1>Contact Us</h1>
<p>Fill the form bellow to contact us.</p>
<p>We are online most of the time and will probably answer your questions/concerns/comments shortly.</p>

<div style="width: 500px; margin-left: auto; margin-right: auto;">
	<?=$this->form->create($ticket);?>
		<?=$this->form->field('name');?>
		<?=$this->form->field('email');?>
		<?=$this->form->field('zip', array('label' => 'Postal Code'));?>
		<?=$this->form->textarea('content', array('style'=>'min-height: 100px;','label' => 'Fill in your comment/question/concern bellow.'));?>
		<?=$this->form->submit('Submit');?>
	<?=$this->form->end();?>
</div>