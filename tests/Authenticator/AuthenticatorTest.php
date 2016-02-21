<?php

namespace Dolondro\GoogleAuthenticator\Tests\Authenticator;

use Base32\Base32;
use Dolondro\GoogleAuthenticator\GoogleAuthenticator;
use Dolondro\GoogleAuthenticator\Secret;
use Dolondro\GoogleAuthenticator\SecretFactory;

class AuthenticatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCalculateCode()
    {
        $authenticator = new GoogleAuthenticator();
        $this->assertEquals("081446", $authenticator->calculateCode("G2XLNTQRVES7JF3V", 48535779));
        $this->assertEquals("818888", $authenticator->calculateCode("OX35UDZUWP23WBUA", 48535782));
    }

}