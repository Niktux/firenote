Firenote
========

PHP Admin skeleton over Silex

Status : Development in progress - not stable


1 - Setup
---------

A - Install with Composer (niktux/firenote)

B - Initialize filetree :
<code>
 php vendor/bin/fire app:init .
</code> 
 
C - Create database (manually at this time)

D - Initialize database :
<code>
 php vendor/bin/fire db:create
</code>

E - Vhost : 

```
<VirtualHost *:80>

    ServerName www.example.org
    DocumentRoot /path/to/firenote/web

    RewriteEngine on

    RewriteRule ^/assets/(.*) - [L]
    RewriteRule ^/favicon\.ico - [L]
    RewriteRule (.*) /index.php [L]

</VirtualHost>
```

F - Enjoy !


2 - Developers documentation
----------------------------

TODO
