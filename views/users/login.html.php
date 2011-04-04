<div id="ribbon">
	<span>Login</span>
</div>
<div id="content-wrapper">
	<div style="width: 500px; margin-left: auto; margin-right: auto;">
		<?=$this->form->create();?>
			<?=$this->form->field('email');?>
			<?=$this->form->field('password',array('type'=>'password'));?>
			<?=$this->form->submit('Login');?>
		<?=$this->form->end();?>
	</div>
</div>