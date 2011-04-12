<?php
$address = explode(",", $venue->address);
$expiration = ($offer->expiry) ? $offer->expiry->sec : null;
?>
<div id="ribbon">
	<span><?=$venue->name;?></span>
</div>
<div id="content-wrapper">
<div style="margin-left: 15px; margin-right: 15px; margin-top: 20px;">

	<h1 style="margin-bottom: 30px;"><?=$offer->name;?></h1>
	
	<div id="offer-informations" style="float:left; width: 270px;">
		
		<div id="offer-information-logo">
			<?=$this->html->image("/images/{$venue->logo}.jpg")?>
		</div>
		
		<ul style="list-style: none;">
			<li id="offer-address"><?=$address[0];?></li>
			<li id="offer-countdown" class="countdown"></li>
			<li id="offer-remaining"><?=($offer->availability > 0) ? "Only {$offer->availability} left!" : "Sold Out!"; ?></li>
			<?php if ($expiration):?>
				<li>Expires: <?=date('F, j, Y', $expiration);?></li>
			<?php endif;?>
			<li><?php echo ($offer->availability) ? $this->html->link($this->html->image('buydeal-button.png'), array('Offers::buy', 'id'=>$offer->_id), array('id'=>'offer_buy', 'escape'=>false)): null; ?></li>
		</ul>
		<h3>Restrictions</h3>
		<ul style="list-style: none;">
			<li>Only valid for dinner.</li>
			<li>Not valid on holidays.</li>
			<li>Cannot be combined with another offer.</li>
		</ul>
	</div>
	
	<div id="venue-informations" style="width: 550px; float:right;">
		<?php if($venue->image):?>
			<div style="max-width: 550px; overflow:hidden; border: 2px solid #eeeeee;">
				<?=$this->html->image("/images/{$venue->image}.jpg")?>
			</div>
		<?php endif;?>
		
		<p style="margin-top: 20px;"><?php echo nl2br($h($venue->description));?></p>
		
		<h3 style="margin-top: 20px;">Location</h3>
		<ul style="list-style: none;">
			<li><?=$venue->name;?></li>
			<li><?=$venue->address;?></li>
		</ul>
	</div>
	<br style="clear: both;" />
</div>
</div>
<script type="text/javascript">
$(function () {
	
	var couponEnd = new Date();
	couponEnd = new Date(<?php echo $offer->ends->sec * 1000;?>);
	$("#offer-countdown").countdown({until: couponEnd, layout: 'Ends in {dn} {dl}, {hnn}{sep}{mnn}{sep}{snn}'});	
});
</script>