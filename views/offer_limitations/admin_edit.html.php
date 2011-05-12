<div id="ribbon">
	<span><?=($limitation->exists())? "Modifying Offer {$limitation->name}" : 'New Limitation';?></span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
	<?=$this->form->create($limitation, array('type' => 'file', 'id'=>'form_limitation')); ?>
		<?=$this->form->field('name'); ?>
		<br style="clear: both;" />
		<button id="form_limitation_save" onclick="return false;">Save</button>
		<button id="form_limitation_cancel" onclick="return false;">Cancel</button>
	<?=$this->form->end(); ?>
	</div>
</div>

<script type="text/javascript">
$("#form_limitation_save").bind('click',function(){
	e.preventDefault();
	$("#form_limitation").submit();
});
$("#form_limitation_cancel").bind('click',function(){
	e.preventDefault();
	$('#form_limitation_save').hide();
	history.back();
});
</script>