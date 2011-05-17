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
			<li id="offer-buy"><?php echo ($offer->availability) ? $this->html->link($this->html->image('buydeal-button.png'), array('Offers::buy', 'id'=>$offer->_id), array('id'=>'offer-buy-link', 'escape'=>false)): null; ?></li>
		</ul>
		<h3>Limitations</h3>
		<ul id="offer-restrictions">
			<?php foreach($offer->limitations as $limitation):?>
				<li><?=$limitation;?></li>
			<?php endforeach;?>
		</ul>
		<div id="share-offer-twitter"></div>
		<?=$this->facebook->like();?>
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
	<div id="map_container" style="margin-top: 20px; margin-bottom: 10px;">
		<h3>Location</h3>
		<div id="map_canvas" style="width: 700px; margin-top: 20px; height: 400px; margin-left: auto; margin-right: auto;"></div>
		<div id="map_error" style="display: none; margin-left: auto; margin-right: auto; width: 500px; margin-top: 10px;">
			<div style="padding: 10px; border: 1px solid #B9121B; background-color: #FF1914; color:#ffffff; text-align: center;">
				Sorry, we cannot display a map of the restaurant location.
			</div>
			<p style="margin-top: 40px;">The restaurant is located at: <?=$venue->address;?></p>
		</div>
	</div>
</div>
</div>
<div id="offer-buy-limitations-popup" style="display: none; z-index: 200; position: absolute; width: 350px; min-height: 100px; margin: 20px; padding: 20px; background-color: #ffffff; border: 4px solid #dddddd;">
	<h4>The Fine Print</h4>
	<div style="min-height: 50px; margin-left: 20px; margin-right: 20px;">
		<ul>
			<?php foreach($offer->limitations as $limitation):?>
				<li><?=$limitation;?></li>
			<?php endforeach;?>
		</ul>
	</div>
	<div style="margin-top: 20px; text-align: center;">
	<?php echo ($offer->availability) ? $this->html->link($this->html->image('buydeal-button.png'), array('Offers::buy', 'id'=>$offer->_id), array('id'=>'offer-buy-confirmed', 'escape'=>false)): null; ?>
	</div>
</div>
<div id="offer-buy-popup-bg" style="position: absolute; top: 0px; left: 0px; background-color: #444444; opacity: 0.4; display: none; width: 100%;"></div>
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
			$('#map_error').show();
		}
	});
}
$(function () {
	$('#offer-buy-popup-bg').bind('click', function(e){
		e.preventDefault();
		$('#offer-buy-limitations-popup').fadeOut(200);
		$(this).fadeOut(200);
	});
	$('#offer-buy-link').bind('click',function(e){
		e.preventDefault();
		var link = $(e.target).parent().attr('href');
		var pos = $("#offer-buy").offset();
		var top = pos.top - $("#offer-buy-limitations-popup").height() / 2;
		$("#offer-buy-limitations-popup").css( { "left": (pos.left - 20) + "px", "top": top + "px" } );
		$('#offer-buy-popup-bg').css({'height' : $(document).height()});
		$('#offer-buy-popup-bg').fadeIn(400);
		$("#offer-buy-limitations-popup").fadeIn(400);
	});
	function loadTwitter(){
		  var script = document.createElement("script");
		  script.type = "text/javascript";
		  script.src = "http://platform.twitter.com/widgets.js";
		  document.body.appendChild(script);
		  $('#share-offer-twitter').append($('<a href="http://twitter.com/share" class="twitter-share-button" data-text="<?=$offer->name;?>" data-count="horizontal">Tweet</a>'));
	}
	function loadScript() {
		  var script = document.createElement("script");
		  script.type = "text/javascript";
		  script.src = "http://maps.google.com/maps/api/js?v=3.2&sensor=false&callback=initialize_maps";
		  document.body.appendChild(script);
	}

	var couponEnd = new Date();
	couponEnd = new Date(<?php echo $offer->ends->sec * 1000;?>);
	$("#offer-countdown").countdown({until: couponEnd, layout: 'Ends in {dn} {dl}, {hnn}{sep}{mnn}{sep}{snn}'});	

	loadTwitter();
	loadScript();
});
</script>