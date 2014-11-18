<?php
session_start();
 
require 'database.php';


if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}
$folder_id= $_POST['folder_id'];
// Use a prepared statement

$stmt0 = $mysqli->prepare("delete FROM video WHERE folder_id= ?");
 
// Bind the parameter
$stmt0->bind_param('s', $folder_id);
$stmt0->execute();


$stmt = $mysqli->prepare("delete FROM folders WHERE id= ?");
 
// Bind the parameter
$stmt->bind_param('s', $folder_id);
$stmt->execute();
header("Location: folder_page.php");



?>