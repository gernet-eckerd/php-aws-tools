# php-aws-tools

Convenience tools for AWS, utilizing the AWS SDK for PHP.

## Installation

<pre>
$ sudo chown -R [webuser]:[webgroup] php-aws-tools
$ cd php-aws-tools
$ # optional: get composer
$ sudo chmod +x install_composer.sh
$ ./install_composer.sh
$ composer install
</pre>

## Add AWS Secret to wp-config.php

You can either modify the config file directly, or use the conversion script <code>secretify_wp_config.php</code>

<pre>
$ php secretify_wp_config.php /path/to/wordpress/ instance/name
$ cd /path/to/wordpress
$ mv wp-config.php wp-config-plaintext.php; mv wp-config-secrets.php wp-config.php
</pre>

If the script fails, or you prefer to modify the wp-config.php file directly:

<pre lang="php">
&lt;?php
// add these two lines to the top (double-check the path to the aws tools install directory)
require_once __DIR__.'/../php-aws-tools/get-aws-secret.function.php';
$db_secret=get_aws_secret('your/secret/name/here');

// database settings
define( 'DB_NAME', $db_secret['dbname'] );
define( 'DB_USER', $db_secret['username'] );
define( 'DB_PASSWORD', $db_secret['password'] );
define( 'DB_HOST', $db_secret['host'].':'.$db_secret['port'] );

// table prefix can also be kept in the secret
$table_prefix = $db_secret['tableprefix'];
</pre>

## Optional: Install wp-cli.yml in WordPress directory

This file will prevent wp-cli from writing to wp-config.php. This can help prevent you shooting yourself in the foot by overwriting the AWS secret array members with static strings.
