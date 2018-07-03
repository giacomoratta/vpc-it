(function($) {
    $.fn.vpc_gmap = function(options) {

    // Check google.maps
    if(
    	(typeof google != 'object') ||
    	(typeof google.maps.LatLng != 'function')
    ) { $(this).css({'height':'auto'});  return; }
    
	// Elemento
	var $this = $(this);
	//$this_w = $this.contents().filter('.wrapper');
	
	
	// DATI e OPZIONI MAPPA
	var data = {
       	'lat': 0,
		'lng': 0,
		'zoom': 5,
		'markers': null,
		'info_maxWidth':500,
		'new_marker_fn':null,
    };
	if(options) $.extend(data, options);
	if(data.lat==0 || data.lng==0) return;
	
	
	var mapOptions = {
    	zoom: data.zoom,
    	center: new google.maps.LatLng(data.lat, data.lng),
		panControl: false,
		streetViewControl: false,
		scrollwheel: false,
		zoomControl: true,
    	zoomControlOptions: { style:google.maps.ZoomControlStyle.SMALL },
		mapTypeControl: true,
    	mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU },
    	mapTypeId: google.maps.MapTypeId.ROADMAP
  	};
	google.maps.visualRefresh = true;
  	var $map = new google.maps.Map(document.getElementById($this.attr('id')),mapOptions);
	var $markers = new Array();
	var $infowindows = new Array();
	
	
	// Inserisce marker	
	if(data.markers!=null && (data.markers instanceof Array))
	{
		$.each(data.markers, function(i,m)
		{
			/* Imposto il marker */
			var marker_arg = {
	    		position: new google.maps.LatLng(m.lat,m.lng),
	    		map: $map,
	  		};
			if( !(m.icon==undefined || m.icon==null || m.icon.length<=0) )
			{
				marker_arg.icon = m.icon;
			}

			/* Creo il marker */
			$markers[i] = new google.maps.Marker(marker_arg);
			
			/* Aggiungo fumetto, se c'e' il testo */
			if(!(m.txt==undefined || m.txt==null || m.txt.length<=0))
			{
				$infowindows[i] = new google.maps.InfoWindow({ content:m.txt, maxWidth:data.info_maxWidth, });
				
				google.maps.event.addListener($markers[i], 'click', function()
				{
					$.each($infowindows, function(i){ $infowindows[i].close(); });
					$infowindows[i].open($map, $markers[i]);
				});
			}
		});
	}
	

	
	
	// Crea marker con un click
	$this.remove_single_marker = function($single_marker)
	{
		data.new_marker_fn("","");
		$single_marker.setMap(null);
		return null;
	}	
	
	var $single_marker = null;
	if(data.new_marker_fn!=null)
	{
		/* Inizializzazione dati */
		if($markers.length>0)
		{
			$single_marker=$markers[0];
			$.each($markers, function(i) { if(i!=0) $markers[i].setMap(null); });
			google.maps.event.addListener($single_marker, 'click', function()
			{
				$single_marker = $this.remove_single_marker($single_marker);
			});
		}
		
		google.maps.event.addListener($map, 'click', function(event)
		{
			// Chiama la funzione new_marker_fn()
			data.new_marker_fn(event.latLng.lat(),event.latLng.lng());
			
			// Elimina tutti i marker presenti sulla mappa (ci deve essere un solo marker)
			if($single_marker!=null) $single_marker.setMap(null);
			
			// Nuovo marker
			$single_marker = new google.maps.Marker({
    			position: event.latLng,
    			map: $map,
  			});
			
			// Eliminare il marker con un click
			google.maps.event.addListener($single_marker, 'click', function()
			{
				$single_marker = $this.remove_single_marker($single_marker);
			});
		});
	}	
	
		
}})(jQuery);
