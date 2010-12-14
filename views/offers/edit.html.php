<div id="content-header">
	<?php if($offer->_id):?>
		<h1>Modifying Offer <?=$offer->name;?></h1>
	<?php else:?>
		<h1>New Offer for <?=$venue->name;?></h1>
	<?php endif;?>
</div>

<div id="content-panel">
<?=$this->form->create($offer, array('type' => 'file', 'id'=>'form_offer')); ?>
	<?=$this->form->field('name'); ?>

	<?=$this->form->field('description', array('type'=>'textarea')); ?>

	<?=$this->form->field('price', array('label' => 'Price in C$'));?>
	
	<?=$this->form->field('starts', array('label'=>'Date Start','readonly'=>true,'id'=>'form_offer_starts'));?>
	<?=$this->form->field('ends', array('label'=>'Date Stops','readonly'=>true,'id'=>'form_offer_stops'));?>
	
	<?=$this->form->field('availability', array('id'=>'offer_availability','label'=>'How many coupons?'));?>
	
	<?=$this->form->field('venue_id', array('type'=>'hidden','value' => $venue->_id));?>
	<button id="form_offer_save" onclick="return false;">Save</button>
	<button id="form_offer_cancel" onclick="return false;">Cancel</button>
<?=$this->form->end(); ?>
</div>

<script type="text/javascript">
$("#offer_availability").keydown(function(event) { 
	if ( event.keyCode == 46 || event.keyCode == 8 ) { 
	} else { 
	if (event.keyCode < 95) { 
		if (event.keyCode < 48 || event.keyCode > 57 ) { 
			event.preventDefault();	
		} 
	} else { 
		if (event.keyCode < 96 || event.keyCode > 105 ) { 
			event.preventDefault();	
			} 
		} 
	}
});
$("#form_offer_save").bind('click',function(){
	$("#form_offer").submit();
});
$("#form_offer_cancel").bind('click',function(){
	$('#form_offer_save').hide();
	history.back();
});
$(function(){
	$('#form_offer_starts').datepicker({'dateFormat':'yy-mm-dd'});
	$('#form_offer_stops').datepicker({'dateFormat':'yy-mm-dd'});
});
</script>