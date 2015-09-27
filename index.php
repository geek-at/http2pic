<?php
$a = apache_request_headers();
define('DOMAIN', $a['Host']);
define('CONN', (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "")?'http':'https');


?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Christian Haschek">

    <title>http2pic</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/clean-blog.min.css" rel="stylesheet">
    <link href="css/http2pic.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='//fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header id="intro-header" class="intro-header" style="background-image: url('img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1 style="text-shadow: 0px 0px 12px #000000;">http2pic</h1>
                        <h2 id="loading"><img src="img/loading.gif" /><br/>Loading..</h2>
                        <hr class="small">
                        <span style="text-shadow: 0px 0px 12px #000000;" class="subheading">Give it a try! <input id="showcase_url" type="url" placeholder="eg. http://xkcd.com" />
                        <input id="showcase_button" type="button" onclick="web2pic();" value="GO" /></div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <img id="preloader" src="" />

    <!-- Main Content -->
    <div class="container">
        <h2>How the API works</h2>
        <div class="well"><h2 ><?php echo CONN; ?>://<?php echo DOMAIN; ?>/api.php?<span style="color:#C73C49">[OPTIONS]</span>&amp;url=<span style="color:#1e90ff">[WEBSITE_URL]</span></h2></div><hr/><br/>
        <div >
            <div>
                <section>
                    <h2>Options</h2>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Option</th>
                                <th>Values</th>
                                <th>Description</th>
                                <th>Example</th>
                            </tr>
                            <tr>
                                <td>url</td>
                                <td>http://..</td>
                                <td>The URL of the webpage you'd like to take a screenshot of. Make sure to encode the URL!</td>
                                <td>url=http://xkcd.com</td>
                            </tr>
                            <tr>
                                <td>width</td>
                                <td>WIDTH</td>
                                <td>Resizes the screenshot to a specified maximum width. Default value is the original size</td>
                                <td>width=400</td>
                            </tr>
                            <tr>
                                <td>viewport</td>
                                <td>WIDTHxHEIGHT</td>
                                <td>Sets the size of the virtual screen rendering the page. Default: smart width, full height</td>
                                <td>viewport=1980x1080</td>
                            </tr>
                            <tr>
                                <td>js</td>
                                <td>yes|no</td>
                                <td>Allows you to enable/disable JavaScript in the rendered Website. Default value: yes</td>
                                <td>js=yes</td>
                            </tr>
                            <tr>
                                <td>type</td>
                                <td>jpg|png</td>
                                <td>Sets the output file format of the rendered screenshot. Default value: jpg</td>
                                <td>type=png</td>
                            </tr>
                            <tr>
                                <td>onfail</td>
                                <td>[url of .jpg]</td>
                                <td>If the page can't be reached, this image will be displayed instead</td>
                                <td><?php echo CONN; ?>://<?php echo DOMAIN; ?>/img/failed.jpg</td>
                            </tr>
                            
                            <tr>
                                <td>cache</td>
                                <td>[any alphanumeric string]</td>
                                <td>If provided, caches the rendered image (based on the URL) so it loads faster on next request. The same cache id with the same url will return the cached image. Change cache id to re-render</td>
                                <td>f01d0</td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>
            <div class="6u">
                <section>
                    <h1>Examples</h1>
                    
                    <h3>Simple link via img tag</h3>
                    <p class="margin-bottom"></p>
                    <p>
                        <pre><code class="php">
&lt;?php
    $url = 'http://www.xkcd.com';
    $query = 'type=jpg&viewport=1200x330&url='.rawurlencode($url);
    $img="<?php echo CONN; ?>://<?php echo DOMAIN; ?>/api.php?$query";

    echo "&lt;img src='$img' /&gt;";
?&gt;
                        </code></pre>
                    </p>
                    
                    
                    <h3>Proxy script to download the image via curl</h3>
                    <p class="margin-bottom"></p>
                    <p>
                        <pre><code class="php">
&lt;?php
    $targeturl = 'http://www.xkcd.com';
    $url = '<?php echo CONN; ?>://<?php echo DOMAIN; ?>/api.php?url='.rawurlencode($targeturl);
	    
    $ch = curl_init($url);
    $fp = fopen('xkcd.jpg', 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
?&gt;
                        </code></pre>
                    </p>
                </section>
            </div>
        </div>
    </div>

    <hr>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <p class="copyright text-muted">Copyright &copy; Haschek Solutions <br/><a href="https://www.haschek-solutions.com"><img src="img/hs_logo.png" /></a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/clean-blog.min.js"></script>
    <script src="js/http2pic.js"></script>

</body>

</html>
