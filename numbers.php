<?php
	if($_SERVER['HTTPS'] != 'on'){
		die(header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	}
	session_start();
	$id = $_SESSION['id'];
	if(!isset($id)){
		die(header("Location: login/index.php"));
	}

	include("db.conf");
	if(!$link = new mysqli($dbhost, $dbuser, $dbpass, $dbname)){
		echo "Connection Error: ".$link->error;
	}else if(isset($_POST['submit'])){
		$sql = "UPDATE business SET funding_needed=?, funding_use=? WHERE id=$id";
		if($stmt = $link->prepare($sql)){
			$need = $_POST['need'.$i] ? htmlspecialchars($_POST['need'.$i]) : NULL;
			if($stmt->bind_param("ss", $need, htmlspecialchars($_POST['use']))){
				if(!$stmt->execute()){
					echo "Execution Failed: ".$stmt->error;
					$error++;
				}
			}else{
				echo "Bind Error";
				$error++;
			}
			$stmt->close();
		}else{
			echo "Prepare Failed";
			$error++;
		}

		$sql = "INSERT INTO milestones(id, entry, when_quarter, when_year, what) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE when_quarter=?, when_year=?, what=?";
		if($stmt = $link->prepare($sql)){
			for($i = 0; $i < 5; $i++){
				$quarter =  $_POST['quarter'.$i];
				$year = $_POST['year'.$i] ? htmlspecialchars($_POST['year'.$i]) : NULL;
				$what = htmlspecialchars($_POST['what'.$i]);
				if($stmt->bind_param("ssssssss", $id, $i, $quarter, $year, $what, $quarter, $year, $what)){
					if(!$stmt->execute()){
						echo "Execution Failed: ".$stmt->error;
						$error++;
					}
				}else{
					echo "Bind Error";
					$error++;
				}
			}
			$stmt->close();
		}else{
			echo "Prepare Failed";
			$error++;
		}

		$sql = "INSERT INTO financial_projections(id, entry, income, expenses, net) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE income=?, expenses=?, net=?";
		if($stmt = $link->prepare($sql)){
			for($i = 1; $i < 4; $i++){
				$income = $_POST['income'.$i] ? htmlspecialchars($_POST['income'.$i]) : NULL;
				$expenses = $_POST['expenses'.$i] ? htmlspecialchars($_POST['expenses'.$i]) : NULL;
				$net = $_POST['net'.$i] ? htmlspecialchars($_POST['net'.$i]) : NULL;
				if($stmt->bind_param("ssssssss", $id, $i, $income, $expenses, $net, $income, $expenses, $net)){
					if(!$stmt->execute()){
						echo "Execution Failed: ".$stmt->error;
						$error++;
					}
				}else{
					echo "Bind Error";
					$error++;
				}
			}
			$stmt->close();
		}else{
			echo "Prepare Failed";
			$error++;
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>The Numbers</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=600">
		<link rel="stylesheet" type="text/css" href="numbers.css">
		<?php include "bootstrap.conf"; ?>
	</head>
	<body>
		<?php include "navbar.html"; ?>
        <script>
            $(".nav > .active").removeClass('active');
            $("#num").addClass('active');
        </script>
		<h1 id="head">The Numbers</h1>
		<br>
		<h2>Funding Needed: </h2>
		<form method="post">
			<?php
				if($link){
					$sql = "SELECT funding_needed, funding_use FROM business WHERE id=$id";
					if($result = $link->query($sql)){
						$row = $result->fetch_array();
						echo "<h2>$</h2><input type='number' name='need' min='0' max='4294967296' placeholder='Amount' value='".$row['funding_needed']."' id='big'><br>";
						echo "<textarea name='use' rows='5' cols='60' maxlength='250' placeholder='How will the funds be used?' id='big'>".$row['funding_use']."</textarea><br><br>";
						$result->free();
					}else{
						echo "Query Error: ".$link->error;
					}
				}
			?>

			<br><h2 id="mile">Milestones Post Funding</h2><br>
			<table>
				<tr>
					<th id="left">When</th>
					<th id="right">What</th>
				</tr>
				<?php
					if($link){
						$sql = "SELECT when_quarter, when_year, what FROM milestones WHERE id=$id";
						if($result = $link->query($sql)){
							for($i = 0; $i < 5; $i++){
								$row = $result->fetch_array();
								echo "<tr><td><select name='quarter$i'>";
								foreach(array("Q1", "Q2", "Q3", "Q4") as $value){
									if($row['when_quarter'] == $value){
										echo "<option value='$value' selected>$value</option>";
									}else{
										echo "<option value='$value'>$value</option>";
									}
								}
								echo "</select><input type='number' name='year$i' min='".date('Y')."' max='".(date('Y') + 10)."' placeholder='Year' value='".$row['when_year']."' id='year'></td>";
								echo "<td><textarea name='what$i' rows='3' cols='60' maxlength='150' placeholder='Info'>".$row['what']."</textarea></td></tr>";
							}
							$result->free();
						}else{
							echo "Query Error: ".$link->error;
						}
					}
				?>
			</table><br>

			<h2 class="blue">Financial Projections at a Glance</h2>
			<table>
				<tr>
					<th></th>
					<th>Income</th>
					<th>Expenses</th>
					<th>Net</th>
				</tr>
				<?php
					if($link){
						$sql = "SELECT income, expenses, net FROM financial_projections WHERE id=$id";
						if($result = $link->query($sql)){
							for($i = 1; $i < 4; $i++){
								$row = $result->fetch_array();
								echo "<tr><th id='pad'>Year $i</th><td>$<input type='number' name='income$i' min='0' max='4294967296' placeholder='Income' value='".$row['income']."'></td>";
								echo "<td>$<input type='number' name='expenses$i' min='0' max='4294967296' placeholder='Expenses' value='".$row['expenses']."'></td>";
								echo "<td>$<input type='number' name='net$i' min='0' max='4294967296' placeholder='Net' value='".$row['net']."'></td></tr>";
							}
							$result->free();
						}else{
							echo "Query Error: ".$link->error;
						}
						$link->close();
					}
				?>
			</table><br>
			<input type="submit" name="submit" value="submit">
		</form>
		<?php
			if(isset($_POST['submit'])){
				if($error){
					echo "<br><h2 class='red'>Submission Failed</h2><br>";
				}else{
					echo "<br><h2>Submission Successful</h2><br>";
				}
			}else{
				echo "<p>IMPORTANT: Press the submit button to save your progress!</p><br>";
			}
		?>
	</body>
</html>
