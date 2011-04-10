<?php 
$starts = ($offer->_id)? $offer->starts->sec : time();
$ends = ($offer->_id)? $offer->ends->sec : time() + 60 * 60 * 24 * 30;
?>
<div id="ribbon">
	<span><?=($offer->_id)? "Modifying Offer {$offer->name}": "New Offer for {$venue->name}";?></span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
	<?=$this->form->create($offer, array('type' => 'file', 'id'=>'form_offer')); ?>
		<?=$this->form->field('name'); ?>
		<?=$this->form->field('description', array('type'=>'textarea')); ?>
		<?=$this->form->field('limitations', array('type'=>'textarea')); ?>
		<?=$this->form->field('cost', array('label' => 'Price in C$', 'id'=>'offer_cost'));?>
		<ul class="time-picker">
			<li><?=$this->form->field('starts', array('value'=>date('Y-m-d H:i:s', $starts),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_offer_start_date'));?></li>
			<li><?=$this->form->field('ends', array('value' => date('Y-m-d H:i:s', $ends ),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_offer_end_date'));?></li>
		</ul>
		<br style="clear: both;" />
		<?=$this->form->field('expires', array('value'=>date('Y-m-d H:i:s', $ends),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_offer_expires'));?>
		
		<?php if(!$offer->_id):?>
			<?=$this->form->field('availability', array('id'=>'offer_availability','label'=>'How many coupons?', 'style'=>'width: 100px;'));?>
		<?php endif;?>
		
		<?=$this->form->hidden('venue_id', array('value' => $venue->_id));?>
		<button id="form_offer_save" onclick="return false;">Save</button>
		<button id="form_offer_cancel" onclick="return false;">Cancel</button>
	<?=$this->form->end(); ?>
	</div>
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