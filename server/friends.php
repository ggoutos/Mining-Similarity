<?php
//$server = "localhost";
//$username = "root";
//$password = "";
//$database = "test";

//$con = mysql_connect($server, $username, $password) or die ("Could not connect: " . mysql_error());

//mysql_select_db($database, $con);

//$mysqli->query("SELECT username, password, manager FROM ergazomenoi WHERE username='$_POST[username]' AND password='$_POST[password]'");

//$row = mysql_fetch_array($mysqli);

//if ($row!=NULL) {}



$mysqli = new mysqli("localhost", "root", "", "mining_web");
$con = mysqli_connect("localhost", "root", "", "mining_web");


$user_a = $_POST["user_a"];
$user_b = $_POST["user_b"];
$friends = $_POST["friends"];
$music["counter"]='0';
$movies["counter"]='0';

//$user_a='10205263907698525';
//$user_b='811952728891756';
//$friends='140';


//$sql="UPDATE friendships SET friends = '$friends' WHERE user_a='$user_a' and user_b='$user_b'";
//$sql="INSERT INTO friendships VALUES ('$user_a', '$user_b', '$friends', '0', '0')";

/*
if (!$mysqli->query($sql)) {
			echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
		} else {
			echo "\n\ncomment added";
		}

*/




//koinh mousikh
$result=mysqli_query($con, "SELECT count(*) AS counter FROM likes_music a LEFT JOIN likes_music b ON a.user_id = '".$user_a."' AND b.user_id = '".$user_b."' WHERE a.music_id = b.music_id ");

if (!$result) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
		echo "\n\ncomment added";
		$music = mysqli_fetch_assoc($result);
}


//koines tainies
$result=mysqli_query($con, "SELECT count(*) AS counter FROM likes_movies a LEFT JOIN likes_movies b ON a.user_id = '".$user_a."' AND b.user_id = '".$user_b."' WHERE a.movie_id = b.movie_id ");

if (!$result) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
		echo "\n\ncomment added";
		$movies = mysqli_fetch_assoc($result);
}


//insert friendship

if (!$mysqli->query("INSERT INTO friendships VALUES ('$user_a', '$user_b', '$friends', '".$music["counter"]."', '".$movies["counter"]."')")) {
			echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
		} else {
			echo "\n\ncomment added";
		}

?>