<div style="padding-left: 15px; height: 43px; line-height: 43px; color: #ffffff; font-size: 24px; font-weight: bold; background: url(/img/top-ribbon.png);">
	Ottawa Restaurant Deals
</div>
<div id="content-wrapper">
	<ul class="offers-list">
		<?php 
			$i = 0;
			$perLine = 3;
		?>
		<?php foreach($offers as $offer):?>
			<?php $style = ($i == 1) ? 'style="background-image: url(/img/dealbg-grey.png);"' : null;?>
			<li <?=$style?>>
				<h1><?=$this->html->link($offer->name, array('Offers::view', 'id'=> $offer->_id));?></h1>
				<?php if($offer->venue_id && isset($venues[(string)$offer->venue_id])):?>
					<?=$this->html->image("/images/{$venues[(string)$offer->venue_id]}.jpg");?>
				<?php endif;?>
				<div class="footer">
					<span><?=($offer->availability)? "Only {$offer->availability} left!" : 'Sold Out!' ;?></span>
					<span style="float:right;" id="offer-countdown-<?php echo $offer->_id;?>" class="countdown"></span>
				</div>		
			</li>
		<?php 
		$i++;
		endforeach;?>
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
