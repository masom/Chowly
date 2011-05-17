<?php 
$here = urlencode($this->url(null, array('absolute'=>true)));
$shareText = urlencode("Great Ottawa restaurant deals!");
?>
<div id="share">
	<ul>
		<li><a id="share-twitter" href="http://twitter.com/share?text=<?=$shareText;?>&counturl=<?=$here;?>" target="_blank"><img src="/img/twitter-icon.png" alt="Share with Twitter" /></a></li>
		<li><a id="share-facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=<?=$here;?>&t=<?=$shareText?>"><img src="/img/facebook-icon.png" alt="Share with Facebook" /></a></li>
		<li><img src="/img/email-icon.png" alt="Share by Email" /></li>
	</ul>
</div>
<div id="ribbon">
	<span>Tonight's Deals</span>
</div>
<div id="content-wrapper">
	<?php if(count($offers)): ?>
		<div class="offer-name-ribbon"></div>
	<?php endif;?>
	<ul class="offers-list">
		<?php 
			$style = true;
			$i = 0;
		?>
		<?php foreach($offers as $offer):?>

		<?php if($i == 3): $i = 0;?>
			</ul>
			<div style="width: 857px; height: 11px; background-image: url(/img/separator.png);"></div>
			<div class="offer-name-ribbon"></div>
			<ul class="offers-list">
		<?php endif;?>
			<?php $listyle = ($style) ? 'style="background-image: url(/img/dealbg-grey.png);"' : 'style="width: 280px; overflow:hidden;"';?>
			<li id="offer-<?=$offer->_id;?>" class="offer" <?php echo $listyle?>>
				<div class="offer-info">
					<div class="offer-venue-logo">
						<?php if($offer->venue_id && isset($venues[(string)$offer->venue_id])):?>
							<?=$this->html->image("/images/{$venues[(string)$offer->venue_id]}.jpg");?>
						<?php endif;?>
					</div>

					<p class="offer-remaining"><?=($offer->availability)? "Only {$offer->availability} left!" : 'Sold Out!' ;?></p>
					<p class="offer-name"><?=$this->html->link($offer->name, array('Offers::view', 'id'=> $offer->_id));?></p>
					
					<p id="offer-countdown-<?php echo $offer->_id;?>" class="offer-countdown"></p>
					<?php if($offer->slug):?>
						<?=$this->html->link($this->html->image('view-deal.png'), array('controller'=>'offers','action'=>'view','slug'=>$offer->slug),array('escape'=>false,'class'=>'offer-buy-button'));?>
					<?php else:?>
						<?=$this->html->link($this->html->image('view-deal.png'), array('controller'=>'offers','action'=>'view','id'=>$offer->_id),array('escape'=>false,'class'=>'offer-buy-button'));?>
					<?php endif;?>
				</div>
			</li>
		<?php $i++; $style = !$style;?>
		<?php endforeach;?>
	</ul>
</div>
<script type="text/javascript">
$(function () {
	var share = function(e){
		e.preventDefault();
		var w = window.open($(this).attr('href'), 'chowly-share', 'height=300,width=400');
		if(w.focus){ w.focus();}
		return false;
	}
	$('#share-facebook').bind('click', share);
	$('#share-twitter').bind('click', share);

	var couponEnd;
	<?php foreach($offers as $offer):?>
			couponEnd = new Date(<?php echo $offer->ends->sec * 1000;?>);
			$("#offer-countdown-<?php echo $offer->_id;?>").countdown({until: couponEnd, layout: 'Ends in {dn} {dl}, {hnn}{sep}{mnn}{sep}{snn}'});
		
	<?php endforeach;?>
});
</script>
