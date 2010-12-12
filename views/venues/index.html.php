<div id="content-header">
<h1>Venues</h1>
</div>

<div id="content-panel">
	<?php if(!count($venues)):?>
	<p class="empty-results">
		Sorry, there are currently no venues.
		<br />
		<br />
		Come Back Soon!
		<br />
		<br />
		~Chowly
	</p>
	<?php endif;?>
	<ul class="venues">
	<?php foreach($venues as $venue):?>
		<li>
			<?=$this->html->image("/images/{$venue->logo}.gif");?>
			<h4><?=$this->html->link($venue->name, array('Venues::view', 'id'=> $venue->_id));?></h4>
			<p class="venue-informations"><?=$venue->address;?></p>
			<p class="venue-informations"><?=$venue->phone?></p>
		</li>
	<?php endforeach;?>
</ul>
</div>