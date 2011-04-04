<div id="ribbon">
	<span>Venues</span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<ul class="venues-listing">
		<?php foreach($venues as $venue):?>
			<li id="<?=$venue->_id;?>" class="venue-listing">
				<?php if($venue->logo):?>
					<?=$this->html->image("/images/{$venue->logo}.gif");?>
				<?php endif;?>
				<h4><?=$venue->name;?></h4>
				<p class="venue-informations"><?=$venue->address;?></p>
				<p class="venue-informations"><?=$venue->phone?></p>
			</li>
		<?php endforeach;?>
		</ul>
		<br style="clear: both;" />
</div>
</div>
<script type="text/javascript">
$('.venue-listing').each(function(i){
	$(this).bind('click', function(){ 
		window.location = '/venues/view/' + this.id;
	});
});
</script>