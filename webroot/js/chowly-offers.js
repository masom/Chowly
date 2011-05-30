var OfferHandler = {
	init: function(){
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
		var couponEnd = new Date($('#offer-details').attr('data-ends') * 1000);
		$("#offer-countdown").countdown({until: couponEnd, layout: 'Ends in {dn} {dl}, {hnn}{sep}{mnn}{sep}{snn}'});
		OfferHandler.maps.Canvas = document.getElementById("map_canvas");
		$('#offer-address').bind('click', function(e){
			OfferHandler.maps.init();
			location.href = '#map';
			e.preventDefault();
			return false;
		});
	},
	maps: {
		hasRunInit: false,
		init: function(){
			if(this.hasRunInit){
				return true;
			}
			this.hasRunInit = true;
			$('#map_container').show();
			var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = "http://maps.google.com/maps/api/js?v=3.2&sensor=false&callback=OfferHandler.maps.show";
			document.body.appendChild(script);
		},
		show: function(){
			OfferHandler.maps.Geocoder = new google.maps.Geocoder();
			OfferHandler.maps.Map = new google.maps.Map(OfferHandler.maps.Canvas, {zoom: 13,  mapTypeId: google.maps.MapTypeId.ROADMAP});

			OfferHandler.maps.Geocoder.geocode( { address: $('#offer-details').attr('data-address')}, OfferHandler.maps.geocoderHandler);
		},
		Canvas: null,
		Geocoder: null,
		geocoderHandler: function(results, status){
			if(status != google.maps.GeocoderStatus.OK){
				$('#map_canvas').remove();
				$('#map_error').show();
				return;
			}
			OfferHandler.maps.Map.setCenter(results[0].geometry.location );
			OfferHandler.maps.Marker = new google.maps.Marker({
				map: OfferHandler.maps.Map,
				visible: true,
				position: results[0].geometry.location
			});
			OfferHandler.maps.InfoWindow = new google.maps.InfoWindow({
				content: $('#offer-map-content').html()
			});
			OfferHandler.maps.InfoWindow.open(OfferHandler.maps.Map, OfferHandler.maps.Marker);
			google.maps.event.addListener(OfferHandler.maps.Marker, 'click', function(){
				OfferHandler.maps.InfoWindow.open(OfferHandler.maps.Map, OfferHandler.maps.Marker);
			});
		},
		InfoWindow: null,
		Marker: null,
		Map: null
	},
	social: {
		Twitter: function(){
			var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = "http://platform.twitter.com/widgets.js";
			document.body.appendChild(script);
			$('#share-offer-twitter').append($('#offer-twitter-data').html());
		}
	},
};