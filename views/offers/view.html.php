
	<h1><?=$offer->name;?></h1>

	<div class="whitebox" style="float:left; width:380px;">
		<?php if($offer->availability > 0):?>
			<p>Only <?=$offer->availability;?> left!</p>
		<?php else:?>
			<p>Sold Out!</p>
		<?php endif;?>
		<p id="offer-countdown" class="countdown"></p>
		<?php if($offer->availability > 0):?>
		<?=$this->html->link('Buy', array('Offers::buy', 'id'=>$offer->_id),array('id'=>'offer_buy'));?>
		<?php endif;?>
		<?php if($offer->image):?>
			<?=$this->html->image("/images/{$offer->image}.jpg");?>
		<?php endif;?>
		<p><?php echo nl2br($offer->description);?></p>
	<div id="map_canvas" style="width: 380px; height: 380px;"></div>
	</div>
	<div class="whitebox" style="width: 300px; float:left;">
		<?php if($venue->logo):?>
			<?=$this->html->image("/images/{$venue->logo}.jpg")?>
		<?php endif;?>
		<h3>Location</h3>
		<ul style="list-style: none;">
			<li><?=$venue->name;?></li>
			<li><?=$venue->address;?></li>
		</ul>
		<h3>About the venue</h3>
		<p style="margin-top: 20px;"><?php echo nl2br($venue->description);?></p>
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