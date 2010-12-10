<?php if(!count($offers)):?>
	<div class="empty-results">
		Sorry, but there is currently no valid offers. Come back soon!
	</div>
<?php endif;?>
<ul class="offers">
	<?php foreach($offers as $offer):?>
		<li>
			<h4><?php echo $offer->name;?></h4>
			<?=$offer->offer; ?>
			<?=$offer->_id;?>
		</li>
	<?php endforeach;?>
</ul>
