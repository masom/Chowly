<div id="content-header">
<h1>Venues / <?=$venue->name;?></h1>
</div>

<div id="content-panel">
	<div class="venue-infocard">
		<h4><?=$venue->name;?></h4>
		<ul>
			<li><?=$venue->phone;?></li>
			<li><?=$venue->address;?></li>
		</ul>
	</div>
	<div class="venue-offers">
		<h3>Offers</h3>
		<ul>
			<?php foreach($offers as $offer):?>
				<li><?=$this->html->link($offer->name, array('Offers::view', 'id'=> $offer->_id));?></li>
			<?php endforeach;?>
		</ul>
	</div>
	<p style="width: 400px; margin-left: 30px; margin-bottom: 30px; min-height: 300px; text-align: justify; float: left;">
		<?=$this->html->image("/images/{$venue->logo}.jpg", array('style'=>'float:left; display: inline;'));?>
		<?php echo nl2br($venue->description);?>
	</p>
	<br style="clear: both;" />
</div>