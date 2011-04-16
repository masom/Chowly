<div id="ribbon">
	<span>Registration</span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<?=$this->form->create($user,array('id'=>'UserRegistration'));?>
		<?=$this->form->field('name');?>
		<?=$this->form->field('email');?>
		<?=$this->form->field('password', array('id'=>'UserRegistrationPassword', 'type'=>'password'));?>
		<?=$this->form->field('password_repeat', array('id'=>'UserRegistrationPasswordRepeat', 'type'=>'password'));?>
		<button onclick="return false;" id="UserRegistrationSubmit">Register</button>
		<?=$this->form->end();?>
	</div>
</div>
<script type="text/javascript">

$('#UserRegistrationSubmit').bind('click', function(){
	if($('#UserRegistrationPassword').val() != $('#UserRegistrationPasswordRepeat').val()){
		alert('Passwords do not match');
		return false;
	}
	$('#UserRegistration').submit();
});

</script>