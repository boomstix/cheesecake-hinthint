<?php

$sendgrid_user = "app24109921@heroku.com";
$sendgrid_pass = "woumzahx";

$db_host = "127.0.0.1";
$db_username = "hinthint";
$db_password = "hinthint";
$db_schema = "hinthint";

if ($db_url = getenv("CLEARDB_DATABASE_URL")) {

	# Heroku Clear DB configuration
	$db_url = parse_url($db_url);
	$db_host = $db_url["host"];
	$db_username = $db_url["user"];
	$db_password = $db_url["pass"];
	$db_schema = substr($db_url["path"],1)
	;
}

$db_err = false;
$db_ex_msg = '';

define( 'ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] );
define( 'HEADER_PATH', '/assets/php/header.php' );
define( 'FOOTER_PATH', '/assets/php/footer.php' );
define( 'SCRIPT_PATH', '/assets/php/scripts.php' );

// Strict email validation
function isValidEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

?>