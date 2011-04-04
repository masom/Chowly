<div id="ribbon">
	<span><?=$offer->name;?></span>
</div>
<div id="content-wrapper">
<div style="margin-left: 15px; margin-right: 15px; margin-top: 20px;">


	<div class="whitebox" style="float:left; width:350px;">
		
		<p class="offer-remaining" style="text-align: left;">
			<?php if($offer->availability > 0):?>
				Only <?=$offer->availability;?> left!
			<?php else:?>
				Sold Out!
			<?php endif;?>
		</p>
		<p id="offer-countdown" class="countdown"></p>

		<?php if($offer->image):?>
			<?=$this->html->image("/images/{$offer->image}.jpg");?>
		<?php endif;?>
		
		<h3>Details</h3>
		<p style="margin-bottom: 40px;"><?php echo nl2br($offer->description);?></p>
		
		<?php echo ($offer->availability) ? $this->html->link('Buy', array('Offers::buy', 'id'=>$offer->_id),array('id'=>'offer_buy')): null; ?>
	</div>
	<div class="whitebox" style="width: 450px; float:right;">
		<h3>About the venue</h3>
		<?php if($venue->logo):?>
			<?=$this->html->image("/images/{$venue->logo}.jpg")?>
		<?php endif;?>
		<p style="margin-top: 20px;"><?php echo nl2br($venue->description);?></p>
		
		<h3 style="margin-top: 20px;">Location</h3>
		<ul style="list-style: none;">
			<li><?=$venue->name;?></li>
			<li><?=$venue->address;?></li>
		</ul>
		<div id="map_canvas" style="width: 380px; height: 380px; margin-top: 20px; margin-bottom: 10px;"></div>
	</div>
	<br style="clear: both;" />
</div>
</div>
<script type="text/javascript">
var map;
var geocoder;
var infoWindow;
var marker;
function initialize_maps() {
	geocoder = new google.maps.Geocoder();
	var myLatlng = new google.maps.LatLng(-34.397, 150.644);
	var myOptions = {
		zoom: 14,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	geocoder.geocode( { 'address': '<?=$venue->address;?>'}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			marker = new google.maps.Marker({
				map: map,
				visible: true,
				position: results[0].geometry.location
			});
			infoWindow = new google.maps.InfoWindow({
				content: "<p><?=addslashes($venue->name);?></p><p><?=addslashes($venue->address);?></p><p><?=addslashes($venue->phone);?></p>"
			});
			infoWindow.open(map,marker);
			google.maps.event.addListener(marker, 'click', function() {
				infoWindow.open(map,marker);
			});
		}else{
			$('#map_canvas').hide();
		}
	});
	

}
$(function () {
	$('#offer_buy').button();
	
	var couponEnd = new Date();
	couponEnd = new Date(<?php echo $offer->ends->sec * 1000;?>);
	$("#offer-countdown").countdown({until: couponEnd, layout: 'Ends in {dn} {dl}, {hnn}{sep}{mnn}{sep}{snn}'});
		  
	function loadScript() {
	  var script = document.createElement("script");
	  script.type = "text/javascript";
	  script.src = "http://maps.google.com/maps/api/js?v=3.2&sensor=false&callback=initialize_maps";
	  document.body.appendChild(script);
	}
	loadScript();
	
});
</script>