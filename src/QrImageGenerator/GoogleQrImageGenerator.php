<?php

namespace Dolondro\GoogleAuthenticator\QrImageGenerator;

use Dolondro\GoogleAuthenticator\Secret;

class GoogleQrImageGenerator implements QrImageGeneratorInterface
{
    public function __construct(protected int $width = 200, protected int $height = 200)
    {
    }

    public function generateUri(Secret $secret): string
    {
        return "https://chart.googleapis.com/chart?chs={$this->width}x{$this->height}&chld=M|0&cht=qr&chl=" . $secret->getUri();
    }
}
