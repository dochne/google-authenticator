<?php

namespace Dolondro\GoogleAuthenticator\QrImageGenerator;

use Dolondro\GoogleAuthenticator\Secret;

class GoogleQrImageGenerator implements QrImageGeneratorInterface
{
    protected $width;
    protected $height;

    public function __construct($width = 200, $height = 200)
    {
        if (!is_numeric($width) || !is_numeric($height)) {
            throw new \InvalidArgumentException("Both width and height are required to be numeric");
        }

        $this->width = $width;
        $this->height = $height;
    }

    public function generateUri(Secret $secret)
    {
        return "https://chart.googleapis.com/chart?chs={$this->width}x{$this->height}&chld=M|0&cht=qr&chl=".$secret->getUri();
    }
}
