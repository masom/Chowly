<div id="content-header">
	<h1><?=$offer->name;?></h1>
</div>
<div id="content-panel" style="margin-left: auto; margin-right: auto; width: 800px">
	<div class="offer-informations" style="float:left; width:400px;">
		<?php if($offer->availability > 0):?>
			<p>Only <?=$offer->availability;?> left!</p>
		<?php else:?>
			<p>Out of Stock!</p>
		<?php endif;?>
		<p id="offer-countdown" class="countdown"></p>
		<?php if($offer->availability > 0):?>
			<div style="padding: 10px; font-size: 14px; border: 1px solid #aaa; background-color:#ddd; border-radius: 9px; width: 50px; text-align: center;">
				<?=$this->html->link('Buy', array('Offers::buy', 'id'=>$offer->_id));?>
			</div>
		<?php endif;?>
		<?php if($offer->image):?>
			<?=$this->html->image("/images/{$offer->image}.jpg");?>
		<?php endif;?>
		<p><?php echo nl2br($offer->description);?></p>
	</div>
	<div class="venue-infocard" style="width: 300px; float:left;">
		<?php if($venue->logo):?>
			<?=$this->html->image("/images/{$venue->logo}.jpg")?>
		<?php endif;?>
		<h3>Location</h3>
		<ul style="list-style: none;">
			<li><?=$venue->name;?></li>
			<li><?=$venue->address;?></li>
		</ul>
		<h3>About the venue</h3>
		<p style="margin-top: 20px;"><?php echo nl2br($venue->description);?></p>
	</div>
</div>
<script type="text/javascript">
$(function () {
	var couponEnd = new Date();
	couponEnd = new Date(<?php echo $offer->ends->sec * 1000;?>);
	$("#offer-countdown").countdown({until: couponEnd, layout: 'Ends in {dn} {dl}, {hnn}{sep}{mnn}{sep}{snn}'});
});
</script>