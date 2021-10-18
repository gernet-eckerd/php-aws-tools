<?php
require __DIR__.'/vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

function get_aws_secret($secretName) {
    $client = new SecretsManagerClient([
        'version' => '2017-10-17',
        'region' => 'us-east-1',
    ]);

    $result = $client->getSecretValue([
        'SecretId' => $secretName,
    ]);

    if (isset($result['SecretString'])) {
        $secret = $result['SecretString'];
    } else {
        $secret = base64_decode($result['SecretBinary']);
    }

    $decoded_secret = json_decode($secret, true);
    return $decoded_secret;
}
