<?php

namespace Dolondro\GoogleAuthenticator;

class SecretFactory
{
    protected int $secretLength;

    // For some reason the maniac who came up with base32 encoding decided they hated 0 and 1...
    protected string $base32Chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";

    /**
     * @param int $secretLength - this is the length of the encoded string, as such, it must be divisible by 8
     */
    public function __construct(int $secretLength = 16)
    {
        if ($secretLength == 0 || $secretLength % 8 > 0) {
            throw new \InvalidArgumentException("Secret length must be longer than 0 and divisible by 8");
        }
        $this->secretLength = $secretLength;
    }

    /**
     * The spec technically allows you to only have an accountName not an issuer, but as it's strongly recommended,
     * I don't feel particularly guilty about forcing it in the create.
     */
    public function create(string $issuer, string $accountName): Secret
    {
        return new Secret($issuer, $accountName, $this->generateSecretKey());
    }

    /**
     * Generates a secret key!
     *
     * Interestingly, the easiest way to get truly random key is just to iterate through the base 32 chars picking random
     * characters
     */
    public function generateSecretKey(): string
    {
        $key = "";
        while (strlen($key) < $this->secretLength) {
            $key .= $this->base32Chars[random_int(0, 31)];
        }

        return $key;
    }
}
