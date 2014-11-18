<?php

$mysqli = new mysqli('localhost', 'chengongxia', 'cgx1016', 'test');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>