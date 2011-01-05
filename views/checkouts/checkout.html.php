<div id="content-header">
	<h1>Checkout</h1>
</div>

<div id="content-panel">
<?=$this->form->create($transaction, array('type' => 'file', 'id'=>'form_transaction')); ?>
	<?=$this->form->field('name'); ?>
	<?=$this->Form->field('phone',array('label' => 'Phone Number (public)'));?>
	<?=$this->form->field('address');?>
	<button id="form_transaction_save" onclick="return false;">Submit</button>
	<button id="form_transaction_cancel" onclick="return false;">Cancel</button>
<?=$this->form->end(); ?>
</div>
<script type="text/javascript">
$("#form_transaction_save").bind('click',function(){
	$("#form_transaction").submit();
});
$("#form_transaction_cancel").bind('click',function(){
	$('#form_transaction_save').hide();
	$('#form_transaction')[0].reset();
	history.back();
});
</script>