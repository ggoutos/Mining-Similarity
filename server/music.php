<?php
//$server = "localhost";
//$username = "root";
//$password = "";
//$database = "test";

//$con = mysql_connect($server, $username, $password) or die ("Could not connect: " . mysql_error());

//mysql_select_db($database, $con);

$mysqli = new mysqli("localhost", "root", "", "mining_web");


$id = $_POST["id"];
$name = mysql_real_escape_string($_POST["name"]);
$genre = mysql_real_escape_string($_POST["genre"]);


if (!$mysqli->query("DELETE FROM `mining_web`.`music` WHERE `music`.`id` = $id")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "\n\ncomment added";
}


if (!$mysqli->query("INSERT INTO music VALUES ('$id', '$name', '$genre')")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "\n\ncomment added";
}



/*
if (!$mysqli->query("UPDATE `mining_web`.`music` SET `genre` = 'metal' WHERE `music`.`id` = '10212595263'")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
	echo "\n\ncomment added";
}
*/
?>