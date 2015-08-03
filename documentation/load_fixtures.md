# Load fixtures

To make tests easier, we need 'real' datas. So we
written a script which insert lot of informations
in database.

These scripts are in each bundle except DefaultBundle,
and are located in
'src/Xali/Bundle/<sth>Bundle/DataFixtures/ORM/'.

These scripts are executed when you launch the following
command :
- `php app/console doctrine:fixtures:load`

It can take some minutes (or more according to your scripts)
