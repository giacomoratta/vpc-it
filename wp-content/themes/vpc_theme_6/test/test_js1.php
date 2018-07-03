<?php 
$VPC->new_VPC_Business();
$x123 = $VPC->Business->getThematicListData(1);

//var_dump($x123);

?>
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

<?php // $VPC->Business->Print_GmapMarkers_IconShadow($x123); ?>
	
	var markers = [
<?php $VPC->Business->Print_GmapMarkers_ThematicData($x123); ?>
	];
	
	$(<?php echo $VPC->gmap_opz['id']; ?>).vpc_gmap({
		lat:<?php echo $VPC->gmap_opz['lat']; ?>,
		lng:<?php echo $VPC->gmap_opz['lng']; ?>,
		zoom:<?php echo $VPC->gmap_opz['zoom']; ?>,
		markers: markers,
		info_maxWidth: 300,
		//id_newMarker:'coord',
	});

});
</script>

</head>

<body>
<h4>Test Google Map</h4>
<div id="output"></div>
<input type="hidden" id="coord" value="" />
<div id="<?php echo $VPC->gmap_opz['id']; ?>" style="width:900px; height:500px;"></div>
</body>
</html>
