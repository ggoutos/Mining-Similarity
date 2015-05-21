<?php
//$server = "localhost";
//$username = "root";
//$password = "";
//$database = "test";

//$con = mysql_connect($server, $username, $password) or die ("Could not connect: " . mysql_error());

//mysql_select_db($database, $con);

$mysqli = new mysqli("localhost", "root", "", "mining_web");


$id = mysql_real_escape_string($_POST["id"]);
$username = mysql_real_escape_string($_POST["name"]);
$firstname = mysql_real_escape_string($_POST["first"]);
$lastname = mysql_real_escape_string($_POST["last"]);
$email = mysql_real_escape_string($_POST["email"]);


if (!$mysqli->query("DELETE FROM `mining_web`.`users` WHERE `users`.`id` = $id")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "\n\ncomment added";
}


if (!$mysqli->query("INSERT INTO users VALUES ('$id', '$username', '$firstname', '$lastname', '$email')")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "\n\ncomment added";
}
?>

