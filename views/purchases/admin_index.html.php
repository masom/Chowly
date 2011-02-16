<h1>Purchases</h1>

<div>
	<input type="text" id="purchase_id" style="width: 300px; float: left; margin:0; padding:0.6em;" />
	<button onclick="return false;" id="purchase_submit">View</button>
	<script type="text/javascript">
		$('#purchase_submit').bind('click',function(){
			document.location = '<?=$this->url(array('Purchases::view','admin'=>true));?>/' + $('#purchase_id').val();
		});
	</script>
</div>
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

<div>
	<?php if($page > 1):?>
		<?=$this->html->link('< Previous', array('Purchases::index', 'admin'=>true, 'page'=> $page - 1));?>
	<?php endif;?>
	<?php if($total > ($limit * $page)):?>
		<?=$this->html->link('Next >', array('Purchases::index', 'admin'=>true, 'page'=> $page + 1));?>
	<?php endif;?>
</div>