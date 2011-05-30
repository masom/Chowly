<?php
$this->html->script('chowly-offers', array('inline'=>false));
$address = explode(",", $venue->address);
$expiration = ($offer->expiry) ? $offer->expiry->sec : null;
$available = ($offer->availability > 0);
if($expiration && $available):
	$available = ($expiration < time());
endif;
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
		<ul id="offer-details" data-address="<?=addslashes($venue->address);?>" data-ends="<?=$offer->ends->sec;?>">
			<li id="offer-address"><?=$address[0];?></li>
			<li id="offer-countdown" class="countdown"></li>
			<li id="offer-remaining"><?=($offer->availability > 0) ? "Only {$offer->availability} left!" : "Sold Out!"; ?></li>
			<?php if ($expiration):?>
				<li>Expires: <?=date('F, j, Y', $expiration);?></li>
			<?php endif;?>
			<li id="offer-buy"><?php echo ($available) ? $this->html->link($this->html->image('buydeal-button.png'), array('Offers::buy', 'id'=>$offer->_id), array('id'=>'offer-buy-link', 'escape'=>false)): null; ?></li>
		</ul>
		<div id="share-offer" style="margin-top: 20px; width: 270px; overflow: hidden;">
			<div id="share-offer-twitter"></div>
			<?=$this->facebook->like();?>
		</div>
		<h3>Limitations</h3>
		<ul id="offer-restrictions">
			<?php foreach($offer->limitations as $limitation):?>
				<li><?=$limitation;?></li>
			<?php endforeach;?>
		</ul>

	</div>
	
	<div id="venue-informations" style="width: 550px; float:right;">
		<?php if($venue->image):?>
			<div style="max-width: 550px; overflow:hidden; border: 2px solid #eeeeee;">
				<?=$this->html->image("/images/{$venue->image}.jpg")?>
			</div>
		<?php endif;?>
		
		<p style="margin-top: 20px;"><?php echo nl2br($h($venue->description));?></p>
	</div>
	<br style="clear: both;" />
	<a id="map"></a>
	<div id="map_container" style="display: none;background-color: #ffffff; width: 700px; height: 500px;">
		
		<h3>Location</h3>
		<div id="map_canvas" style="width: 700px; margin-top: 20px; height: 400px; margin-left: auto; margin-right: auto;"></div>
		<div id="map_error" style="display: none; margin-left: auto; margin-right: auto; width: 500px; margin-top: 10px;">
			<div style="padding: 10px; border: 1px solid #B9121B; background-color: #FF1914; color:#ffffff; text-align: center;">
				Sorry, we cannot display a map of the restaurant location.
			</div>
			<p style="margin-top: 40px;">The restaurant is located at: <?=$venue->address;?></p>
		</div>
	</div>
</div>
</div>
<div id="offer-buy-limitations-popup" style="display: none; z-index: 200; position: absolute; width: 350px; min-height: 100px; margin: 20px; padding: 20px; background-color: #ffffff; border: 4px solid #dddddd;">
	<h4>The Fine Print</h4>
	<div style="min-height: 50px; margin-left: 20px; margin-right: 20px;">
		<ul>
			<?php foreach($offer->limitations as $limitation):?>
				<li><?=$limitation;?></li>
			<?php endforeach;?>
		</ul>
	</div>
	<div style="margin-top: 20px; text-align: center;">
	<?php echo ($offer->availability) ? $this->html->link($this->html->image('buydeal-button.png'), array('Offers::buy', 'id'=>$offer->_id), array('id'=>'offer-buy-confirmed', 'escape'=>false)): null; ?>
	</div>
</div>
<div style="display: none;">
	<div id="offer-map-content">
		<p><?=$venue->name;?></p><p><?=$venue->address;?></p><p><?=$venue->phone;?></p>
	</div>
	<div id="offer-twitter-data">
		<a href="http://twitter.com/share" class="twitter-share-button" data-text="<?=addslashes($offer->name);?>" data-count="horizontal">Tweet</a>
	</div>
</div>
<div id="offer-buy-popup-bg" style="position: absolute; top: 0px; left: 0px; background-color: #444444; opacity: 0.4; display: none; width: 100%; filter:alpha(opacity=40)"></div>
<script type="text/javascript">
$(function () {
	OfferHandler.init();
	OfferHandler.social.Twitter();
});
</script>