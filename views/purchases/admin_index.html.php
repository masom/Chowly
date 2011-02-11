<h1>Purchases</h1>

<table>
	<thead>
		<tr><th>Date</th><th>Name</th><th>Email</th><th>Status</th><th>Actions</th></tr>
	</thead>
	<?php foreach($purchases as $purchase):?>
		<tr>
			<td><?=date('Y-m-d H:i:s', $purchase->created->sec);?></td>
			<td><?=$purchase->name;?></td>
			<td><?=$purchase->email;?></td>
			<td><?=$purchase->status;?></td>
			<td><?=$this->html->link('View', array('Purchases::view','id' => $purchase->_id,$this->session->read('user.role') => true));?></td>
		</tr>
	<?php endforeach;?>
</table>