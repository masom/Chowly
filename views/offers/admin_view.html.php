<div id="ribbon">
	<span>Deal Preview</span>
</div>
<div id="content-wrapper">
	<div style="margin:20px;">

		<h3>Offer</h3>
		<p><?=$offer->name;?></p>
		<ul>
			<?php if($offer->image):?>
				<li><?=$this->html->image("/images/{$offer->image}.jpg");?></li>
			<?php endif;?>
			<li><em>Availability</em>: <?=$offer->availability;?></li>
			<li><em>Starts</em>: <?php echo date('Y-m-d H:i:s', $offer->starts->sec);?></li>
			<li><em>Ends</em>: <?php echo date('Y-m-d H:i:s',$offer->ends->sec);?></li>
		</ul>
		<p style="text-align: justify;"><?php echo nl2br($offer->description);?></p>

		<h3>Venue</h3>
		<?php if($venue->logo):?>
			<?=$this->html->image("/images/{$venue->logo}.jpg")?>
		<?php endif;?>
		<h4>Location</h4>
		<ul style="list-style: none;">
			<li><?=$venue->name;?></li>
			<li><?=$venue->address;?></li>
		</ul>
		<br />
		<?php if($offer->state == 'unpublished'):?>
			<?=$this->html->link('Publish', array('Offers::publish', 'id'=>$offer->_id,'admin'=>true),array('id'=>'publish'));?>
		<?php else:?>
			<?=$this->html->link('Unpublish', array('Offers::unpublish', 'id'=>$offer->_id,'admin'=>true),array('id'=>'publish'));?>
		<?php endif;?>
		<script type="text/javascript">
		$('#publish').button();
		</script>
	</div>
</div>