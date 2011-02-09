
<h1>Venues</h1>

<?php if(!count($venues)):?>
<p class="empty-results">
	Sorry, there are currently no venues.
	<br />
	<br />
	Come Back Soon!
	<br />
	<br />
	~Chowly
</p>
<?php endif;?>
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

<script type="text/javascript">
$('.venue-listing').each(function(i){
	$(this).bind('click', function(){ 
		window.location = '/venues/view/' + this.id;
	});
});
</script>