<?php
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).DS.'..');

require_once(ROOT.DS.'src'.DS.'config.inc.php');
require_once(ROOT.DS.'src'.DS.'helpers.php');
require_once(ROOT.DS.'src'.DS.'http2pic.class.php');
require_once(ROOT.DS.'src'.DS.'vendor'.DS.'autoload.php');

$url = array_filter(explode('/',ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/')));

//check for integrated server
if(php_sapi_name()=='cli-server' && file_exists(ROOT.DS.'web'.DS.implode('/',$url)) && !is_dir(ROOT.DS.'web'.DS.implode('/',$url)))
    return false;


switch($url[0])
{
    case 'test':
        $target = $_GET['url'];
        $type = $_GET['type'];
        $timeout = $_GET['timeout'];
        $viewport = $_GET['viewport'];
        $js = $_GET['js']=='false'?false:true;
        $resizewidth = $_GET['width'];

        $serverUrl = 'http://localhost:4444';
        $options = new \Facebook\WebDriver\Chrome\ChromeOptions();
        $options->addArguments(['--headless', '--disable-gpu', '--no-sandbox', '--disable-dev-shm-usage']);
        
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(\Facebook\WebDriver\Chrome\ChromeOptions::CAPABILITY, $options);

        //disable javascript if $js is false
        if(!$js)
            $capabilities->setCapability('javascriptEnabled', false);
        
        

        $driver = RemoteWebDriver::create($serverUrl, $capabilities);

        $driver->get($target);

        //hide scroll bars
        $driver->executeScript('document.body.style.overflow = "hidden";');

        //set screenshot size to 1920x1080
        //$driver->manage()->window()->setSize(new \Facebook\WebDriver\WebDriverDimension(1024, 768));
        //if $viewport is set, set window size
        if($viewport)
        {
            $viewport = explode('x',$viewport);
            $driver->manage()->window()->setSize(new \Facebook\WebDriver\WebDriverDimension($viewport[0], $viewport[1]));
        }
        else
        {
            $driver->manage()->window()->setSize(new \Facebook\WebDriver\WebDriverDimension(1024, 768));
        }

        // take screenshot and save to file
        //header for png
        header('Content-Type: image/png');
        echo $driver->takeScreenshot();

    break;
    case 'api':
        $url = $_GET['url'];
        $type = $_GET['type'];
        $timeout = $_GET['timeout'];
        $viewport = $_GET['viewport'];
        $js = $_GET['js'];
        $resizewidth = $_GET['width'];
            $cache = $_GET['cache'];
            $onfail = rawurldecode($_GET['onfail']);

            $params = array('url'=>trim($url),
                            'type'=>$type,
                            'timeout'=>$timeout,
                            'viewport'=>$viewport,
                            'js'=>$js,
                            'resizewidth'=>$resizewidth,
                            'cache'=>$cache,
                            'onfail'=>$onfail);

            $http2pic = new http2pic($params);
        
        break;
    case 'img':
        
        break;
    default:
        echo renderTemplate('index.html.php');
        break;
}