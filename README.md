# http2pic

## Introduction
http2pic is an Open Source website renderer. It uses the [wkhtmltox](https://github.com/wkhtmltopdf/wkhtmltopdf) to render websites with various options.

Live demo on https://http2pic.haschek.at/

## Dependencies
- [wkhtmltox](http://wkhtmltopdf.org/downloads.html)
- "timeout" command

## Install

- Install [wkhtmltox](http://wkhtmltopdf.org/downloads.html) on your server
- Make /usr/sbin/wkhtmltoimage executable for the user that runs the webserver. For Apache it's the ```www-data``` user. Or use sudo
- Make sure the server has the "timeout" command. For debian this is available via ```apt-get install coreutils```
- Download this repo and extract it somewhere on your webserver


This is a [HASCHEK SOLUTIONS](https://haschek.solutions) project

[![HS logo](https://http2pic.haschek.at/img/hs_logo.png)](https://haschek.solutions)