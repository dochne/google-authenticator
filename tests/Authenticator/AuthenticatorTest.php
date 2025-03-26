<?php

namespace Dolondro\GoogleAuthenticator\Tests\Authenticator;

use Dolondro\GoogleAuthenticator\GoogleAuthenticator;
use Dolondro\GoogleAuthenticator\Tests\Helper\ArrayPsr16Cache;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;

class AuthenticatorTest extends TestCase
{
    public function testCalculateCode(): void
    {
        $authenticator = new GoogleAuthenticator();
        $this->assertEquals("818888", $authenticator->calculateCode("OX35UDZUWP23WBUA", 48535782));
    }

    /**
     * @param int  $timeOffset
     * @param int  $window
     * @param bool $success
     * @testWith    [0, 1, true]
     *              [-30, 1, true]
     *              [30, 1, true]
     *              [69, 1, false]
     *              [60, 1, false]
     *              [60, 2, true]
     *              [-31, 1, false]
     *              [-59, 2, true]
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testAuthenticate(int $timeOffset, int $window, bool $success): void
    {
        $secret = "G2XLNTQRVES7JF3V";
        $code = "081446";
        $baseTime = 1456073370;

        $options = [
            "time" => $baseTime + $timeOffset,
            "window" => $window,
        ];

        $authenticator = new GoogleAuthenticator($options);
        self::assertSame($success, $authenticator->authenticate($secret, $code));
    }

    public function testReplayAttackPsr6(): void
    {
        $secret = "G2XLNTQRVES7JF3V";
        $code = "081446";
        $time = 1456073370;
        $options = ["time" => $time];
        $authenticator = new GoogleAuthenticator($options);
        $authenticator->setCache(new PhpArrayAdapter(
            __DIR__ . '/../file.cache',
            new FilesystemAdapter(),
        ));
        $this->assertTrue($authenticator->authenticate($secret, $code));
        $this->assertFalse($authenticator->authenticate($secret, $code));
    }

    public function testReplayAttackPsr16(): void
    {
        $secret = "G2XLNTQRVES7JF3V";
        $code = "081446";
        $time = 1456073370;
        $options = ["time" => $time];
        $authenticator = new GoogleAuthenticator($options);
        $authenticator->setCache(new ArrayPsr16Cache());
        $this->assertTrue($authenticator->authenticate($secret, $code));
        $this->assertFalse($authenticator->authenticate($secret, $code));
    }
}
