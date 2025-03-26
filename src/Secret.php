<?php

namespace Dolondro\GoogleAuthenticator;

class Secret
{
    public function __construct(protected string $issuer, protected string $accountName, protected string $secretKey)
    {
        // As per spec sheet
        if (str_contains($issuer . $accountName, ":")) {
            throw new \InvalidArgumentException("Neither the 'Issuer' parameter nor the 'AccountName' parameter may contain a colon");
        }
    }

    public function getUri(): string
    {
        return "otpauth://totp/" . rawurlencode($this->getLabel()) . "?secret=" . $this->getSecretKey() . "&issuer=" . rawurlencode($this->getIssuer());
    }

    public function getLabel(): string
    {
        return $this->issuer . ":" . $this->accountName;
    }

    public function getIssuer(): string
    {
        return $this->issuer;
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}
