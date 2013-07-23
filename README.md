Firenote
========

PHP Admin skeleton over Silex

Status : Development in progress - not stable


1 - Setup

A - Install with Composer (niktux/firenote)
B - Initialize filetree :
 php vendor/bin/fire app:init .
 
C - Create database (manually at this time)
D - Initialize database :
 php vendor/bin/fire db:create

E - Vhost : 

<VirtualHost *:80>

    ServerName www.example.org
    DocumentRoot /path/to/firenote/web

    RewriteEngine on

    RewriteRule ^/assets/(.*) - [L]
    RewriteRule ^/favicon\.ico - [L]
    RewriteRule (.*) /index.php [L]

</VirtualHost>

F - Enjoy !
