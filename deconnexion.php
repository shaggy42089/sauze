<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>deco</title>
</head>
<body>
	<?php 
		session_start();
		session_destroy();
		session_unset();
		header('Location: connection.php');
	?>
</body>
</html>