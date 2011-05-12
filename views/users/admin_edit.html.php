<div id="ribbon">
	<span>Edit User</span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<?=$this->form->create($user,array('id'=>'UserRegistration'));?>
		<?=$this->form->field('name', array('autocomplete' => 'off'));?>
		<?=$this->form->field('email', array('autocomplete' => 'off'));?>
		<?=$this->form->field('role', array('type'=>'select','list'=>$user->roles()));?>
		<?=$this->form->field('password', array('id'=>'UserRegistrationPassword', 'type'=>'password', 'autocomplete' => 'off'));?>
		<?=$this->form->field('password_repeat', array('id'=>'UserRegistrationPasswordRepeat', 'type'=>'password', 'autocomplete' => 'off'));?>
		<button onclick="return false;" id="UserRegistrationSubmit">Update</button>
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