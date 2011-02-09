<h1>Offers</h1>
<table>
	<thead>
		<tr><th>Name</th><th>Starts</th><th>Ends</th></tr>
	</thead>
<?php foreach($offers as $offer):?>
	<tr>
		<td><?=$offer->name;?></td>
		<td><?=date('Y-m-d H:i:s',$offer->starts->sec);?></td>
		<td><?=date('Y-m-d H:i:s',$offer->ends->sec);?></td>
		<td><?php echo ($offer->state == 'published')? $this->html->link('Unpublish',array('Offers::unpublish','id'=>$offer->_id,'admin'=>true)) : $this->html->link('Publish',array('Offers::publish','id'=>$offer->_id,'admin'=>true)); ?>
	</tr>
<?php endforeach;?>
</table>