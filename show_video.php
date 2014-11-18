<?php
//var_dump($_POST);
session_start();

if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}
if (isset($_POST)&& !empty($_POST))
{
$full_link= $_POST['link'];
$pieces= explode("watch?v=",$full_link);
$link=$pieces[1];

// echo $link;

?>

<html>
    <head>
        <title>Show Video</title>
	<link href="main.css" rel="stylesheet" type="text/css">  
    </head>
    <body>
            <p>
		<div id="whateverID" style="margin: 0 auto">
		    <iframe width="750" height="600" frameborder="0" title="YouTube video player" type="text/html" src="<?php echo "http://www.youtube.com/embed/".$link."?enablejsapi=1" ?>" style="display: block;margin: 0 auto;">
		    </iframe>
		</div>
	    </p>
	    <br/>
    </body>
</html>

<form action="folder_content.php" method="POST" >
    <h2>Go back to folder</h2>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    <input type="submit" value="back to folder">
    
</form>
<?php

}

?>