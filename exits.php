<?php
//session setup
session_start();
$id = $_SESSION['id'];

//secure request
if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) {
    $url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header('Location: ' . $url);
}//end if

if (!isset($id)) {
        die(header("Location: login/index.php"));
}

//require database information
require 'db.conf';

//connect
if(!$link = mysqli_connect ($dbhost, $dbuser, $dbpass, $dbname)) {
        echo "Connection Error: ".$link->error;
}

//submit form
if (isset($_POST['submit'])) {
	//Update business table
	$sql = "UPDATE business SET exit_strategy=?, acquisition_amount=?, regulatory_issues=? WHERE id=$id";
	if ($stmt = $link->prepare($sql)) {
		if ($stmt->bind_param("sis", htmlspecialchars($_POST['exit_strategy']), htmlspecialchars($_POST['likelyAc']), htmlspecialchars($_POST['regulatory_issues']))) {
			if (!$stmt->execute()) {
				echo "Execution Failed: ".$stmt->error;
				$error++;
			}
		}else {
			echo "Bind Error";
			$error++;
		}
	}else {
		echo "Prepare Failed";
		$error++;
	}

	//Insert or Update comparable_exits table
	for ($i = 1; $i <= $_POST['ceCount']; $i++) {
		if ($_POST['company'.$i] != '' && $_POST['exit_amount'.$i] != '' && $_POST['acquirer'.$i] != '') {
			$sql = "INSERT INTO comparable_exits(id, ceID, company, exit_amount, acquirer) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE company=?, exit_amount=?, acquirer=?";
			if ($stmt = $link->prepare($sql)) {
				$company = htmlspecialchars($_POST['company'.$i]);
				$exit_amount = htmlspecialchars($_POST['exit_amount'.$i]);
				$acquirer = htmlspecialchars($_POST['acquirer'.$i]);
				if ($stmt->bind_param("ssssssss", $id, $i, $company, $exit_amount, $acquirer, $company, $exit_amount, $acquirer)) {
					if (!$stmt->execute()) {
						echo "CE Execution Failed ($i): ".$stmt->error;
						$error++;
					}
				}else {
					echo "CE Bind Error ($i)";
					$error++;
				}
			}else {
				echo "CE Prepare Failed ($i)";
				$error++;
			}
		}//end outer if
		else if ($i != $_POST['ceCount']) {
			//do nothing
		}//end else if
		else {
			echo '<p>Row '.($i - 1).' of Comparable Exits table not added, missing data.</p>';
		}
	}//end for

	//Insert or Update rounds_anticipated table
	for ($j = 1; $j <= $_POST['arCount']; $j++) {
		if ($_POST['amount'.$j] != '' && $_POST['years'.$j] != '') {
			$sql = "INSERT INTO rounds_anticipated(id, round_entry, amount, years) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE amount=?, years=?";
			if ($stmt = $link->prepare($sql)) {
				$amount = htmlspecialchars($_POST['amount'.$j]);
				$years = htmlspecialchars($_POST['years'.$j]);
				if ($stmt->bind_param("ssssss", $id, $j, $amount, $years, $amount, $years)) {
					if (!$stmt->execute()) {
						echo "RA Execution Failed ($j): ".$stmt->error;
						$error++;
					}
				}else {
					echo "RA Bind Error ($j)";
					$error++;
				}
			}else {
				echo "RA Prepare Failed ($j)";
				$error++;
			}
		}//end outer if
		else if ($j != $_POST['arCount']) {
			//do nothing
		}//end else if
		else {
			echo '<p>Row '.($j - 1).' of Anticipated Rounds table not added, missing data.</p>';
		}
	}//end for

}//end form submit
?>


<html>
    <head>
        <title>Exits</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=600">
	<link rel="stylesheet" type="text/css" href="exits.css">
    <?php include "bootstrap.conf"; ?>
    </head>

    <body>
        <?php include "navbar.html"; ?>
        <script>
            $(".nav > .active").removeClass('active');
            $("#exit").addClass('active');
        </script>
	<form method='POST' id='form1' name='exits'>
		<h1 id='titleHead'>Exit Information</h1>
		<br>

		<div id='exitStratDiv'>
	    		<h2>Exit Strategy</h2>
			<table name="exitStratTable" id="exitStratTable" class="table">
               			<?php
               			$sql1 = "SELECT exit_strategy FROM business WHERE id =".$id;
				if ($result1 = mysqli_query($link, $sql1)) {
                    			while($row = $result1->fetch_array()) {
                       				echo "<tr>
							<td><textarea name='exit_strategy' placeholder='Enter strategy description here.'>".$row['exit_strategy']."</textarea></td>
							</tr>";
                			}
				}else {
					echo "Query Error: ".$link->error;
				}?>
                   	</table>
		</div>

		<div id='likelyAcDiv'>
			<h2>Likely Acquisition Amount</h2>
			<table name="likelyAcquisitionTable" id="likelyAcquisitionTable" class="table">
				<?php
				$sql11 = "SELECT acquisition_amount FROM business WHERE id =".$id;
				if ($result11 = mysqli_query($link, $sql11)) {
					while($row = $result11->fetch_array()) {
						echo "<tr>
							<td><input type='number' id='likelyAc' name='likelyAc' placeholder='Likely Amount' value=".$row['acquisition_amount']." id='big'></td>
							</tr>";
					}
				}else {
					echo "Query Error: ".$link->error;
				}?>
			</table>
		</div>

		<div id='regIssuesDiv'>
	    		<h2>Regulatory Issues</h2>
            		<table name="regIssues" id="regIssues" class="table">
				<?php
                		$sql2 = "SELECT regulatory_issues FROM business WHERE id=".$id;
                		if ($result2 = mysqli_query($link, $sql2)) {
                    			while($row = $result2->fetch_array()) {
                        			echo "<tr>
                               				<td><textarea name='regulatory_issues' placeholder='Enter any regulatory issues here.'>".$row['regulatory_issues']."</textarea></td>
                              				</tr>";
                    			}
				}else {
					echo "Query Error: ".$link->error;
				}?>
            		</table>
            	</div>
		<br><br>

		<h2 id='compExits' class='tableHead'>Comparable Exits</h2>
		<div class='tableDiv'>
        	    	<table name="comparable_exits" id="comp_exits" class="table">
                		<thead>
					<th class='left'>Company</th>
					<th class='middle'>Exit Amount</th>
					<th class='right'>Acquirer</th>
				</thead>
				<?php
                		$sql3 = "SELECT company, exit_amount, acquirer FROM comparable_exits WHERE id =".$id;
                		if ($result3 = mysqli_query($link, $sql3)) {
					$i = 1;
                    			while($row = $result3->fetch_array()) { ?>
                        			<tr>
							<td><input type='text' name='company<?=$i?>' placeholder='Company' value='<?=$row['company']?>'></td>
                        	        		<td><input class='exitsNum' type='number' name='exit_amount<?=$i?>' placeholder='Exit Amount' value='<?=$row['exit_amount']?>'></td>
                        	        		<td><input type='text' name='acquirer<?=$i?>' placeholder='Acquirer' value='<?=$row['acquirer']?>'></td>
                        	      		</tr> <?php
						$i++;
                    			}
				}else {
					echo "Query Error: ".$link->error;
				}?>
				<tr>
					<td><input type='text' name='company<?=$i?>' placeholder='Company'></td>
					<td><input class='exitsNum' type='number' name='exit_amount<?=$i?>' placeholder='Exit Amount'></td>
					<td><input type='text' name='acquirer<?=$i?>' placeholder='Acquirer'></td>
				</tr>
            		</table>
		</div>
        	<br><br>

		<h2 id='antRounds' class='tableHead'>Additional Rounds Anticipated</h2>
		<div class='tableDiv'>
            		<table name="rounds_anticipated" id="ant_rounds" class="table">
				<thead>
					<th class='left'>Round Entry</th>
					<th class='middle'>Amount</th>
					<th class='right'>Years</th>
				</thead>
                		<?php
                		$sql4 = "SELECT round_entry, amount, years FROM rounds_anticipated WHERE id =".$id;
				if ($result4 = mysqli_query($link, $sql4)) {
					$i = 1;
                    			while($row = $result4->fetch_array()) { ?>
                        			<tr>
							<td><input type='text' name='round_entry<?=$i?>' value='<?=$row['round_entry']?>' readonly></td>
                                			<td><input class='exitsNum' type='number' name='amount<?=$i?>' placeholder='Amount' value='<?=$row['amount']?>'></td>
                                			<td><input class='exitsNum' type='number' name='years<?=$i?>' placeholder='Years' value='<?=$row['years']?>'></td>
                              			</tr> <?php
						$i++;
                    			}
				}else {
					echo "Query Error: ".$link->error;
				}?>
				<tr>
					<td><input type='text' name='round_entry<?=$i?>' value='<?=$i?>' readonly></td>
					<td><input class='exitsNum' type='number' name='amount<?=$i?>' placeholder='Amount'></td>
					<td><input class='exitsNum' type='number' name='years<?=$i?>' placeholder='Years'></td>
				</tr>
            		</table>
		</div>
		<br><br>

		<input type='submit' name='submit' value='submit' onClick="countRows()">
		<input type='hidden' name='ceCount' id='ceCount' value=0>
		<input type='hidden' name='arCount' id='arCount' value=0>
	</form>

	<script>
		function countRows() {
			var ceTable = document.getElementById('comp_exits');
			var ceRowCount = ceTable.rows.length;
			var ceCount = document.getElementById('ceCount');
			ceCount.value = ceRowCount;

			var arTable = document.getElementById('ant_rounds');
			var arRowCount = arTable.rows.length;
			var arCount = document.getElementById('arCount');
			arCount.value = arRowCount;
		}//end countRows function
	</script>

	<p>IMPORTANT: Press the submit button to save your progress!</p>
    </body>
</html>
