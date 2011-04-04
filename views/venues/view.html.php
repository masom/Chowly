<div id="ribbon">
	<span><?=$venue->name;?></span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<div>
			<?php if($venue->logo):?>
				<?=$this->html->image("/images/{$venue->logo}.jpg",array('style'=>'float:left;'));?>
			<?php endif;?>
			
			<ul style="list-style: none; margin: 20px; float: left; width: 400px;">
				<li><?=$venue->name;?></li>
				<li><?=$venue->address;?></li>
				<li><?=$venue->phone;?>
			</ul>
		</div>
		<div style="width: 400px; float:left;">
			<h3>About the venue</h3>
			<p><?php echo nl2br($venue->description);?></p>
		</div>
		<div style="float: right;">
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
	</div>
	<br style="clear:both;" />
</div>