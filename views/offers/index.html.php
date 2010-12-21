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
			<?php if($offer->availability > 0):?>
				<p>Only <?=$offer->availability;?> left!</p>
			<?php else:?>
				<p>Out of Stock!</p>
			<?php endif;?>
			<p id = "offer-countdown-<?php echo $offer->_id;?>"></p>		
		</li>
	<?php endforeach;?>
</ul>
</div>
<script type="text/javascript">
	<?php foreach($offers as $offer):?>
		$(function () {
			var couponEnd = new Date();
			couponEnd = new Date(<?php echo $offer->ends->sec * 1000;?>);
			$("#offer-countdown-<?php echo $offer->_id;?>").countdown({until: couponEnd, layout: 'Ends in {dn} {dl}, {hnn}{sep}{mnn}{sep}{snn}'});
		});
	<?php endforeach;?>
</script>