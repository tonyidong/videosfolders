<?php

session_start();
if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}

$full_path = sprintf("/tmp/uploads/%s/%s/%s", $_SESSION['username'], $_SESSION['folder_name'] ,$_POST['file_name']);
 
if(file_exists($full_path))
{
    unlink($full_path);
    header("Location: folder_content.php");
}
else
{
    echo "file does not exist";
}

?>



