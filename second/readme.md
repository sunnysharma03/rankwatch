 # rankwatch17_php_emailing Problem
 
 The problem was simple all you need to do is send an email by taking parameters like name, email, message subject and the message.
 I have used PhpMailer since it can work with normal Gmail credentials and more to that it can work on a localhost too. The mails be be guaranted 
 to reach into the inbox since the oauth is being enalbled.
 
The inputs are being processed into ajax with proper validation and then being mail to the mail.
The user need to put his gmail username and password in

```
$mail->Username = 'Enter your Gmail username';        // SMTP username
$mail->Password = 'Enter you gmail Password';         // SMTP password
```
 
