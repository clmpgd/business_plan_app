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

/* require credentials! */
require "db.conf";

/* connect to database */
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

/* check connection */
if (!$link){
		printf("Connect failed: %s\n", mysqli_connect_error());
}

//display non-editable textbox for attribute $key
function printNonEditable($key) {
	echo "<div>";
	echo "<label>".$key.":	</label>";
	echo "<textarea form='form2' type='text' name='".$key."' readonly>$_POST[$key]</textarea>";
	echo "</div>";
}

//display editable textbox for attribute $key
function printInput($key) {
	echo "<div>";
	echo "<label>".$key.":	</label>";
	echo "<textarea form='form2' type='text' name='".$key."' required>$_POST[$key]</textarea>";
	echo "</div>";
}

//editable form for records from the Market table
function displayMarket() {
	echo "<form action='marketUpdate.php' method='POST' id='form2'>";
	echo "<input type='hidden' name='table' value='market'>";
	echo "<input type='hidden' name='group' value='".$_POST['groups']."''>";
	printNonEditable('id');
	printInput('groups');
	printInput('amount');
	echo "<input type='submit' name='save' value='Save'>";
	echo "<a href='market.php'>Go back</a>";
	echo "</form>";
}

//editable form for records from the intellectual_property table
function displayIntellectualProperty() {
	echo "<form action='marketUpdate.php' method='POST' id='form2'>";
	echo "<input type='hidden' name='table' value='intellectualProperty'>";
	printNonEditable('id');
	printNonEditable('entry');
	printInput('info');
	echo "<input type='submit' name='save' value='Save'>";
	echo "<a href='market.php'>Go back</a>";
	echo "</form>";
}

//editable form for records from the competitive_analysis table
function displayCompetitiveAnalysis() {
	echo "<form action='marketUpdate.php' method='POST' id='form2'>";
	echo "<input type='hidden' name='table' value='competitiveAnalysis'>";
	printNonEditable('id');
	printNonEditable('entry');
	printInput('behavior');
	printInput('advantage');
	echo "<input type='submit' name='save' value='Save'>";
	echo "<a href='market.php'>Go back</a>";
	echo "</form>";
}

function saveMarket() {
	global $link;
	$sql = "UPDATE market SET id=?, groups=?, amount=? WHERE id=? AND groups=?";
	if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
		mysqli_stmt_bind_param($stmt, "dsdds", $_POST['id'], $_POST['groups'], $_POST['amount'], $_POST['id'], $_POST['group']) or die("bind param");
		if(mysqli_stmt_execute($stmt)) {//execute successful
			echo "<br><br><h2>Successfully Saved Record</h2><br>";
		} else {
			echo "<br><br><h2>Error: unable to update record.</h2>";
		}
		//close the prepared statement
		mysqli_stmt_close($stmt);
		}	else { //prepare failed
		echo "<br><h2>Error: prepare failed.</h2>";
		}
	}

function saveIntellectualProperty() {
	global $link;
	$sql = "UPDATE intellectual_property SET info=? WHERE entry=? AND id=?";
	if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
		mysqli_stmt_bind_param($stmt, "sdd", $_POST['info'], $_POST['entry'], $_POST['id']) or die("bind param");
		if(mysqli_stmt_execute($stmt)) {//execute successful
			echo "<br><br><h2>Successfully Saved Record</h2><br>";
		} else {
			echo "<br><br><h2>Error: unable to update record.</h2>";
		}
		// close the prepared statement
		mysqli_stmt_close($stmt);
	} else { //prepare failed
		echo "<br><h2>Error: prepare failed.</h2>";
	}
}

function saveCompetitiveAnalysis() {
	global $link;
	$sql = "UPDATE competitive_analysis SET behavior=?, advantage=? WHERE entry=? AND id=?";
	if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
		mysqli_stmt_bind_param($stmt, "ssdd", $_POST['behavior'], $_POST['advantage'], $_POST['entry'], $_SESSION['id']) or die("bind param");
		if(mysqli_stmt_execute($stmt)) {//execute successful
			echo "<br><br><h2>Successfully Saved Record</h2><br>";
		} else {
			echo "<br><br><h2>Error: unable to update record.</h2>";
		}
		//close the prepared statement
		mysqli_stmt_close($stmt);
	} else { //prepare failed
		echo "<br><h2>Error: prepare failed.</h2>";
	}
}
?>


<!DOCTYPE html>
<html>
	<head>
		<title>Update Data</title>
		<link rel="stylesheet" type="text/css" href="market.css">
		<?php include "bootstrap.conf"; ?>
	</head>
	<body>
		<?php include "navbar.html"; ?>
		<div class="container">

<?php
	if(isset($_POST['update'])) {//submit came from index.php
		if(isset($_POST['table'])) {//do we have table information?
			switch($_POST['table']) {//what table are we updating
				case "market":
					echo "<br><h2>Edit Market Record</h2><br>";
					displayMarket();
					break;

				case "intellectualProperty":
					echo "<br><h2>Edit Intellectual Property Record</h2><br>";
					displayIntellectualProperty();
					break;

				case "competitiveAnalysis":
					echo "<br><h2>Edit Competitive Analysis Record</h2><br>";
					displayCompetitiveAnalysis();
					break;

				default:
					echo "<br><h2>Error: unable to update record.</h2><br>";
					break;
			}
		}
	}

	else if(isset($_POST['save'])) {//submit came from request to save form data
		if(isset($_POST['table'])) {//do we have table information?
			switch($_POST['table']) {//what table are we updating
				case "market":
					echo "<br><h2>Edit Market Record</h2><br>";
					displayMarket();
					saveMarket();
					break;

				case "intellectualProperty":
					echo "<br><h2>Edit Intellectual Property Record</h2><br>";
					displayIntellectualProperty();
					saveIntellectualProperty();
					break;

				case "competitiveAnalysis":
					echo "<br><h2>Edit Competitive Analysis Record</h2><br>";
					displayCompetitiveAnalysis();
					saveCompetitiveAnalysis();
					break;

				default:
					//Failed
					echo "<br><h2>Error: unable to update record.</h2><br>";
					break;
			}
		}
	}
	/* close connection */
	mysqli_close($link);
?>
		</div>
	</body>
</html>
