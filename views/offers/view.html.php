<div id="content-header">
	<h1><?=$offer->name;?></h1>
</div>
<div id="content-panel">
	<div class="offer-informations">
		<?php if($offer->availability > 0):?>
			<p>Only <?=$offer->availability;?> left!</p>
		<?php else:?>
			<p>Out of Stock!</p>
		<?php endif;?>
		<p id="offer-countdown" class="countdown"></p>
		<?php if($offer->image):?>
			<?=$this->html->image("/images/{$offer->image}.jpg");?>
		<?php endif;?>
		<p><?=$offer->description;?></p>
	</div>
	<div class="venue-informations">
		<ul style="list-style: none; border: 1px dashed #eee; width: 350px; padding:10px;">
			<li><?=$this->html->image("/images/{$venue->logo}.jpg")?></li>
			<li><?=$venue->name;?></li>
			<li><?=$venue->address;?></li>
		</ul>
	</div>
</div>
<script type="text/javascript">
$(function () {
	var couponEnd = new Date();
	couponEnd = new Date(<?php echo $offer->ends->sec * 1000;?>);
	$("#offer-countdown").countdown({until: couponEnd, layout: 'Ends in {dn} {dl}, {hnn}{sep}{mnn}{sep}{snn}'});
});
</script>