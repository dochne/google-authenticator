<?php

namespace Dolondro\GoogleAuthenticator\QrImageGenerator;

use Dolondro\GoogleAuthenticator\Secret;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Exception\ValidationException;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\WriterInterface;

class EndroidQrImageGenerator implements QrImageGeneratorInterface
{
    public function __construct(
        protected int $size = 200,
        protected WriterInterface $writer = new PngWriter(),
        protected string $labelText = '',
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function generateUri(Secret $secret): string
    {
        $builder = new Builder(
            writer: $this->writer,
            writerOptions: [],
            validateResult: false,
            data: $secret->getUri(),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $this->size,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            labelText: $this->labelText,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );

        $result = $builder->build();
        return $result->getDataUri();
    }
}
