# How to test Xali

If you want to contribute to Xali, you can translate strings,
fix bugs, or make tests.
To test Xali, you have to [install](https://github.com/anthonybocci/Xali/blob/master/documentation/install.md)
it on your machine, then you'll can test.

## Log in as user

To test Xali you'll have to be logged in, but you don't know any
username or password. The easier way to find username and password, is
open PhpMyAdmin in your browser. In the 'user' table, you'll see all
users's informations. Pick a username, copy it in 'username' field on
login page, and the associated password is the user's lastname
(in lowercase).

Example:
    
    username:       |     email:            |  firstname:  |  lastname:
    chuck-norris0   |     chuck@norris0.fr  |  Chuck       |  Norris

The username, of course, is 'chuck-norris0. The assiociated password is
'chuck' (in lowercase)
In PhpMyAdmin you'll can see user's roles, it's useful to test rights.
If a user hasn't ROLE_SUPER_ADMIN, can he access to "create an
organisation" ?

## Kinds of tests

A platform, like Xali, need several kind of tests. There is a not
exhaustive list of test needed by Xali.

### Design test

Try to do an action, like add on organisation for example. Is design
ergonomic ? Have you found easily where is the link ?
For each detail, you can create a new issue on Github and describe the
problem.

### Functionality tests

Try every actions, in different cases, is there a bug ? An error ?
Try to do something logged as sample user, or root, or organisation,
is everything ok ?

### Sturdiness tests

Try to fill every forms, and let some fields empty, is there any error
or warning ?

### Security tests

Try to access to Xali without be logged in, or with an insufficient
role, can you access to the wanted resource ?
Try to submit a form with a wrong csrf token, does it works ?


**For each problem, each bug or strange thing,
[create a new issue]()
on Github.** If you don't know how to declare an issue, see
[this document](https://github.com/anthonybocci/Xali/blob/master/documentation/how_to_create_issue.md)
