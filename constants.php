<?php 
session_start();

date_default_timezone_set("Europe/London");

$servername = "localhost";
$username = "andytest_me";
$password = "Pa$$.999";
$database = "andytest_news_db";

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


?>