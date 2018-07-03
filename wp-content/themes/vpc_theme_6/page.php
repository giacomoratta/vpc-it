<?php

$VPC->new_VPC_Business();

 
if($VPC->Business->isBusinessPage(get_the_ID()))
	$VPC->Theme->page_class='business_page';

else
	$VPC->Theme->page_class='simple_page';

include('single_page.php');

?>