# Install Xali

Xali's installation has several steps:
- Install a Web server and associated softwares
- Install Git
- Add Git and PHP into the PATH (for Windows)
- Install Xali on the server, create database and set parameters

## Install a Web server and associated softwares

Xali is written in PHP language, using MySQL for its database,
so you need PHP, MySQL and a Web server to manage HTTP requests.

The easier way is to install a software which contains all of these.
- Windows: Install [Wampserver](http://www.wampserver.com/)
- Linux: Launch 'sudo apt-get install apache2 php5 mysql-server 
libapache2-mod-php5 php5-mysql' in a Terminal
- Mac: Install [Mamp](https://www.mamp.info/en/)

Follow instructions corresponding to you machine,
you have now a functional Web server.

## Install Git

Xali is managed using Git, on Github, so you need get Git to work on
Xali. Note than if you only want to test Xali on your computer, you can
'Download as ZIP' on Github and you don't need Git, but the method
using Git is recommanded.

- Windows: Your download begin [here](https://git-scm.com/download/win)
- Linux: Launch 'sudo apt-get install git git-core' in
a Terminal or if you don't use apt-get, follow 
[these instructions](https://git-scm.com/download/linux)
- Mac: Your download begin [here](https://git-scm.com/download/mac)

Just follow the instructions, Git will be installed on your machine.
You can now 'clone' Xali project on your server. If your OS
is not listed above, follow [this link](https://git-scm.com/downloads)

## Add Git and PHP into the PATH (for Windows)

- Open your files explorer
- Click right on 'Computer' in the panel
on the left, it will open a new window
- Click on 'Properties', it will open a window
- Click on 'Advanced system settings' on
the left, it will open a new window
- Click on 'Environment variables', it will open
a new window
- In the list 'System variables' (it should be at bottom) select
the variable named 'Path' and click on 'Update', it will open
a new window
- DO NOT DELETE ANYTHING. Add the the end of the string the
absolute path to PHP and Git.

*Example* : If php.exe is located at 'C:\wamp\bin\php5-2\php.exe',
you should add 'C:\wamp\bin\php5-2' into the PATH. Use ';'
character to separate differents paths in the list.

## Install Xali project on your server

### Clone Xali on your server

To work on Xali, you need Xali, of course.

Open a Terminal or a DOS console. Note than on Windows it's
recommanded to use Git scm, the software you've installed just before,
for syntacic colouring.

In the Terminal, type:
- `cd <pathToWebserver>` to go into the wanted folder in Web server
- `git clone https://github.com/anthonybocci/Xali`

You have now a folder named 'Xali' which contains all of Xali project.

### Set parameters

Xali use a database, so you must set your parameters.
*We suppose you are in the Xali folder.*

- Copy the file 'app/config/parameters.yml.dist'
and paste a copy in the same folder.
- Rename the copy 'parameters.yml'
- Open it, and set your MySQL parameters
- You can choose the database's name,
it's optional
- Save the file and close it

Now we suppose you have a Terminal or a
DOS console opened, and you are located
in the Xali folder.
### Install dependencies

Xali has some dependencies you have to install.

- `php ./composer.phar install`

Note than you must get the writing right. And, if
composer suggest to do an update, cancel the previous
command and do it. Then, you'll can lauch the command
above.

### Create the database

Xali use a database, you'll create an empty database
using a Terminal or a DOS console and type the
following commands.

- `php app/console doctrine:database:create`

To delete completely this database, you can use MySQL
in a Terminal (type `DROP DATABASE <databaseName>`) or
PhpMyAdmin in your browser for example

### Update database's schema

Actually, your database is empty. You'll update its schema
typing the following command :

- `php app/console doctrine:schema:update --force`

Each time you would update the database's schema we
recommand to delete completely Xali's database like
explained above (only if you have datas inside,
because of foreign keys), create it again and
update its schema using the commands above.

### Fill database with example datas

To make easier use of Xali, it's better to insert datas
like organisations, camps, survivors and users. A script
is already written to do that. Type the following command :

- `php app/console doctrine:fixtures:load`

It could take some minutes. To know more about fixtures you can read
[this document](https://github.com/anthonybocci/Xali/blob/master/documentation/load_fixtures.md)

Xali is now functional on your server !
