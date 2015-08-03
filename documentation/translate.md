# How to translate Xali

Xali is thinked up to be translated in several language, and we
need you to translate Xali in your language.

**Note than Xali need to be translated in right English, Xali's team
is not English.**

It is easy to translate Xali in your language. The files are
located in 'src/Xali/Bundle/[sth]Bundle/Resources/translations'
and are named messages.<language>.yml, where [language] equals
to 'en' for English for example. It can also equals to 'en_US'
to translate Xali in US english.

Just open the file or create the file 'messages.[language].yml if
it doesn't exist. As you can see in the other files, there are
strings like :

    form:
        firstname: "Firstname:"

It means in at least one file in the bundle, there is a
string named 'form.firstname' and in your browser you see
'Firstname:'.
Copy the strings you want to translate in your file, **and only
the strings you want to translate, not the others**, and translate the
strings between quotes.

Note than strings on several lines **are not between quotes** and are
the following syntaxe (don't forget the '>' alone on the first line):

    welcome:
        description: >
                     Here a text
                     on several lines


To see your translations in your browser you can change the 'locale'
and set it to your locale. Open the file
'app/config/parameters.yml' and update locale from 'en' to your
language.

Finally, commit and click on 'Pull request' on Github (if you've forked
the repository). If you've any problem, declare an *issue* on Github
and a Xali's member will help you.
