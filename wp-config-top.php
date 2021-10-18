<?php
require_once __DIR__.'/../get-aws-secret.function.php';
$db_secret=get_aws_secret('db/caladan/credentials');
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', $db_secret['dbname'] );

/** MySQL database username */
define( 'DB_USER', $db_secret['username'] );

/** MySQL database password */
define( 'DB_PASSWORD', $db_secret['password'] );

/** MySQL hostname */
define( 'DB_HOST', $db_secret['host'].':'.$db_secret['port'] );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
