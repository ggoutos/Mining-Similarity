<?php
//$server = "localhost";
//$username = "root";
//$password = "";
//$database = "test";

//$con = mysql_connect($server, $username, $password) or die ("Could not connect: " . mysql_error());

//mysql_select_db($database, $con);

$mysqli = new mysqli("localhost", "root", "", "mining_web");


$user_id = $_POST["user_id"];
$music_id = $_POST["music_id"];


if (!$mysqli->query("INSERT INTO likes_music VALUES ('$user_id', '$music_id')")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "\n\ncomment added";
}
?>