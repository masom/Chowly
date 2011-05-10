<div id="ribbon">
	<span>Template: <?=$template->name;?></span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<?=$this->html->link('Back to index.', array('action'=>'index','admin'=>true));?>
		<br />
		<h3>Price</h3>
		<p>$<?=$template->cost;?></p>
		<h3>Description</h3>
		<p style="text-align: justify;"><?php echo nl2br($template->description);?></p>
		<h3>Limitations</h3>
		<p style="text-align: justify;"><?php echo nl2br($template->limitations);?></p>
	</div>
</div>