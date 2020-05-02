# GoogleAuthenticator

## Author's note
Although this library is not deprecated by any means and should continue to work well, since the release of this library other projects have implemented this in a non-terrible style and have gained reasonable traction.

Before you implement this, consider whether [otphp](https://github.com/Spomky-Labs/otphp) may suit your use case.

## Introduction
2 factor authentication is pretty awesome. Far too many people use the same password for multiple things, and sometimes it's nice to actually have a secure application.

Using the Google Authenticator allows people to have another layer of security that will only allow them to access your web application/service if they have both the password and the correctly setup Google Authenticator app on their phone.

## Implementation
As far as I could tell, there were (at the time of writing) 2 other PHP libraries for interacting with the Google Authenticator. Both of which work but neither of which seem to be updated much nor incorporate modern best practises.

This library has the advantage of being slightly nicer (I hope) to integrate into existing libraries, and contains inbuilt support for using a PSR-6 cache interface to reduce the possibility of a replay attack.

## Usage
You can initially create the a secret code for use in your application using:

```php
$issuer = "MyAwesomeCorp";
$accountName = "MrsSmith";
$secretFactory = new SecretFactory();
$secret = $secretFactory->create($issuer, $accountName);
```
    
This gives you a secret. You should:
1. feed this object into a QrImageGenerator so your user can scan the QR code into their phone
2. attach the secret to their user account so you can query it

There are 2 ImageGenerator implementations included with this library:
1. EndroidQrImageGenerator which requires you composer require `endroid/qr-code:~2.2|~3` which generates it without any external service dependencies.
2. GoogleImageGenerator which uses the Google QR code API to generate the image.

I'd recommend using Endroid as it seems that Google has now [deprecated their QR code API](https://developers.google.com/chart/infographics/docs/qr_codes)

If neither of these fit the bill for some reason, it's easy to create another implementation, as all it needs to do is generate a QR code for the data in `$secret->getUri()`
    
You can verify that the user has been successful by using this:

```php
$googleAuth = new GoogleAuthenticator();
$googleAuth->authenticate($secret, $code);
```
    
Authenticate will either boolean true/false.

If you want to use a PSR-6 cache interface to attempt to prevent replay attacks, you can do so like so:

```php
$googleAuth = new GoogleAuthenticator();
$googleAuth->setCache($cacheItemPoolInterface);
$googleAuth->authenticate($secret, $code);
```
    
If the code has been used for that secret in the last 30 seconds, it will return false.

## Examples
An example working implementation of this code can be found in the example.php file, which can be run either as:

```sh
php example.php
```
    
Which will allow you to generate a secret, then test it, or:

```sh
php example.php mysecretcode
```
    
Which will allow you to take an already existing code and again, test if your code is valid

## References
Other PHP Google Authenticator implementations:
-  https://github.com/chregu/GoogleAuthenticator.php
-  https://github.com/PHPGangsta/GoogleAuthenticator

Specification for Google Authenticator:
- https://github.com/google/google-authenticator/wiki/Key-Uri-Format
