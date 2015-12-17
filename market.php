<!--
Robert Fink
rwfwcb
CMP_SC 3380
DIANA KANDER DBMS
-->

<?php
/* start the session */
session_start();

if(!isset($_SESSION['user'])) {
		header("Location: /kander/login/index.php");
}

if(!isset($_SESSION['id'])) {
header("Location: /kander/login/index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>The Market</title>
	<link rel="stylesheet" type="text/css" href="market.css">
	<?php include "bootstrap.conf"; ?>
</head>
<body>
	<?php include "navbar.html"; ?>
	<script>
		$(".nav > .active").removeClass('active');
		$("#mark").addClass('active');
	</script>
	
	<?php
	/* start the session */
	session_start();

	if(!isset($_SESSION['user'])) {
			header("Location: /kander/login/index.php");
	}

	if(!isset($_SESSION['id'])) {
	header("Location: /kander/login/index.php");
	}

	/* require credentials! */
	require "db.conf";

	/* connect to database */
	$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	/* check connection */
	if (!$link){
			printf("Connect failed: %s\n", mysqli_connect_error());
	}

	/* has the form been submitted? */
	if(isset($_POST['submit'])){
		/* create a prepared statement */
		if ($stmt = mysqli_prepare($link, "INSERT INTO market (id, groups, amount) VALUES (?, ?, ?)")){
		/* assign variables */
		$id = htmlspecialchars($_SESSION['id']);
		$groups = htmlspecialchars($_POST['group']);
		$amount = htmlspecialchars($_POST['amount']);
		/* bind variables to marker */
		mysqli_stmt_bind_param($stmt, 'sss', $id, $groups, $amount);
		/* execute the prepared statement */
		mysqli_stmt_execute($stmt);
		/* close statement */
		mysqli_stmt_close($stmt);
		} else {
			echo "prepare failed";
		}

		/* create a prepared statement */
		if ($stmt = mysqli_prepare($link, "INSERT INTO intellectual_property (id, info) VALUES (?, ?)")){
		/* assign variables */
		$id = htmlspecialchars($_SESSION['id']);
		if ($_POST['info'] != NULL){
			$info = htmlspecialchars($_POST['info']);
		}
		if ($info != NULL){
			/* bind variables to marker */
			mysqli_stmt_bind_param($stmt, 'ss', $id, $info);
			/* execute the prepared statement */
			mysqli_stmt_execute($stmt);
		}
		/* close statement */
		mysqli_stmt_close($stmt);
		} else {
			echo "prepare failed";
		}

		/* create a prepared statement */
		if ($stmt = mysqli_prepare($link, "INSERT INTO competitive_analysis (id, behavior, advantage) VALUES (?, ?, ?)")){
		/* assign variables */
		$id = htmlspecialchars($_SESSION['id']);
		if ($_POST['behavior'] != NULL){
			$behavior = htmlspecialchars($_POST['behavior']);
		}
		if ($_POST['advantage'] != NULL){
			$advantage = htmlspecialchars($_POST['advantage']);
		}
		if ($behavior != NULL && $advantage != NULL){
			/* bind variables to marker */
			mysqli_stmt_bind_param($stmt, 'sss', $id, $behavior, $advantage);
			/* execute the prepared statement */
			mysqli_stmt_execute($stmt);
		}
			/* close statement */
			mysqli_stmt_close($stmt);
			} else {
				echo "prepare failed";
			}
	}
	?>


<form action="market.php" method="POST" id="form1"></form>
	<br><br><h1 id='market'>Market Info</h1><br>
	<?php
	/* run prepared queries to see if any data is already present in market tables */
		/* create a prepared statement */
			if($stmt = mysqli_prepare($link, "SELECT * FROM market WHERE id=?")){
			/* bind variables to marker */
			mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
			/* execute query */
			mysqli_stmt_execute($stmt);
			/* store result */
			mysqli_stmt_store_result($stmt);
			printf("%d results<br>", mysqli_stmt_num_rows($stmt));
			/* bind result variables */
			mysqli_stmt_bind_result($stmt, $id, $groups, $amount);
			/* print table headers */
			echo "<table><thead><tr><th></th><th>Target Group</th><th>Amount</th></tr></thead>";
			while (mysqli_stmt_fetch($stmt)){ /* print output */
				?>
				<form action="marketUpdate.php" method="POST">
					<input type='hidden' name='table' value='market'>
					<input type='hidden' name='id' value='<?=$id?>'>
					<input type='hidden' name='groups' value='<?=$groups?>'>
					<input type='hidden' name='amount' value='<?=$amount?>'>
					<tr>
						<td><input type='submit' name='update' value='Update'></td>
				</form>
				<?php
				echo "<td><textarea rows='5' cols='60' maxlength='32' class='groupInput' readonly>".$groups."</textarea></td>";
				echo "<td><textarea rows='5' cols='60' maxlength='10' class='amountInput' readonly>".$amount."</textarea></td></tr>";
			}
			echo "<tr><td></td><td><textarea form='form1' rows='5' cols='60' maxlength='32' name='group' class='data' placeholder='Enter target group here...'></textarea></td>";
			echo "<td><textarea form='form1' rows='5' cols='60' maxlength='10' name='amount' class='data' placeholder='Enter the amount here...'></textarea></td></tr>";
			echo "</table>";

			/* close statement */
			mysqli_stmt_close($stmt);
	}	?>


	<br><br><h1 class='blue'>Sustainable Competitive Advantage/Intellectual Property</h2><br>
	<?php
	/* run prepared queries to see if any data is already present in intellectual_property table */
		/* create a prepared statement */
			if($stmt = mysqli_prepare($link, "SELECT * FROM intellectual_property WHERE id=?")){
			/* bind variables to marker */
			mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
			/* execute query */
			mysqli_stmt_execute($stmt);
			/* store result */
			mysqli_stmt_store_result($stmt);
			printf("%d results<br>", mysqli_stmt_num_rows($stmt));
			/* bind result variables */
			mysqli_stmt_bind_result($stmt, $id, $entry, $info);
			/* print table headers */
			echo "<table><thead><tr><th></th><th>Information</th></tr></thead>";
			while (mysqli_stmt_fetch($stmt)){ /* print output */
				?>
				<form action="marketUpdate.php" method="POST">
					<input type="hidden" name="table" value="intellectualProperty">
					<input type="hidden" name="id" value="<?=$id?>">
					<input type="hidden" name="entry" value="<?=$entry?>">
					<input type="hidden" name="info" value="<?=$info?>">
					<tr>
						<td><input type="submit" name="update" value="Update"></td>
				</form>
				<?php
				echo "<td><textarea rows='5' cols='60' maxlength='256' class='data' readonly>".$info."</textarea></td></tr>";
			}
			echo "<tr><td></td><td><textarea form='form1' rows='5' cols='60' maxlength='256'name='info' class='data' placeholder='Enter information here...'></textarea></td></tr>";
			echo "</table>";
		/* close statement */
		mysqli_stmt_close($stmt);
		}	?>


	<br><br><h1 class='blue'>Competitive Analysis</h1><br>
	<?php
	/* run prepared queries to see if any data is already present in intellectual_property table */
		/* create a prepared statement */
			if($stmt = mysqli_prepare($link, "SELECT * FROM competitive_analysis WHERE id=?")){
			/* bind variables to marker */
			mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
			/* execute query */
			mysqli_stmt_execute($stmt);
			/* store result */
			mysqli_stmt_store_result($stmt);
			printf("%d results<br>", mysqli_stmt_num_rows($stmt));
			/* bind result variables */
			mysqli_stmt_bind_result($stmt, $id, $entry, $behavior, $advantage);
			/* print table headers */
			echo "<table><thead><tr><th></th><th>Current Behavior</th><th>Our Competitive Advantage</tr></thead>";
			while (mysqli_stmt_fetch($stmt)){ /* print output */
				?>
				<form action="marketUpdate.php" method="POST">
					<input type="hidden" name="table" value="competitiveAnalysis">
					<input type="hidden" name="id" value="<?=$id?>">
					<input type="hidden" name="entry" value="<?=$entry?>">
					<input type="hidden" name="behavior" value="<?=$behavior?>">
					<input type="hidden" name="advantage" value="<?=$advantage?>">
					<tr>
						<td><input type="submit" name="update" value="Update"></td>
				</form>
				<?php
				echo "<td><textarea class='data' rows='5' cols='60' maxlength='256' readonly>".$behavior."</textarea></td>";
				echo "<td><textarea class='data' rows='5' cols='60' maxlength='256' readonly>".$advantage."</textarea></td></tr>";
			}
			echo "<tr><td></td><td><textarea form='form1' rows='5' cols='60' maxlength='256' name='behavior' class='data' placeholder='Enter current market behaviors here...'></textarea></td>";
			echo "<td><textarea form='form1' rows='5' cols='60' maxlength='256' name='advantage' class='data' placeholder='Enter your competitive advantage here...'></textarea></td></tr>";
			echo "</table>";
		/* close statement */
		mysqli_stmt_close($stmt);
		}	?>
	<input type="submit" form="form1" name="submit" value="Submit">

	<?php
		if(isset($_POST['submit'])){
			if($error){
				echo "<br><br><br><h2 class='red'>Submission Failed</h2>";
			}else{
				echo "<br><br><br><h2>Submission Successful</h2>";
			}
		}else{
			echo "<br><p>IMPORTANT: Press the submit button to save your progress!</p>";
		}
	?>
</body>
</html>
