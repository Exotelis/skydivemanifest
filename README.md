# Skydivemanifest
A tool to manage your dropzones manifest.

## Table of contents
- [Getting started](#getting-started)
  + [Administration bundle](#administration-bundle)
  + [Api bundle](#api-bundle)
- [Roadmap](#roadmap)
- [Server configuration](#server-configuration)
  + [Rewrite urls](#rewrite-urls)
    * [Apache](#apache)
  + [gzip](#gzip)
    * [Apache](#apache-1)
- [Development environment](#development-environment)
- [Contribution guidelines](#contribution-guidelines)

## Getting started
This project consists of different bundles. The `api` uses Lumen, which is a lightweight PHP framework.
The `administration` bundle is the frontend for the api, where the admin can manage the all data on a graphical user
interface. It's based on the JavaScript framework Vue.js. Each bundle provides its own documentation, because of the
different technologies.

### Administration bundle
Please see the [REAMDE.md](administration/README.md) of the administration bundle for details.

### Api bundle
Please see the [REAMDE.md](api/README.md) of the api bundle for details.

## Roadmap
Will be updated after the release of version 1

## Server configuration
If you want to use the system in production, you have to keep some things in mind to provide a solid system.

### Rewrite urls
Since the administration bundle is running in history mode, which makes it possible to use the backward and forward
buttons of your browser, you have to configure your web server properly. To learn more about the Vue Router and the
history mode see [404 error when accessing a route directly](administration/README.md#404-error-when-accessing-a-route-directly).

#### Apache
Before you add any rewrite rules to your .htaccess or web server configuration files, you should check if all required
modules are loaded. In order to use the rewrite engine of apache, the `rewrite_module` must be available. To check if it
is loaded run `apache2ctl -M` or `httpd -M` (depending on the operating system you are using). If it's enabled, just go
ahead. If not, run `a2enmod rewrite` or add the following line to the correct config file of apache
(with apache 2.4 e.g. /etc/apache2/loadmodule.conf):
```
LoadModule rewrite_module <path_to_the_lib_directory>/mod_rewrite.so
```

Once this is done, you have to check if the document root directory is configured properly. For example it should look
similar to:
```
DocumentRoot "/var/html"
<Directory "/var/html">
        Options FollowSymLinks
        AllowOverride All
        <IfModule !mod_access_compat.c>
                Require all granted
        </IfModule>
        <IfModule mod_access_compat.c>
                Order allow,deny
                Allow from all
        </IfModule>
</Directory>
```
Important are `Options FollowSymLinks` and `AllowOverride All`.

Now you can restart your apache service e.g. `systemctl restart apache2.service`

One way to configure the rewrite rules is by using a .htaccess file. Lets imagine you store your administration source
files under the url `https://oursite.com/administration`. Just add the .htaccess file inside this directory, right next
to the `index.html`, and add the following content:
```
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteBase /administration
    RewriteRule ^index\.html$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . index.html [L]
</IfModule>
```
Note that the line `RewriteBase /administration` must be the path to the `index.html` of the administration bundle
(without the filename). If everything is working correctly, it should be possible to browse
`https://oursite.com/administration/login` directly without any error.

### gzip
gzip is used for file compression and decompression. This will improve the loading time and therefore the overall
performance.

#### Apache
To enable the file compression the module `deflate` must be enabled and since Apache version 2.4 also the module
`filter`. Run `apache2ctl -M` or `httpd -M` (depending on the operating system you are using) to check if the modules
are loaded. If yes, just go ahead. If no, run `a2enmod deflate` and `a2enmod filter` or add the following lines to the
correct config file of apache (with apache 2.4 e.g. /etc/apache2/loadmodule.conf):
```
LoadModule deflate_module <path_to_the_lib_directory>/mod_deflate.so
LoadModule filter_module <path_to_the_lib_directory>/mod_filter.so
```

Now you can restart your apache service e.g. `systemctl restart apache2.service`

One way to enable gzip is by using a .htaccess file. Just add the following lines:
```
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
    AddOutputFilterByType DEFLATE application/x-font
    AddOutputFilterByType DEFLATE application/x-font-opentype
    AddOutputFilterByType DEFLATE application/x-font-otf
    AddOutputFilterByType DEFLATE application/x-font-truetype
    AddOutputFilterByType DEFLATE application/x-font-ttf
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE font/opentype
    AddOutputFilterByType DEFLATE font/otf
    AddOutputFilterByType DEFLATE font/ttf
    AddOutputFilterByType DEFLATE image/svg+xml
    AddOutputFilterByType DEFLATE image/x-icon
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/xml
</IfModule>
```

## Development environment
Under development :)
The plan is to provide a vagrant box or docker container that can be used as demo system and/or as development
environment.

## Contribution guidelines
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.