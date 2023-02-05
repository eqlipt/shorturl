<?php

require_once 'db_credentials.php';
require_once 'classes/db.class.php';
require_once 'classes/url.class.php';
require_once 'classes/utility.class.php';

$message = '';
$error = '';

function pre( $array ) {
    echo "<pre>";
    print_r( $array );
    echo "</pre>";
}

function db_connect() {
	$connection = mysqli_connect( DB_HOST, DB_USER, DB_PASS, DB_NAME) ;
	confirm_db_connect();
	mysqli_set_charset( $connection, "utf8" );
	return $connection;
}

function confirm_db_connect() {
	if(mysqli_connect_errno()){
		$msg = "System message: Database connection failed: ";
		$msg .= mysqli_connect_error();
		$msg .= " (" . mysqli_connect_errno() . ")";
		exit($msg);
	}
}

function confirm_query_result( $query_result, $sql='' ) {
	if ( !$query_result ) {
		exit( "Database query failed: " . $sql );
	}
}

function db_escape( $db, $string ) {
	return mysqli_real_escape_string( $db, $string );
}

?>