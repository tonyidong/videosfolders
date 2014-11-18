<?php
    session_start();
?>

<!DOCTYPE HTML>
<html>
<head>
    <title> Login Failed</title>
    <link href="main.css" rel="stylesheet" type="text/css">   
</head>

<body>
	
	<h1>Login Failed! Click here back to home page</h1>

	<form action="home.php" method="POST">
	    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
	    <input type="submit" value="Click here to Homepage">
	</form>
</body>

</html>