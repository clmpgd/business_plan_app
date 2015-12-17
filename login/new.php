<?php
	if($_SERVER['HTTPS'] != 'on'){
		die(header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	}
	session_start();
	if(isset($_SESSION['user']) != ""){
		die(header("Location: ../user.php"));
	}
	if(isset($_POST['new'])){
		$user = htmlspecialchars($_POST['user']);
		$pass = htmlspecialchars($_POST['pass']);
		$name = htmlspecialchars($_POST['name']);
		$age = htmlspecialchars($_POST['age']);
		$zip = htmlspecialchars($_POST['zip']);
		if($pass == $_POST['verify']){
			if(strlen($user) > 4 && strlen($pass) > 4){
				if(strlen($user) < 33 && strlen($pass) < 73){
					if(ctype_alnum($user) && ctype_alnum($pass)){
						if(strlen($name) < 33 && ctype_alpha(str_replace(array(" ", "'", "-"), "", $name))){
							if(ctype_digit($age) && $age < 99 && strlen($zip) == 5 && ctype_digit($zip)){
								include("../db.conf");
									if($link = new mysqli($dbhost, $dbuser, $dbpass, $dbname)){
									$sql = "INSERT INTO users(username, hashed_password, name, age, sex, experience, zipcode) VALUES(?, ?, ?, ?, ?, ?, ?)";
									if($stmt = $link->prepare($sql)){
										if($hash = password_hash($pass, PASSWORD_BCRYPT)){
											if($stmt->bind_param("sssssss", $user, $hash, $name, $age, $_POST['sex'], $_POST['exp'], $zip)){
												if(!$stmt->execute()){
													$error = "username has already been taken";
												}
											}else{
												$error = "parameters could not be bound";
											}
										}else{
											$error = "hash password could not be created";
										}
										$stmt->close();
									}else{
										$error = "prepare statement could not be created";
									}
									$link->close();
								}else{
									$error = "could not connect to the server";
								}
							}else{
								$error = "age & zipcode must be accurate numbers";
							}
						}else{
							$error = "name must be a limited number of letters";
						}
					}else{
						$error = "username & password must be alphanumerical";
					}
				}else{
					$error = "username or password is too long";
				}
			}else{
				$error = "username or password is too short";
			}
		}else{
			$error = "passwords must be the same";
		}
		$updatesql = "insert into updates (user,info) values ('$user','Registered for website')";
		mysqli_query($link,$updatesql);
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Registration</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=400">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="center">
			<div class="limit">
				<h1>New User</h1>
				<form method="post" autocomplete="off">
					<input type="text" name="user" placeholder="username" required><br>
					<input type="password" name="pass" placeholder="password" required><br>
					<input type="password" name="verify" placeholder="verify password" required><br><br><br>
					<input type="text" name="name" placeholder="name" class="medium" required><br><br>
					<input type="text" name="age" placeholder="age" class="small" required>
					<input type="text" name="zip" placeholder="zip code" class="small" required><br><br>
					<p>Gender </p>
					<select name="sex">
						<option value="male">Male</option>
						<option value="female">Female</option>
					</select><br>
					<p>Entrepreneurial Experience </p>
					<select name="exp">
						<option value="high">High</option>
						<option value="medium">Medium</option>
						<option value="low" selected>Low</option>
					</select><br><br>
					<input type="submit" name="new" value="register" class="right">
				</form>
				<form action="index.php">
					<input type="submit" name="new" value="return">
				</form>
			</div>
			<?php
				if(isset($_POST['new'])){
					if($error){
						echo "<br><p class='fail'>".$error."</p>";
					}else{
						echo "<br><p>new user successfully created</p>";
					}
				}
			?>
		</div>
	</body>
</html>
