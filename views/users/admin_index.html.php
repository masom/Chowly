<h1>Users</h1>
<?=$this->html->link('Add a new user',array('Users::add','admin'=>true));?>
<table>
	<thead>
		<tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr>
	</thead>
	<?php foreach($users as $user):?>
		<tr>
			<td><?=$user->name;?></td>
			<td><?=$user->email;?></td>
			<td><?=$user->role;?></td>
			<td id="user_<?=$user->_id;?>_state"><?=($user->active)? 'Enabled' : 'Disabled';?></td>
			<td>
				<?=$this->html->link('Edit',array('controller'=>'users','action'=>'edit', 'id'=>$user->_id,'admin'=>true));?>
				<a href="#" id="<?php echo "user_{$user->_id}_activation";?>"><?=($user->active)? "Disable" : "Enable";?></a>
				<script type="text/javascript">
					$(function(){
					$('#user_<?=$user->_id;?>_activation').data('enabled', <?=($user->active)? 1 : 0;?>);
					$('#user_<?=$user->_id;?>_activation').bind('click', function(e){
						var activate = '<?=$this->url(array('controller'=>'users','action'=>'enable','id'=>$user->_id,'admin'=>true));?>';
						var deactivate = '<?=$this->url(array('controller'=>'users','action'=>'disable','id'=>$user->_id,'admin'=>true));?>';
						var url = null;
						if($('#user_<?=$user->_id;?>_activation').data('enabled')){
							url = deactivate;
						}else{
							url = activate;
						}
						$.ajax({
							  url: url,
							  context: document.body,
							  success: function(data){
							  	if(!data.success){ return;}
							  	$('#user_<?=$user->_id;?>_activation').data('enabled', data.active);
								if(data.active){
								 $('#user_<?=$user->_id;?>_activation').text('Disable');
								 $('#user_<?=$user->_id;?>_state').text('Enabled');
								}else{
								 $('#user_<?=$user->_id;?>_activation').text('Enable');
								 $('#user_<?=$user->_id;?>_state').text('Disabled');
								}
							  }
							});
						return false;
					});});
				</script>
			</td>
		</tr>
	<?php endforeach;?>
</table>
<div>
	<?php if($page > 1):?>
		<?=$this->html->link('< Previous', array('Users::index', 'admin'=>true, 'page'=> $page - 1));?>
	<?php endif;?>
	<?php if($total > ($limit * $page)):?>
		<?=$this->html->link('Next >', array('Users::index', 'admin'=>true, 'page'=> $page + 1));?>
	<?php endif;?>
</div>