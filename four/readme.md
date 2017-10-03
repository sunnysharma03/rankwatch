# rankwatch17_php_userlogin Problem

The task was to build a login system and a registration page too and do proper validation.

The login is build with proper valiation and for error I have used Swal function of Sweet Alert 2 Js framework.
The registration is build with all validation in php and Js and the inputs are processed with prepared statements.
The email is being check for duplicasy with ajax and for country I have used typeahead js with Json query and according to the country 
state list is being fetched using ajax.

The password is being saved in Hashed value.

```
A fixed 70 length string with cost 11 and PASSWORD_BCRYPT is being generated and saved as password
```
so that there is a extra layer of security in terms of database and noone can easily dehash the password.
The sessions are used properly and works well.

## For demo purpose you can use these credentials
```
username: sachin@gmail.com
password: sunny
```
