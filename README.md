# GoogleAuthenticator
### Important Note
This is still under initial development, I've yet to work out the best way of abstracting the concept of the image generators.

## Introduction
2 factor authentication is pretty awesome. Far too many people use the same password for multiple things, and sometimes it's nice to actually have a secure application.

Using the Google Authenticator allows people to have another layer of security that will only allow them to access your web application/service if they have both the password and the correctly setup Google Authenticator app on their phone.

## Implementation
As far as I could tell, there were (at the time of writing) 2 other PHP libraries for interacting with the Google Authenticator. Both of which work but neither of which seem to be updated much nor incorporate modern best practises.

This library has the advantage of being slightly nicer (I hope) to integrate into existing libraries, and contains inbuilt support for using a PSR-6 cache interface to reduce the possibility of a replay attack.

## Usage
You can initially create the a secret code for use in your application using:

    $issuer = "MyAwesomeCorp";
    $accountName = "MrsSmith";
    $secretFactory = new SecretFactory();
    $secret = $secretFactory->create($issuer, $accountName);
    
This gives you a secret. You should both feed this into a QrImageGenerator so your user can scan the QR code into their phone and secondarily, attach the secret to their user account so you can query it.
    
You can verify that the user has been successful by using this:

    $googleAuth = new GoogleAuthenticator();
    $googleAuth->authenticate($secret, $code);
    
Authenticate will either boolean true/false.

If you want to use a PSR-6 cache interface to attempt to prevent replay attacks, you can do so like so:

    $googleAuth = new GoogleAuthenticator();
    $googleAuth->setCache($cacheItemPoolInterface);
    $googleAuth->authenticate($secret, $code);
    
If the code has been used for that secret in the last 30 seconds, it will return false.

## References
Other PHP Google Authenticator implementations:
-  https://github.com/chregu/GoogleAuthenticator.php
-  https://github.com/PHPGangsta/GoogleAuthenticator

Specification for Google Authenticator:
- https://github.com/google/google-authenticator/wiki/Key-Uri-Format
