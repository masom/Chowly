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
			<td><?php echo ($user->active)? 'Enabled' : 'Disabled';?></td>
			<td>
				<?=$this->html->link('Edit',array('controller'=>'users','action'=>'edit', 'id'=>$user->_id,'admin'=>true));?> 
				<?=$this->html->link('Disable',array('controller'=>'users','action'=>'disable','id'=>$user->_id,'admin'=>true));?>
			</td>
		</tr>
	<?php endforeach;?>
</table>
<div>
	<?php if($page > 1):?>
		<?=$this->html->link('< Previous', array('Offers::index', 'admin'=>true, 'page'=> $page - 1));?>
	<?php endif;?>
	<?php if($total > ($limit * $page)):?>
		<?=$this->html->link('Next >', array('Offers::index', 'admin'=>true, 'page'=> $page + 1));?>
	<?php endif;?>
</div>