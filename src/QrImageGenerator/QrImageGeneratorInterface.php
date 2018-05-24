<?php

namespace Dolondro\GoogleAuthenticator\QrImageGenerator;

use Dolondro\GoogleAuthenticator\Secret;

interface QrImageGeneratorInterface
{
    public function generateUri(Secret $secret);
}
