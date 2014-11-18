<?php
session_start();
 
require 'database.php';


if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}
$video_id= $_POST['video_id'];
// Use a prepared statement
$stmt = $mysqli->prepare("delete FROM video WHERE id= ?");
 
// Bind the parameter
$stmt->bind_param('s', $video_id);
$stmt->execute();
header("Location: folder_content.php");



?>