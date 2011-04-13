<div id="ribbon">
	<span>Ottawa Restaurant Deals</span>
</div>
<div id="content-wrapper">
	<div class="offer-name-ribbon"></div>
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
					<?=$this->html->link($this->html->image('buydeal-button.png'), array('controller'=>'offers','action'=>'view','id'=>$offer->_id),array('escape'=>false,'class'=>'offer-buy-button'));?>
				</div>
			</li>
		<?php $i++; $style = !$style;?>
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
