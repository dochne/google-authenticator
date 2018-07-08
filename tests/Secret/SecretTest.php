<?php

namespace Dolondro\GoogleAuthenticator\Tests\Secret;

use Dolondro\GoogleAuthenticator\Secret;
use PHPUnit\Framework\TestCase;

class SecretTest extends TestCase
{
    /**
     * Tests that realistically, aren't going to fail.
     */
    public function testBasicFunctionality()
    {
        $secret = new Secret("ReynolmIndustries", "MrSmith", "SecretKey");
        $this->assertEquals("ReynolmIndustries", $secret->getIssuer());
        $this->assertEquals("MrSmith", $secret->getAccountName());
        $this->assertEquals("SecretKey", $secret->getSecretKey());
    }

    public function testGetUri()
    {
        $secret = new Secret("Example", "alice@google.com", "JBSWY3DPEHPK3PXP");
        $this->assertEquals("otpauth://totp/Example%3Aalice%40google.com?secret=JBSWY3DPEHPK3PXP&issuer=Example", $secret->getUri());
    }

    // Based off examples as given here:
    // https://github.com/google/google-authenticator/wiki/Key-Uri-Format
    /*public function testLabelGeneration()
    {
        $secret = new Secret("ReynolmIndustries", "MrSmith", "SecretKey");
        $this->assertEquals("ReynolmIndustries:MrSmith");
        //label = accountname / issuer (“:” / “%3A”) *”%20” accountname
        //Valid values might include Example:alice@gmail.com, Provider1:Alice%20Smith or Big%20Corporation%3A%20alice%40bigco.com.
    }*/
}
