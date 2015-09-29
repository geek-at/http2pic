<?php
include_once('http2pic.class.php');

$url = $_GET['url'];
$type = $_GET['type'];
$timeout = $_GET['timeout'];
$viewport = $_GET['viewport'];
$js = $_GET['js'];
$resizewidth = $_GET['width'];
$cache = $_GET['cache'];
$onfail = rawurldecode($_GET['onfail']);

$params = array('url'=>$url,
				'type'=>$type,
				'timeout'=>$timeout,
				'viewport'=>$viewport,
				'js'=>$js,
				'resizewidth'=>$resizewidth,
				'cache'=>$cache,
				'onfail'=>$onfail);

$http2pic = new http2pic($params);
//echo nl2br(print_r($http2pic->debug(),true));