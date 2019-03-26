<?php


$dbname = "Auction System";
$message = '';
$dbhost = '';
$dbusername = '';
$dbpassword = '';

foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_localdb") !== 0) {
        continue;
    }
    
    $dbhost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    $dbusername = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $dbpassword = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
}


$db = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);

if ($db->connect_error) {
	$message = $db->connect_error;
}

session_start();


?>