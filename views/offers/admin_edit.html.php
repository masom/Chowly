<?php 
$starts = ($offer->_id)? $offer->starts->sec : time();
$ends = ($offer->_id)? $offer->ends->sec : time() + 60 * 60 * 24 * 30;
?>
<div id="ribbon">
	<span><?=($offer->exists())? "Modifying Offer {$offer->name}": "New Offer";?></span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
	<?=$this->form->create($offer, array('type' => 'file', 'id'=>'form_template')); ?>
		<?php if(!$offer->exists()):?>
			<?=$this->form->field('venue_id', array('type'=>'select', 'list' => $venues, 'label'=>'Select the venue')); ?>
		<?php endif;?>
		<?=$this->form->field('name'); ?>
		<?=$this->form->field('description', array('type'=>'textarea')); ?>
		<?=$this->form->field('limitations', array('type'=>'textarea', 'label'=>'Limitations')); ?>
		<?=$this->form->field('cost', array('label' => 'Price in C$', 'id'=>'offer_cost'));?>
		<ul class="time-picker">
			<?=$this->form->field('starts', array('value'=>date('Y-m-d H:i:s', $starts),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_template_start_date'));?>
			<?=$this->form->field('ends', array('value' => date('Y-m-d H:i:s', $ends ),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_template_end_date'));?>
			<?=$this->form->field('expires', array('value'=>date('Y-m-d H:i:s', $ends),'template'=>'<li{:wrap}>{:label}{:input}{:error}</li>','id'=>'form_offer_expires'));?>
		</ul>
		<br style="clear: both;" />
		<?php if(!$offer->exists()):?>
			<?=$this->form->field('availability', array('tyle'=>'select', 'list'=> range(10, 100, 10),'id'=>'offer_availability','label'=>'How many coupons?', 'style'=>'width: 100px;'));?>
			<?=$this->form->hidden('template_id', array('value'=> $offer->template_id));?>
		<?php endif;?>
		<button id="form_template_save" onclick="return false;">Save</button>
		<button id="form_template_cancel" onclick="return false;">Cancel</button>
	<?=$this->form->end(); ?>
	</div>
</div>

<script type="text/javascript">
$("#template_availability").numeric();
$("#otemplate_cost").numeric();
$("#form_template_save").bind('click',function(){
	$("#form_template").submit();
});
$("#form_template_cancel").bind('click',function(){
	$('#form_template_save').hide();
	history.back();
});
</script>