<?php
/**
 * http2pic by Christian Haschek (https://haschek.solutions)
 *
 * For more info and up2date version of this file visit https://github.com/chrisiaut/http2pic
 * -------------
 *
 * @category   Website rendering API
 * @author     Christian Haschek <christian@haschek.at>
 * @copyright  2015 by HASCHEK SOLUTIONS
 * @link       https://http2pic.haschek.at
 */


//
// Stuff you can edit
//

// if true, will save all cmd queries
define(DEBUG,false);

define(MAXTIMEOUT,30);
define(ONFAILIMAGE,'https://http2pic.haschek.at/img/failed.jpg');

//rendering engine: wkhtmltoimage or phantomjs
define(RENDERINGENGINE,'wkhtmltoimage');

//location of wkhtmltoimage
define(WKHTMLTOIMAGEPATH,'/usr/sbin/wkhtmltoimage');

//location of phantomJS
define(PHANTOMJSPATH,'/usr/bin/phantomjs');

//where shoud we store cached images
define(CACHEDIR,__DIR__.'/cache/');




//
// Only edit from here if you know what you are doing
//
class http2pic
{
	private $params = array();
	function __construct($params)
	{
		//try to create the cache folder if not exists
		if (!is_dir(CACHEDIR)) {
			mkdir(CACHEDIR);
		}
		
		$this->params = $params;
		return $this->paramsPrepare();
	}
	
	/**
	* Prepare and validate params
	**/
	function paramsPrepare()
	{
		//validate file type of rendered image
		switch($this->params['type'])
		{
			case 'png': $this->params['type'] = 'png'; break;
			default: $this->params['type'] = 'jpg';
		}
		
		//validate timeout
		if (!$this->params['timeout'] || !is_numeric($this->params['timeout']) || ($this->params['timeout'] > MAXTIMEOUT || $this->params['timeout'] < 1))
			$this->params['timeout'] = 10;
			
		//validate viewport
		if ($this->params['viewport'])
		{
			$a = explode('x', $this->params['viewport']);
			$w = $a[0];
			$h = $a[1];
			if (is_numeric($w))
				$this->params['vp_w'] = $w;
			if (is_numeric($h))
				$this->params['vp_h'] = $h;
		}
		
		//validate resize width
		if($this->params['resizewidth'])
		{
			if(!is_numeric($this->params['resizewidth']) || $this->params['resizewidth']<1 || $this->params['resizewidth']>8000)
				unset($this->params['resizewidth']);
		}
		
		if(!$this->params['onfail'])
			$this->params['onfail'] = ONFAILIMAGE;
		else
			$this->params['onfail'] = rawurldecode($this->params['onfail']);
			
	
		//validate URL and check if exists
		if ($this->isBase64($this->params['url']))
			$this->params['url'] = base64_decode($url);
		else
			$this->params['url'] = rawurldecode($_GET['url']);
		
			//if the url is not valid or not responding, show onfail image and leave
		if(!$this->isURLValid($this->params['url']) || !$this->isURLReachable($this->params['url']))
		{
			header('Content-Type: image/jpeg');
			$result = imagecreatefromjpeg($this->params['onfail']);
			imagejpeg($result, NULL, 100);
			return false;
		}
		
		
		
		//prepare file name
		$this->params['cache'] = $this->trimToAlphaNumeric($this->params['cache']);
		$hash = $this->params['cache'].'-'.preg_replace("/[^A-Za-z0-9 ]/", '', $this->params['url']).'.'.$this->params['type'];
		if (!$this->params['cache'])
			$hash = md5(time().rand(1,2000)).$hash;
		$this->params['file'] = CACHEDIR.$hash;
		
		$this->render();
		
		return true;
	}
	
	function render()
	{
		//if phantomjs is selected and installed
		if(RENDERINGENGINE=='phantomjs' && file_exists(PHANTOMJSPATH))
			return $this->renderPagePHANTOMJS();
		
		//no? well ok how about WKHTMLToImage?
		else if(RENDERINGENGINE=='wkhtmltoimage' && file_exists(WKHTMLTOIMAGEPATH))
			return $this->renderPageWKHTMLTOIMAGE();
			
		//you're fucked
		else
			throw new Exception('No valid rendering engine found');
	}
	
	
	/**
	* Render using PhantomJS
	**/
	function renderPagePHANTOMJS()
	{
		$cmd = 'timeout '.$this->params['timeout'].' '.PHANTOMJSPATH;
		$cmd.= ' --ignore-ssl-errors=yes '.__DIR__.'/phantom.js ';
		
		$cmd.= ($this->params['url']);
		$cmd.= ','.($this->params['file']);
		$cmd.= ','.$this->params['vp_w'];
		$cmd.= ','.$this->params['vp_h'];
		$cmd.= ','.$this->params['js'];
		
		$cmd = escapeshellcmd($cmd);
		shell_exec($cmd);
		$this->params['cmd'] = $cmd;
		
		$this->postRender();
		if(DEBUG)
		{
			$fp = fopen('debug.log', 'a');
			fwrite($fp, $cmd."\n");
			fclose($fp);
		}
		return $cmd;
	}
	
	/**
	* Render using WKHTMLToImage
	**/
	function renderPageWKHTMLTOIMAGE()
	{
		//escapeshellarg
		
		//timeout
		$cmd = 'timeout '.$this->params['timeout'].' '.WKHTMLTOIMAGEPATH;
		
		//viewport vp_w und vp_h
		if($this->params['vp_w'])
			$cmd.=' --width '.$this->params['vp_w'];
		if($this->params['vp_h'])
			$cmd.=' --height '.$this->params['vp_h'];
			
		//js or no js
		if($this->params['js']=='no')
			$cmd.=' -n';
			
		//png or jpg (default)
		if($this->params['type']=='png')
			$cmd.=' -f png';
		
		//add url to cmd
		$cmd.=' '.escapeshellarg($this->params['url']);
		
		//add storage path to cmd
		$cmd.=' '.escapeshellarg($this->params['file']);
			
		$cmd = escapeshellcmd($cmd);
		shell_exec($cmd);
		$this->params['cmd'] = $cmd;
		
		$this->postRender();

		if(DEBUG)
		{
			$fp = fopen('debug.log', 'a');
			fwrite($fp, $cmd."\n");
			fclose($fp);
		}
		return $cmd;
	}
	
	
	/**
	* Called after a render took place.
	* This method will print the image to the user, then
	* resizes or deletes it
	*/
	function postRender()
	{
		// resize if necessary
		if($this->params['resizewidth'])
			$this->resizeImage($this->params['file']);
		
		
		//print image to user
		if ($this->params['type'] === 'png') {
			
			header('Content-Type: image/png');
			$result = imagecreatefrompng($this->params['file']);
			imagepng($result, NULL, 9);
		}
		else {
			header('Content-Type: image/jpeg');
			$result = imagecreatefromjpeg($this->params['file']);
			imagejpeg($result, NULL, 100);
		}
		
		
		//if no cache  value specified: delete the image
		if(!$this->params['cache']) unlink($this->params['file']);
	}
	
	function resizeImage($file)
	{
		list($width_orig, $height_orig) = getimagesize($file);

		if ($width_orig != $this->params['resizewidth'])
		{
			$ratio_orig = $width_orig/$height_orig;
			$height = $this->params['resizewidth']/$ratio_orig;
	
			// resample
			$image_p = imagecreatetruecolor($this->params['resizewidth'], $height);
			if ($this->params['type'] === 'png')
				$image = imagecreatefrompng($file);
			else
				$image = imagecreatefromjpeg($file);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $this->params['resizewidth'], $height, $width_orig, $height_orig);
			
			if ($this->params['type'] === 'png')
				imagepng($image_p, $file, 9);
			else
				imagejpeg($image_p, $file, 100);
		}
	}
	
	function isURLValid($url)
	{
		return filter_var($url, FILTER_VALIDATE_URL);
	}
	
	/**
	* https://stackoverflow.com/questions/7684771/how-check-if-file-exists-from-the-url
	*/
	function isURLReachable($url)
	{
		$ch = curl_init($url);    
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
		if($code < 400) //status code updated so redirects will also work
			$status = true;
		else
			$status = false;
			
		curl_close($ch);
		return $status;
	}
	
	function trimToAlphaNumeric($string)
	{
		return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
	}
	
	function isBase64($data)
	{
		if (base64_encode(base64_decode($data, true)) === $data)
			return true;
		return false;
	}
}
