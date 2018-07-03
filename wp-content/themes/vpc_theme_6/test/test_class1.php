<?php
$pl_data = $VPC->PostList->topPosts();
$p2_data = $VPC->PostList->lastPosts();
$p3_data = $VPC->PostList->featuredPosts();
//var_dump($pl_data);


//$VPC->PostList->Print_LastPosts();
//$VPC->PostList->Print_Homeage_FeaturedPosts();

//$VPC->PostList->Print_Homeage_FeaturedPosts($p3_data);
die();
$VPC->new_VPC_Business();

//var_dump($VPC->Business->isBusinessPage(1385));

//var_dump($VPC->Business->getBusinessPosition(8079));

//var_dump($VPC->Business->getBusinessCategories(76));

//var_dump($VPC->Business->getPostBusiness(8000));

$x123 = $VPC->Business->getThematicListData(1);

var_dump($VPC->Business->Print_GmapMarkers_ThematicData($x123));

var_dump($x123);

die();

$ev_data = $VPC->EV->GetData(0,22,true
		//,array(493,6617,1672,4619, 2708,703)
);
$VPC->EV->Print_GmapMarkersData($ev_data);


die();

$pl_data = $VPC->PostList->topPosts();
$p2_data = $VPC->PostList->lastPosts();
$p3_data = $VPC->PostList->featuredPosts();
//var_dump($pl_data);

//$VPC->PostList->Print_Homeage_LastPosts($pl_data, $p2_data);

$VPC->PostList->Print_Homeage_FeaturedPosts($p3_data);
die();

$ev_data = $VPC->EV->GetData(0,22,true
		//,array(493,6617,1672,4619, 2708,703)
);


$VPC->EV->Print_Calendar($ev_data);

$VPC->EV->Print_All_Boards($ev_data);















?>