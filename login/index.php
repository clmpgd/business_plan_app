<?php
	if($_SERVER['HTTPS'] != 'on'){
		die(header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	}
	session_start();
	if(isset($_SESSION['user']) != ""){
		die(header("Location: ../user.php"));
	}
	if(isset($_POST['signin'])){
		include("../db.conf");
		if($link = new mysqli($dbhost, $dbuser, $dbpass, $dbname)){
			$sql = "SELECT hashed_password, is_admin FROM users WHERE BINARY username=?";
			if($stmt = $link->prepare($sql)){
				$user = htmlspecialchars($_POST['user']);
				if($stmt->bind_param("s", $user)){
					if($stmt->execute()){
						$stmt->bind_result($hash, $type);
						if($stmt->fetch()){
							if(password_verify(htmlspecialchars($_POST['pass']), $hash)){
								$_SESSION['user'] = $user;
								$_SESSION['type'] = $type;
								die(header("Location: ../user.php"));
							}else{
								$error = "Incorrect Password";
							}
						}else{
							$error = "Invalid Username";
						}
					}else{
						$error = "Execution Failed: ".$stmt->error;
					}
				}else{
					$error = "Bind Error";
				}
				$stmt->close();
			}else{
				$error = "Prepare Failed";
			}
			$link->close();
		}else{
			$error = "Connection Error: ".$link->error;
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=400">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="limit">
			<form method="post" autocomplete="off">
				<h1>
					Username<br>
					<input type="text" name="user" placeholder="username" required><br>
					Password<br>
				</h1>
				<input type="password" name="pass" placeholder="password" required><br>
				<input type="submit" name="signin" value="sign in" class="right">

			</form>
			<form action="new.php" autocomplete="off">
				<input type="submit" name="register" value="register">
			</form>
		</div>
		<?php
			if(isset($_POST['signin'])){
				echo "<br><div class='center'><h1 class='fail'>".$error."</h1></div>";
			}
		?>
	</body>
</html>
