<div id="ribbon">
	<span>Purchase Confirmation</span>
</div>
<div id="content-wrapper">
	<div id="empty_cart" style="display: none; margin-left: auto; margin-right: auto;">
		<p>Your cart is currently empty.</p>
		<p><?=$this->html->link('Take me back to the main page.', '/');?>
	</div>
	
	<div style="margin-left: 20px; margin-right: 20px;">
	<?php $total = 0;?>
	<table id="offers">
		<thead>
			<tr><th></th><th>Expires</th><th>Name</th><th>Price</th></tr>
		</thead>
		<?php foreach($offers as $offer):
		$total += $offer->cost;
		$offer_id = $offer->_id;
		$cart_item = $cart_items->first(function($i) use ($offer_id) { return $i->_id == $offer_id; });
		?>
		<tr id="offer_<?=$offer->_id;?>">
			<td><?=$this->html->image('silk/cart_delete.png', array('id'=>"offer_{$offer->_id}_remove"))?></td>
			<td><?php echo date('H:i:s', $cart_item['expires']);?></td>
			<td><?=nl2br($offer->name);?></td>
			<td>$<?=$offer->cost;?></td>
		</tr>
		<?php endforeach;?>
	</table>
	<p>Total: $<?=$total;?></p>
	<?=$this->html->link('Proceed to Checkout', array('Checkouts::checkout'), array('id'=>'CheckoutGo'));?>
	</div>
</div>

<script type="text/javascript">
$('#CheckoutGo').button();
<?php foreach($offers as $offer):?>
	$('#offer_<?=$offer->_id;?>_remove').button();
	$('#offer_<?=$offer->_id;?>_remove').bind('click', function(){
		$(this).hide();
		$.ajax({
			  url: "<?=$this->url(array('Carts::remove', 'id'=>$offer->_id));?>",
			  context: document.body,
			  success: function(data){
				  if(data.cleared){
					  $('#offer_' + data.id).remove();
				  }
				  if($('#offers tr').length == 1){
					  $('#CheckoutGo').hide();
					  $('#empty_cart').show();
				  }
			  }
			});
	});
<?php endforeach;?>
</script>