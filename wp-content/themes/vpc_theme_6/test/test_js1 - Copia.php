<!doctype html>
<!--[if lte IE 7 ]> <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>     <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>     <html class="ie9"> <![endif]-->
<!--[if gt IE 9 ]>  <html class="ie"> <![endif]-->
<!--[if !(IE) ]><!--> <html class="not-ie"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>Test Google Map</title>

<script src="<?php echo $VPC->gmap_url; ?>"></script>
<script src="<?php echo $VPC->template_url; ?>script/jquery.min.js"></script>
<script src="<?php echo $VPC->template_url; ?>script/vpc_gmap.jquery.js"></script>

<script>
$(document).ready(function(){
	var map = gmap_newMap('<?php 
		echo $VPC->gmap_opz['id']; 
		?>', <?php 
		echo $VPC->gmap_opz['lat']; 
		?>, <?php 
		echo $VPC->gmap_opz['lng']; 
		?>, <?php 
		echo $VPC->gmap_opz['zoom']; 
		?>);
		
function xxx() {
	
	var marker1 = gmap_newMarker(map,<?php 
		echo ($VPC->gmap_opz['lat']+(rand()%10)/170); 
		?>, <?php 
		echo ($VPC->gmap_opz['lng']+(rand()%10)/170); 
		?>);
	var infowindow1 = gmap_newInfowindow(map);
	google.maps.event.addListener(marker1, 'click', function() { infowindow1.open(map, marker1); });
	
	var marker2 = gmap_newMarker(map,<?php 
		echo ($VPC->gmap_opz['lat']+(rand()%10)/170); 
		?>, <?php 
		echo ($VPC->gmap_opz['lng']+(rand()%10)/170); 
		?>);
	var infowindow2 = gmap_newInfowindow(map);
	google.maps.event.addListener(marker2, 'click', function() { infowindow2.open(map, marker2); });
}

function op(i,iw,mk) { iw[i].open(map, mk[i]); alert(i); }

function xxx1() {
	
	var mk = new Array();
	var iw = new Array();
	var i=0;
	
	i=0;
	mk[i] = gmap_newMarker(map,<?php 
		echo ($VPC->gmap_opz['lat']+(rand()%10)/170); 
		?>, <?php 
		echo ($VPC->gmap_opz['lng']+(rand()%10)/170); 
		?>);
	iw[i] = gmap_newInfowindow(map);
	google.maps.event.addListener(mk[i], 'click', function() { op(0,iw,mk); });
	
	i++;
	mk[i] = gmap_newMarker(map,<?php 
		echo ($VPC->gmap_opz['lat']+(rand()%10)/170); 
		?>, <?php 
		echo ($VPC->gmap_opz['lng']+(rand()%10)/170); 
		?>);
	iw[i] = gmap_newInfowindow(map);
	google.maps.event.addListener(mk[i], 'click', function() { op(1,iw,mk); });
}


function op2(i,iw,mk) { iw.open(map, mk); alert(i); }
function xxx2(map, iw, lat, lng) {
	var i=iw.length;
	var mk = gmap_newMarker(map,lat,lng);
	iw[i] = gmap_newInfowindow(map);
	google.maps.event.addListener(mk, 'click', function() { iw[i].open(map,mk); alert(i);  });
	return iw[i];
}

var iw = new Array();
var j=0;
xxx2(map,iw,<?php 
		echo ($VPC->gmap_opz['lat']+(rand()%10)/170); 
		?>, <?php 
		echo ($VPC->gmap_opz['lng']+(rand()%10)/170); 
		?>);
j++;
xxx2(map,iw,<?php 
		echo ($VPC->gmap_opz['lat']+(rand()%10)/170); 
		?>, <?php 
		echo ($VPC->gmap_opz['lng']+(rand()%10)/170); 
		?>);

});
</script>

</head>

<body>
<h4>Test Google Map</h4>
<div id="<?php echo $VPC->gmap_opz['id']; ?>" style="width:900px; height:500px;"></div>
</body>
</html>
