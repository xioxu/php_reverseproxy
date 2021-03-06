##ReverseProxy PHP Script

A simple PHP script that can be used as a ReverseProxy. Although you can use Nginx, mod_proxy or a dedicated software like Squid for this purpose, there are instances that you favor the ability to just do it in a PHP script that can be dropped in a single folder. There could be various reasons:


Requirements
============

* PHP >= 5.2
* Apache mod_rewrite enabled
* cURL extension enabled

Linux
============
You can use .htaccess to control the route

Windows
============
You sholuld specify route file with router.php

Installation
============
edit config.php

    define('MASKED_DOMAIN', 'http://www.google.com');
    define('PROXY_SUBFOLDER', '');
    define('FOLLOW_LOCATION', FALSE);

* MASKED_DOMAIN is the site where this script will be getting its contents from, should have no traliling slashes.
* PROXY_SUBFOLDER this is in case you had placed your script in a subfolder, like for example http://www.yourdomain.com/reverseproxy
Leave this to blank if you had placed this in the root of the domain
* FOLLOW_LOCATION - indicates whether the script will follow the redirect returned by the source site

edit .htaccess

    RewriteEngine on 
    RewriteCond $1 !^(proxy\.php) 
    RewriteRule ^(.*)$ /proxy.php/$1 [L]

This directives allows the reverseproxy to catch all the URI intented for the original domain, like for example

    http://www.originaldomain.com/blog/102014/hello-world

With this setting, you can use

    http://www.yourdomain.com/blog/102014/hello-world

and the uri will be passed to the index.php and can then be passed to the original domain to rerieve the contents.
Remember that if you are serving the contents from the root domain you only need to use this

    RewriteEngine on 
    RewriteCond $1 !^(proxy\.php) 
    RewriteRule ^(.*)$ /proxy.php/$1 [L]






