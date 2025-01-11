<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).DS.'..');

require_once(ROOT.DS.'src'.DS.'config.inc.php');
require_once(ROOT.DS.'src'.DS.'helpers.php');
require_once(ROOT.DS.'src'.DS.'http2pic.class.php');

$url = array_filter(explode('/',ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/')));

//check for integrated server
if(php_sapi_name()=='cli-server' && file_exists(ROOT.DS.'web'.DS.implode('/',$url)) && !is_dir(ROOT.DS.'web'.DS.implode('/',$url)))
    return false;


switch($url[0])
{
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