<?php
$workingDir = $argv[1] ? $argv[1] : __DIR__;
$instance = $argv[2] ? $argv[2] : 'dev/test';
$source = $workingDir."/wp-config.php";
$target = $workingDir."/wp-config-secrets.php";

echo "Adding secrets to $source in new file $target...\n";

if (($copied = copy($source, $target)) === FALSE) {
    die("Failed to copy $source to $target\n");
}

if (($configStr = file_get_contents($target)) === FALSE) {
    die("Failed to read $target\n");
}

$winLines = strpos($configStr, chr(13).chr(10));
if ($winLines !== FALSE) {
    $lineDel = chr(13).chr(10);
}
else {
    $lineDel = chr(10);
}

$requires = implode($lineDel, [
    '<?php',
    'require_once(__DIR__."/../php-aws-tools/get-aws-secret.function.php");',
    "\$db_secret = get_aws_secret(\"$instance/db\");",
    "\$salts_secret = get_aws_secret(\"$instance/salts\");"
]);

// require and secret fetches
$configStr = str_replace("<?php", $requires, $configStr);

$defines = [
    "DB_NAME" => '$db_secret["dbname"]',
    "DB_USER" => '$db_secret["username"]',
    "DB_PASSWORD" => '$db_secret["password"]',
    "DB_HOST" => '$db_secret["host"].":".$db_secret["port"]',
    "AUTH_KEY" => '$salts_secret["AUTH_KEY"]',
    "SECURE_AUTH_KEY" => '$salts_secret["SECURE_AUTH_KEY"]',
    "LOGGED_IN_KEY" => '$salts_secret["LOGGED_IN_KEY"]',
    "NONCE_KEY" => '$salts_secret["NONCE_KEY"]',
    "AUTH_SALT" => '$salts_secret["AUTH_SALT"]',
    "SECURE_AUTH_SALT" => '$salts_secret["SECURE_AUTH_SALT"]',
    "LOGGED_IN_SALT" => '$salts_secret["LOGGED_IN_SALT"]',
    "NONCE_SALT" => '$salts_secret["NONCE_SALT"]',
];
/*
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );
*/

$configStr = preg_replace_callback('/define\([ ]*[\'"]([^\'"]+)[\'"],[ ]*[\'"][^\'"]+[\'"][ ]*\);/i', function($matches){
    global $defines;
    if (array_key_exists($matches[1], $defines)) {
        return 'define("'.$matches[1].'", '.$defines[$matches[1]].');';
    }
    else {
        return $matches[0];
    } 
}, $configStr);

$variables = [
    'table_prefix' => '$db_secret["tableprefix"]'
];

$configStr = preg_replace_callback('/\$([a-zA-Z0-9_]+)[ ]*=[ ]*[\'"][^;]+[\'"];/', function($matches){
    global $variables;
    if (array_key_exists($matches[1], $variables)) {
        return '$'.$matches[1].' = '.$variables[$matches[1]].';';
    }
    else {
        return $matches[0];
    }
}, $configStr);

echo $configStr;

if (file_put_contents($target, $configStr) === FALSE) {
    die("Failed to write new config data to $target\n");
}

