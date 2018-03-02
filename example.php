<?php

include "vendor/autoload.php";

if (isset($argv[1])) {
    $secretKey = $argv[1];
    echo "Secret passed in as argument!\n";
    echo "Your secret is ".$secretKey."\n";
} else {
    $secretFactory = new \Dolondro\GoogleAuthenticator\SecretFactory();
    $secret = $secretFactory->create("MyAwesomeWebCo", "Dolondro");
    $secretKey = $secret->getSecretKey();

    $qrImageGenerator = new \Dolondro\GoogleAuthenticator\QrImageGenerator\GoogleQrImageGenerator();
    // $qrImageGenerator = new \Dolondro\GoogleAuthenticator\QrImageGenerator\EndroidQrImageGenerator();

    echo "Your secret is: ".$secretKey."\n";
    echo "Visit this URL: ".$qrImageGenerator->generateUri($secret)." and add it to your google authenticator app\n";
}

$googleAuthenticator = new \Dolondro\GoogleAuthenticator\GoogleAuthenticator();

while (true) {
    echo "Enter the code that has been given in the app:\n";
    $handle = fopen("php://stdin", "r");
    while (($code = trim(fgets($handle))) === "") {
        echo "Response required\n";
    }

    if ($googleAuthenticator->authenticate($secretKey, $code)) {
        echo "This code was valid!\n";
    } else {
        echo "This code was invalid =[\n";
    }

    echo "\n";
}
