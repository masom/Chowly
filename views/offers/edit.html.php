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

	<?=$this->form->field('cost', array('label' => 'Price in C$', 'id'=>'offer_cost'));?>
	<ul class="time-picker">
		<?=$this->form->field('starts', array('value'=>date('Y-m-d H:i:s', ($offer->starts)? $offer->starts : time()),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_offer_start_date'));?>
		<?=$this->form->field('ends', array('value' => date('Y-m-d H:i:s', ($offer->ends)? $offer->ends : time() + 60 * 60 * 24),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_offer_end_date'));?>
	</ul>
	<br style="clear: both;" />
	<?=$this->form->field('availability', array('id'=>'offer_availability','label'=>'How many coupons?', 'style'=>'width: 100px;'));?>
	<?=$this->form->hidden('venue_id', array('value' => $venue->_id));?>
	<button id="form_offer_save" onclick="return false;">Save</button>
	<button id="form_offer_cancel" onclick="return false;">Cancel</button>
<?=$this->form->end(); ?>
</div>

<script type="text/javascript">
$("#offer_availability").numeric();
$("#offer_cost").numeric();
$("#form_offer_save").bind('click',function(){
	$("#form_offer").submit();
});
$("#form_offer_cancel").bind('click',function(){
	$('#form_offer_save').hide();
	history.back();
});
</script>