<?php 

function renderTemplate($templatename,$variables=[],$basepath=ROOT.'/src')
{
    ob_start();
    if(is_array($variables))
        extract($variables);
    if(file_exists($basepath.DS.'templates'.DS.$templatename.'.php'))
        include($basepath.DS.'templates'.DS.$templatename.'.php');
    else if(file_exists($basepath.DS.'templates'.DS.$templatename))
        include($basepath.DS.'templates'.DS.$templatename);
    $rendered = ob_get_contents();
    ob_end_clean();

    return $rendered;
}