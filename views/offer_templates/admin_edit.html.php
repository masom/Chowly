<div id="ribbon">
	<span><?=($template->exists())? "Modifying Offer {$template->name}" : 'New Template';?></span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
	<?=$this->form->create($template, array('type' => 'file', 'id'=>'form_template')); ?>
		<?=$this->form->field('name'); ?>
		<?=$this->form->field('description', array('type'=>'textarea')); ?>
		<?=$this->form->field('limitations', array('type'=>'textarea')); ?>
		<?=$this->form->field('cost', array('label' => 'Price in C$', 'id'=>'template_cost'));?>
		<br style="clear: both;" />
		<button id="form_template_save" onclick="return false;">Save</button>
		<button id="form_template_cancel" onclick="return false;">Cancel</button>
	<?=$this->form->end(); ?>
	</div>
</div>

<script type="text/javascript">
$("#template_cost").numeric();
$("#form_template_save").bind('click',function(){
	$("#form_template").submit();
});
$("#form_template_cancel").bind('click',function(){
	$('#form_template_save').hide();
	history.back();
});
</script>