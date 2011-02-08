<h1>Login</h1>
<?=$this->form->create();?>
	<?=$this->form->text('email');?>
	<?=$this->form->password('password');?>
	<?=$this->form->submit('Login');?>
<?=$this->form->end();?>