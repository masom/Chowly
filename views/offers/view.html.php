<?php
$address = explode(",", $venue->address);
$expiration = ($offer->expiry) ? $offer->expiry->sec : null;
?>
<div id="ribbon">
	<span><?=$venue->name;?></span>
</div>
<div id="content-wrapper">
<div style="margin-left: 15px; margin-right: 15px; margin-top: 20px;">

	<h1 style="margin-bottom: 30px;"><?=$offer->name;?></h1>
	
	<div id="offer-informations" style="float:left; width: 270px;">
		
		<div id="offer-information-logo">
			<?=$this->html->image("/images/{$venue->logo}.jpg")?>
		</div>
		
		<ul id="offer-details">
			<li id="offer-address"><?=$address[0];?></li>
			<li id="offer-countdown" class="countdown"></li>
			<li id="offer-remaining"><?=($offer->availability > 0) ? "Only {$offer->availability} left!" : "Sold Out!"; ?></li>
			<?php if ($expiration):?>
				<li>Expires: <?=date('F, j, Y', $expiration);?></li>
			<?php endif;?>
			<li id="offer-buy"><?php echo ($offer->availability) ? $this->html->link($this->html->image('buydeal-button.png'), array('Offers::buy', 'id'=>$offer->_id), array('id'=>'offer_buy', 'escape'=>false)): null; ?></li>
		</ul>
		<h3>Restrictions</h3>
		<ul id="offer-restrictions">
			<li>Only valid for dinner.</li>
			<li>Not valid on holidays.</li>
			<li>Cannot be combined with another offer.</li>
		</ul>
	</div>
	
	<div id="venue-informations" style="width: 550px; float:right;">
		<?php if($venue->image):?>
			<div style="max-width: 550px; overflow:hidden; border: 2px solid #eeeeee;">
				<?=$this->html->image("/images/{$venue->image}.jpg")?>
			</div>
		<?php endif;?>
		
		<p style="margin-top: 20px;"><?php echo nl2br($h($venue->description));?></p>
	</div>
	<br style="clear: both;" />
	<div id="map_container" style="margin-top: 20px; margin-bottom: 10px; margin-left: auto; margin-right: auto;">
		<h3>Location</h3>
		<div id="map_canvas" style="width: 750px; height: 400px;"></div>
		<div id="map_error" style="margin-left: auto; margin-right: auto; width: 500px; margin-top: 10px;">
			<div style="padding: 10px; border: 1px solid #B9121B; background-color: #FF1914; color:#ffffff; text-align: center;">
				Sorry, we cannot display a map of the restaurant location.
			</div>
			<p style="margin-top: 40px;">The restaurant is located at: <?=$venue->address;?></p>
		</div>
	</div>
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
			$('#map_canvas').remove();
		}
	});
}
$(function () {
	function loadScript() {
		  var script = document.createElement("script");
		  script.type = "text/javascript";
		  script.src = "http://maps.google.com/maps/api/js?v=3.2&sensor=false&callback=initialize_maps";
		  document.body.appendChild(script);
	}
	loadScript();

	var couponEnd = new Date();
	couponEnd = new Date(<?php echo $offer->ends->sec * 1000;?>);
	$("#offer-countdown").countdown({until: couponEnd, layout: 'Ends in {dn} {dl}, {hnn}{sep}{mnn}{sep}{snn}'});	
});
</script>