<?php
$type = $_GET['type'];
$timeout = $_GET['timeout'];
$viewport = $_GET['viewport'];
$js = $_GET['js'];
$resizewidth = $_GET['width'];
$cache = preg_replace("/[^A-Za-z0-9 ]/", '', $_GET['cache']);
$onfail = rawurldecode($_GET['onfail']);
if(!$onfail)
	$onfail = 'https://http2pic.haschek.at/img/failed.jpg';
$url = rawurldecode($_GET['url']);
if(isBase64($url))
	$url = base64_decode($url);

if(!$timeout || !is_numeric($timeout) || ($timeout>30 || $timeout<1))
	$timeout = 10;

if($viewport)
{
	$a=explode('x', $viewport);
	$w = $a[0];
	$h = $a[1];
	if($w)
		$vp = "--width $w ";
	if($h)
		$vp.= "--height $h ";

}	

if($js=='no')
	$jsp = '-n ';

switch ($type) {
	case 'png':
		$ft = $type;
		header('Content-Type: image/png');
	break;

	case 'jpg':
	case 'jpeg':
	default:
		$ft = 'jpg';
		header('Content-Type: image/jpeg');
}



$hash = $cache.'-'.preg_replace("/[^A-Za-z0-9 ]/", '', $url).'.'.$ft;
if(!$cache)
	$hash = md5(time().rand(1,2000)).$hash;

if(!is_dir(__DIR__.'/cache/'))
	mkdir(__DIR__.'/cache/');
$file = __DIR__.'/cache/'.$hash;
if(!file_exists($file))
	shell_exec('timeout '.$timeout.' /usr/sbin/wkhtmltoimage '.escapeshellcmd($vp.$jsp.'-f '.$ft.' '.$url.' '.$file));

if(filesize($file)==0 && $onfail)
	@file_put_contents($file, file_get_contents($onfail));

if($resizewidth)
{
	list($width_orig, $height_orig) = getimagesize($file);
	if($width_orig!=$resizewidth) 
	{
		$ratio_orig = $width_orig/$height_orig;
		$height = $resizewidth/$ratio_orig;

		// resample
		$image_p = imagecreatetruecolor($resizewidth, $height);
		$image = imagecreatefromjpeg($file);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $resizewidth, $height, $width_orig, $height_orig);
		imagejpeg($image_p, $file, 100);
	}
}


if($ft=='jpg')
{
	$res = imagecreatefromjpeg($file);
	imagejpeg($res,NULL,100);
}
else if($ft=='png')
{
	$res = imagecreatefrompng($file);
	imagepng($res,NULL,9);
}


imagedestroy($res);

if(!$cache)
	unlink($file);

function isBase64($data)
{
	if ( base64_encode(base64_decode($data, true)) === $data)
		return true;
	else 
		return false;
}