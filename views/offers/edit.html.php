<div id="content-header">
	<?php if($offer->_id):?>
		<h1>Modifying Offer <?=$offer->name;?></h1>
	<?php else:?>
		<h1>New Offer for <?=$venue->name;?></h1>
	<?php endif;?>
</div>

<div id="content-panel">
<?=$this->form->create($offer, array('type' => 'file', 'id'=>'form_venue')); ?>

	<?=$this->form->field('name'); ?>

	<?=$this->form->field('description', array('type'=>'textarea')); ?>

	<?=$this->form->field('price', array('label' => 'Price in C$'));?>
	
	<?=$this->form->field('starts', array('label'=>'Date Start'));?>
	<?=$this->form->field('ends', array('label'=>'Date Stops'));?>
	
	<?=$this->form->field('availability', array('label'=>'How many coupons?'));?>
	
	<?=$this->form->field('venue_id', array('type' => 'hidden', 'value' => $venue_id));?>
	<button id="form_offer_save" onclick="return false;">Save</button>
	<button id="form_offer_cancel" onclick="return false;">Cancel</button>
<?=$this->form->end(); ?>
</div>
<script type="text/javascript">
$("#form_offer_save").bind('click',function(){
	$("#form_offer").submit();
});
$("#form_offer_cancel").bind('click',function(){
	$('#form_offer_save').hide();
	history.back();
});
</script>