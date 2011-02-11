<h1>Purchase</h1>
<div>
<ul>
	<?php if($purchase->status == 'completed'):?>
		<li><?=$this->html->link('View Invoice', array('Purchases::download', 'id'=>$purchase->_id,'type'=>'pdf', $this->session->read('user.role') => true));?></li>
		<li><?=$this->html->link('Resend Invoice');?></li>
	<?php endif;?>
</ul>
</div>
<h3>Details</h3>
<table>
	<thead>
		<tr><th>date</th><th>cc</th><th>status</th><th>Total</th></tr>
	</thead>
	<tr>
		<td><?=date('Y-m-d H:i:s', $purchase->created->sec);?></td>
		<td><?=$purchase->cc_number;?></td>
		<td><?=$purchase->status;?></td>
		<td><?=number_format($purchase->price, 2, '.', ',');?></td>
	</tr>
</table>
<h3>Customer Details</h3>
<table>
	<thead>
		<tr><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>Province</th></tr>
	</thead>
	<tr>
		<td><?=$purchase->name;?></td>
		<td><?=$purchase->email;?></td>
		<td><?=$purchase->phone;?></td>
		<td><?=$purchase->city;?></td>
		<td><?=$purchase->province;?></td>
	</tr>
</table>
<h3>Offers</h3>

<table>
	<thead>
		<tr><th>Name</th><th>Venue Name</th><th>Charged Price</th></tr>
	</thead>
	<?php foreach($offers as $offer):
		$venue_id = $offer->venue_id;
		$offer_id = $offer->_id;
		$venue = $venues->first(function($i) use ($venue_id) { return (string) $i->_id == $venue_id; });
		$offer_price = $purchase->offers->first(function($i) use($offer_id) { return (string) $i->_id == $offer_id; })->price;
	?>
		<tr>
			<td><?=$offer->name;?></td>
			<td><?=$venue->name;?></td>
			<td><?=number_format($offer_price, 2, '.', ',');?>
		</tr>
	<?php endforeach;?>
</table>