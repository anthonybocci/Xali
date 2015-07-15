Xali
===================

Xali is a Web platform, a tool witch allow families to be
together again. In countries where there are wars, some families are separated.
If these people come in humanitarian organisation's camp, they'll can use
Xali to add them in database, and the rest of family, witch is
is an other country, will can find them again with help of humanitarian organisation
witch will user Xali to find them.


1) Installing Xali
---------------------------------


 * Install a webserver, Wampserver if you're using Windows for example
 * Clone this repository onto your web server
 * Copy app/config/parameters.yml.dist in the same folder, and rename the copy as app/config/parameters.yml
 * Set your paramaters in app/config/parameters.yml, the username and password for database, and the database's name (xali for example)

Execute following commands :

    php ./composer.phar update
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    php app/console doctrine:fixtures:load

Note : If you're using Windows, add "PHP" and "Git" to the PATH


2) Check your configuration
---------------------------

After installing, launch you Web browser and type the following adress :
http://localhost/<pathToHumanitarianHelper>/web/config.php

This page will tell you if your configuration is fine or not.


3) Using Xali
----------------------------

To know exactly how to use it, you have to read features.html, and there
is a Use Case ("Use Case.png")
