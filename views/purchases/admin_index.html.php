<div id="ribbon">
	<span>Purchases</span>
</div>
<div id="content-wrapper">
	<div style="margin-left: 10px; margin-right: 10px;">
		<?=$this->form->select('purchase_search_type', array('id'=>'Purchase #', 'email'=>'Email','name'=>'Name'), array('id'=>'purchase_search_type'));?>
		<input type="text" id="purchase_search_value" style="width: 300px; float: left; margin:0; padding:0.6em;" />
		<button onclick="return false;" id="purchase_search_submit">View</button>
		<script type="text/javascript">
			$('#purchase_search_submit').bind('click',function(){
				var search = $('#purchase_search_type').val();
				var value = $('#purchase_search_value').val();
				if( search == "id"){
					document.location = '<?=$this->url(array('Purchases::view','admin'=>true));?>/' + value;
					return false;
				}
				$('#form_search_value').val(value);
				$('#form_search_type').val(search);
				$('#form_search').submit();
				return false;
			});
		</script>
		<div style="display: none;">
			<?=$this->form->create(null, array('id'=>'form_search', 'url'=>$this->url(array('action'=>'search', 'admin'=>true))));?>
				<?=$this->form->input('search[value]', array('type'=>'hidden','id'=>'form_search_value'));?>
				<?=$this->form->input('search[type]', array('type'=>'hidden', 'id'=>'form_search_type'))?>
			<?=$this->form->end();?>
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
	
	<?php if(isset($page)):?>
	<div>
		<?php if($page > 1):?>
			<?=$this->html->link('< Previous', array('Purchases::index', 'admin'=>true, 'page'=> $page - 1));?>
		<?php endif;?>
		<?php if($total > ($limit * $page)):?>
			<?=$this->html->link('Next >', array('Purchases::index', 'admin'=>true, 'page'=> $page + 1));?>
		<?php endif;?>
	</div>
	<?php endif;?>
	</div>
</div>