<h1>Users</h1>
<?=$this->html->link('Add a new user',array('Users::add'));?>
<table>
	<tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr>
	<?php foreach($users as $user):?>
		<tr>
			<td><?=$user->name;?></td>
			<td><?=$user->email;?></td>
			<td><?=$user->role;?></td>
			<td><?php echo ($user->active)? 'Enabled' : 'Disabled';?></td>
			<td>
				<?=$this->html->link('Edit',array('controller'=>'users','action'=>'edit', 'id'=>$user->_id));?> 
				<?=$this->html->link('Disable',array('controller'=>'users','action'=>'disable','id'=>$user->_id));?>
			</td>
		</tr>
	<?php endforeach;?>
</table>
