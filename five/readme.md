# rankwatch17_php_bitbucket Problem

The task is to accept the username and password of a bitbucket account and fetch all his info.

The bitbuckey people have already created
Bitbucket-api is PHP library which facilitates communication with Bitbucket API for any PHP application. 
It supports versions 1 and 2 of the Bitbucket API.

## Requirements

1.PHP >= 5.3 with cURL extension.
2.Buzz library,
3.PHPUnit to run tests. ( optional )

You can install it using Composer as
```
 composer require "gentle/bitbucket-api"
```
The compser will install all the the dependencies

Using Examples

## Basic authentication
To use basic authentication, you need to attach BasicAuthListener to http client with your username and password.

```
 $user = new Bitbucket\API\User();
  $user->getClient()->addListener(
      new Bitbucket\API\Http\Listener\BasicAuthListener($bb_user, $bb_pass)
  );

  // now you can access protected endpoints as $bb_user
  $response = $user->get();
 ```
 
 Just repace the $bb_user, $bb_pass with the user credentials
 
 You will get the result in $response and then you can print it.
 ```
 You will get the main file inside the vendor folder as test.php
 ```
