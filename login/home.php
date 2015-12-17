<?php
	if($_SERVER['HTTPS'] != 'on'){
		die(header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	}
	session_start();
	$user = $_SESSION['user'];
	if(!isset($user)){
		die(header("Location: index.php"));
	}
	if(isset($_POST['logout'])){
		session_unset();
		session_destroy();
		die(header("Location: index.php"));
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?=ucfirst($user)?>'s Home</title>
		<meta charset="UTF-8"> 
		<meta name="viewport" content="width=400">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<form method="post" class="center">
			<?php
				if($_SESSION['type']){
					echo "<h1>Welcome Admin!<br>You have super privileges.</h1><br>";
				}else{
					echo "<h1>Welcome ".ucfirst($user)."!<br>You are logged in.</h1><br>";
				}
			?>
			<input type="submit" name="logout" value="log out">
		</form>
	</body>
</html>