var page = require('webpage').create(),
    system = require('system'),
    address, output, size;

var input = system.args[1].split(",");

address = input[0];
output = input[1];
var vp_w = input[2];
var vp_h = input[3];
var js = input[4];

var pageWidth = (vp_w!="")?parseInt(vp_w, 10):1024;
var pageHeight = (vp_h!="")?parseInt(vp_h, 10):'auto';
page.viewportSize = { width: pageWidth, height: pageHeight };
page.settings['javascriptEnabled'] = (js=="no")?false:true;

    page.open(address, function (status) {
        if (status !== 'success') {
            console.log('Unable to load the address!');
            phantom.exit(1);
        } else {
            window.setTimeout(function () {
                page.render(output);
                phantom.exit();
            }, 200);
        }
    });
