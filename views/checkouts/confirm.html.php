<h1>Purchase Confirmation</h1>
<div id="empty_cart" style="display: none; margin-left: auto; margin-right: auto;">
	<p>Your cart is currently empty.</p>
	<p><?=$this->html->link('Take me back to the main page.', '/');?>
</div>
<div style="float:left; width:400px;" id="offers">
<?php foreach($offers as $offer):?>
	<div class="whitebox" id="offer_<?=$offer->_id;?>">
		<?=$this->html->image('silk/cart_delete.png', array('id'=>"offer_{$offer->_id}_remove"))?>
		<?php if($offer->image):?>
			<?=$this->html->image("/images/{$offer->image}.jpg");?>
		<?php endif;?>
		<p><?php echo nl2br($offer->name);?></p>
		<p><?php echo nl2br($offer->description);?></p>
		<p>Inventory reservation expires at <?php echo date('H:i:s', $cart[(string)$offer->_id]['expires']);?></p>
	</div>
<?php endforeach;?>
</div>
<?=$this->html->link('Proceed to Checkout', array('Checkouts::checkout'), array('id'=>'CheckoutGo'));?>

<script type="text/javascript">
$('#CheckoutGo').button();
<?php foreach($offers as $offer):?>
	$('#offer_<?=$offer->_id;?>_remove').button();
	$('#offer_<?=$offer->_id;?>_remove').bind('click', function(){
		$.ajax({
			  url: "<?=$this->url(array('Carts::remove', 'id'=>$offer->_id));?>",
			  context: document.body,
			  success: function(data){
				  if(data.cleared){
					  $('#offer_' + data.id).remove();
				  }
				  if($('#offers').children().size() == 0){
					  $('#CheckoutGo').hide();
					  $('#empty_cart').show();
				  }
			  }
			});
	});
<?php endforeach;?>
</script>