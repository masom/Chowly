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
	<div class="offers">
		<?php foreach($offers as $offer):?>
			<div style="float:left; background-color: #fff; border: 2px solid #2E4A21; border-radius: 9px; margin: 5px; padding: 5px;">
				<h4><?=$this->html->link($offer->name, array('Offers::view', 'id'=> $offer->_id));?></h4>
				<?php if($offer->venue_id):?>
					<?=$this->html->image("/images/{$venues[(string)$offer->venue_id]}.jpg");?>
				<?php endif;?>
				<div class="footer">
					<span><?=($offer->availability)? "Only {$offer->availability} left!" : 'Out of Stock!' ;?></span>
					<span style="float:right;" id="offer-countdown-<?php echo $offer->_id;?>" class="countdown"></span>
				</div>		
			</div>
		<?php endforeach;?>
	</div>
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