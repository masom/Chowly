
<h1><?=$venue->name;?></h1>
<div class="whitebox">
	<?php if($venue->logo):?>
		<?=$this->html->image("/images/{$venue->logo}.jpg",array('style'=>'float:left;'));?>
	<?php endif;?>
	
	<ul style="list-style: none; margin: 0; margin-top: 20px; float: left; width: 400px;">
		<li><?=$venue->name;?></li>
		<li><?=$venue->address;?></li>
		<li><?=$venue->phone;?>
	</ul>
	<br style="clear: both;" />
</div>
<div class="whitebox" style="width: 400px; float:left;">
	<h3>About the venue</h3>
	<p><?php echo nl2br($venue->description);?></p>
</div>
<div class="whitebox" style="float: right;">
	<h3>Offers</h3>
	<?php if(!count($offers)):?>
		<p>This venue currently has no offers</p>
	<?php endif;?>
	<ul>
		<?php foreach($offers as $offer):?>
			<li><?=$this->html->link($offer->name, array('Offers::view', 'id'=> $offer->_id));?></li>
		<?php endforeach;?>
	</ul>
</div>
