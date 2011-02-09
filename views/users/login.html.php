<h1>Login</h1>
<?=$this->form->create();?>
	<?=$this->form->field('email');?>
	<?=$this->form->field('password',array('type'=>'password'));?>
	<?=$this->form->submit('Login');?>
<?=$this->form->end();?>