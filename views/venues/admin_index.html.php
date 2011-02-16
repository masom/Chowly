<h1>Venues</h1>
<?=$this->html->link('Add a venue', array('Venues::add','admin'=>true));?>
<table>
	<thead>
		<tr><th></th><th>Name</th><th>Address</th><th>Status</th><th>Actions</th></tr>
	</thead>
<?php foreach($venues as $venue):?>
	<tr>
		<td><?=$this->html->link('+', array('Offers::add','id'=>$venue->_id,'admin'=>true));?></td>
		<td><?=$venue->name;?></td>
		<td><?=$venue->address;?></td>
		<td><?=$venue->state;?></td>
		<td><?=$this->html->link('Edit', array('Venues::edit','id'=>$venue->_id,'admin'=>true));?></td>
	</tr>
<?php endforeach;?>

</table>