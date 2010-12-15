<div id="content-header">
<h1>Current Deals</h1>
</div>

<div id="content-panel">


<?php if(!count($offers)):?>
	<p class="empty-results">
		Sorry, there are currently no offers.
		<br />
		<br />
		Come Back Soon!
		<br />
		<br />
		~Chowly
	</p>
<?php endif;?>
<ul class="offers">
	<?php foreach($offers as $offer):?>
		<li>
			<h4><?=$this->html->link($offer->name, array('Offers::view', 'id'=> $offer->_id));?></h4>
			<?php if($offer->venue_id):?>
				<?=$this->html->image("/images/{$venues[(string)$offer->venue_id]}.jpg");?>
			<?php endif;?>
			
			<p><em>Since</em> <?=date('Y-m-d H:i:s', $offer->starts->sec);?></p>
			<p><em>Ends</em> <?=date('Y-m-d H:i:s', $offer->ends->sec);?></p>
		</li>
	<?php endforeach;?>
</ul>
</div>