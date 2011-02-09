<h1>Purchase Confirmation</h1>
	
<?php foreach($offers as $offer):?>
	<div class="whitebox" style="float:left; width:400px;">
		<?php if($offer->image):?>
			<?=$this->html->image("/images/{$offer->image}.jpg");?>
		<?php endif;?>
		<p><?php echo nl2br($offer->name);?></p>
		<p><?php echo nl2br($offer->description);?></p>
		<p>Inventory reservation expires at <?php echo date('H:i:s', $cart[(string)$offer->_id]['expires']);?></p>
	</div>
<?php endforeach;?>

<?=$this->html->link('Proceed to Checkout', array('Checkouts::checkout'), array('id'=>'CheckoutGo'));?>

<script type="text/javascript">
$('#CheckoutGo').button();
</script>