<div id="content-header">
	<?php if($venue->_id):?>
		<h1>Modifying venue <?=$venue->name;?></h1>
	<?php else:?>
		<h1>New Venue</h1>
	<?php endif;?>
</div>

<div id="content-panel">
<?=$this->form->create($venue, array('type' => 'file', 'id'=>'form_venue')); ?>
	<?=$this->form->field('name'); ?>
	<?=$this->form->field('description', array('type'=>'textarea')); ?>
	<?=$this->Form->field('phone',array('label' => 'Phone Number (public)'));?>
	<?=$this->form->field('address');?>
	
	<div <?=($venue->image)? 'style="display:hidden;"' : '' ; ?>>
	<?=$this->form->field('image', array('type' => 'file', 'id'=>'form_venue_image')); ?>
	</div>
	<div <?=($venue->logo)? 'style="display:hidden;"' : '' ; ?>>
	<?=$this->form->field('logo', array('type' => 'file', 'id'=>'form_venue_logo')); ?>
	</div>
	
	<?=$this->form->field('state', array('type'=>'select', 'label' => 'Publication status', 'list'=>$publishedOptions));?>
	<button id="form_venue_save" onclick="return false;">Save</button>
	<button id="form_venue_cancel" onclick="return false;">Cancel</button>
<?=$this->form->end(); ?>
</div>
<script type="text/javascript">
$("#form_venue_save").bind('click',function(){
	$("#form_venue").submit();
});
$("#form_venue_cancel").bind('click',function(){
	$('#form_venue_save').hide();
	history.back();
});
</script>