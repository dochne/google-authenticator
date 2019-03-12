<?php

namespace Dolondro\GoogleAuthenticator\Tests\Authenticator;

use Dolondro\GoogleAuthenticator\GoogleAuthenticator;
use PHPUnit\Framework\TestCase;

class AuthenticatorTest extends TestCase
{
    const SECRET = "G2XLNTQRVES7JF3V";

    const CODE = "081446";

    const TIME = 1456073370;

    public function testCalculateCode()
    {
        $authenticator = new GoogleAuthenticator();
        $this->assertEquals(self::CODE, $authenticator->calculateCode(self::SECRET, self::TIME / 30));
        $this->assertEquals("818888", $authenticator->calculateCode("OX35UDZUWP23WBUA", 48535782));
    }

    public function testAuthenticate()
    {
        $authenticator = new GoogleAuthenticator(self::TIME);
        self::assertTrue($authenticator->authenticate(self::SECRET, self::CODE));

        // user is 30 seconds ahead
        $authenticator = new GoogleAuthenticator(self::TIME - 30);
        self::assertTrue($authenticator->authenticate(self::SECRET, self::CODE));

        // user is 59 seconds before, so we test for a newer code
        $authenticator = new GoogleAuthenticator(self::TIME + 59);
        self::assertTrue($authenticator->authenticate(self::SECRET, self::CODE));

        $authenticator = new GoogleAuthenticator(self::TIME - 31);
        self::assertFalse($authenticator->authenticate(self::SECRET, self::CODE));

        $authenticator = new GoogleAuthenticator(self::TIME + 60);
        self::assertFalse($authenticator->authenticate(self::SECRET, self::CODE));
    }
}
