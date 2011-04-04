<div id="ribbon">
	<span><?=$venue->name?></span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">
		<?php if($venue->logo):?>
			<?=$this->html->image("/images/{$venue->logo}.jpg",array('style'=>'float:left;'));?>
		<?php endif;?>
		
		<ul style="list-style: none; margin: 0; margin-top: 20px; float: left; width: 400px;">
			<li><?=$venue->name;?></li>
			<li><?=$venue->address;?></li>
			<li><?=$venue->phone;?>
		</ul>
		<br style="clear: both;" />
		<h3>About the venue</h3>
		<p><?php echo nl2br($venue->description);?></p>

		<h3>Offers</h3>
		<table>
			<thead>
				<tr>
					<th>State</th><th>Name</th><th>Availability</th><th>Inventory</th><th>Actions</th>
				</tr>
			</thead>
			<?php foreach($offers as $offer):?>
				<tr>
					<td><?=$offer->state;?></td>
					<td><?=$offer->name;?></td>
					<td><?=$offer->availability;?></td>
					<td><?=$offer->inventoryCount;?></td>
					<td>
						<?=$this->html->link('View', array('Offers::view', 'id'=> $offer->_id, 'admin'=>true));?>
						<?php if($offer->state == 'published'):?>
							<?=$this->html->link('Unpublish', array('controller'=>'offers','action'=>'unpublish','admin'=>true,'id'=>$offer->_id));?>
						<?php else:?>
							<?=$this->html->link('Publish', array('controller'=>'offers','action'=>'publish','admin'=>true,'id'=>$offer->_id));?>
						<?php endif;?>
						<a id="offer_<?=$offer->_id;?>_state_changer" href="#"><?php ($offer->state == 'published')? "Unpublish" : "Publish";?></a>
					</td>
				</tr>
			<?php endforeach;?>
		</table>
		
		<?php if(!count($offers)):?>
			<p>This venue currently has no offers</p>
		<?php endif;?>
	</div>
</div>