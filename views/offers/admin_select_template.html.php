<div id="ribbon">
	<span>New Offer: Template Selection</span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
	<select id="template_select">
		<?php foreach($templates as $key => $name):?>
			<option value="<?=$key;?>"><?=$name;?></option>		
		<?php endforeach;?>
	</select>
		<div id="template_error" style="display: none;">
			<p style="color: #ff0000;">A template must be selected.</p>
			<p>No templates? <?=$this->html->link('Create one!', array('controller'=>'offer_templates','action'=>'add', 'admin' => true));?></p>
		</div>
	<button id="template_select_submit">Next</button>
	</div>
</div>
<script type="text/javascript">
$('#template_select_submit').bind('click', function(e){
	if (!$('#template_select').val()){
		$('#template_error').fadeIn(400);
		return false;
	}
	e.preventDefault();
	$('#template_error').hide();
	window.location = '<?=$this->url(array('action'=>'add','admin'=>true));?>';
});
</script>