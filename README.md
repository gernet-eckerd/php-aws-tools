# php-aws-tools

Convenience tools for AWS, utilizing the AWS SDK for PHP.

## Installation

<pre>composer install</pre>

## Add AWS Secret to wp-config.php

<pre lang="php">
<?php
// add these two lines to the top (double-check the path to the aws tools install directory)
require_once __DIR__.'/../php-aws-tools/get-aws-secret.function.php';
$db_secret=get_aws_secret('db/caladan/credentials');

// database settings
define( 'DB_NAME', $db_secret['dbname'] );
define( 'DB_USER', $db_secret['username'] );
define( 'DB_PASSWORD', $db_secret['password'] );
define( 'DB_HOST', $db_secret['host'].':'.$db_secret['port'] );

// table prefix can also be kept in the secret
$table_prefix = $db_secret['tableprefix'];
</pre>