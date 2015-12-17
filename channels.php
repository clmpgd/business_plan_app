<?php
	if($_SERVER['HTTPS'] != 'on')
	{
		die(header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
	}

	session_start();

	if(!isset($_SESSION['user'])) 
	{
                header("Location: /kander/login/index.php");
	}

	$id = $_SESSION['id'];

	require("db.conf");


	if(!$link = new mysqli($dbhost, $dbuser, $dbpass, $dbname))
	{
		echo "Connection Error: ".$link->error;
	}
	else if(isset($_POST['submit'])){


		$count = $_POST['count'];


		for($i=1; $i<$count; $i++){
			$sql = "INSERT INTO sales_channels (id, entry, info) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE info=?";
			if($stmt = $link->prepare($sql)){
				$id = $_SESSION['id'];
				$entry = $i;
				$info = htmlspecialchars($_POST['info'.$i]);
				//printf("the ID is %s", $id);
				if($stmt->bind_param("ssss", $id, $entry, $info, $info)){
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
		}


		$count1 = $_POST['count1'];


		for($i=1; $i<$count1; $i++){
                	$sql = "INSERT INTO market_efforts (id, entry, when_quarter, when_year, what) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE when_quarter=?, when_year=?, what=?";
                	if($stmt = $link->prepare($sql)){
				$quarter = $_POST['quarter'.$i] ? htmlspecialchars($_POST['quarter'.$i]) : NULL;
				$year = $_POST['year'.$i] ? htmlspecialchars($_POST['year'.$i]) : NULL;
				$entry = $i;
				$what = htmlspecialchars($_POST['what'.$i]);
				//printf(" the other ID is %s ", $id);
                        	if($stmt->bind_param("ssssssss", $id, $entry, $quarter, $year, $what, $quarter, $year, $what)){
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
                        	echo "Prepare Failed ";
                        	$error++;
                	}
        	}


		$count2 = $_POST['count2'];



         	for($i=1; $i<$count2; $i++){
         		$sql = "INSERT INTO partners (partnersID, name, title, info) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=?, title=?, info=?";
         		if($stmt = $link->prepare($sql)){
				$name = htmlspecialchars($_POST['name'.$i]);
		 		$title = htmlspecialchars($_POST['title'.$i]);
		 		$info = htmlspecialchars($_POST['resp'.$i]);
		 		$partnerID = $_POST['partner'.$i];
				printf("\n\n%d",$_POST['partner'.$i]);
                  		if($stmt->bind_param("sssssss", $partnerID, $name, $title, $info, $name, $title, $info)){
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
        	}




	 	for($i=1; $i<$count2; $i++){
         		$sql = "INSERT IGNORE INTO partners_business (id, partnersID) VALUES(?, ?)";
         		if($stmt = $link->prepare($sql)){
                 		$partnerID = $_POST['partner'.$i];
                  		if($stmt->bind_param("ss", $id, $partnerID)){
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
        	}


        }

	mysqli_close($link);
?>

<html>
	<head>
		<title>Sales/Partners</title>
		<meta charset = "UTF-8">
		<meta name="viewport"  content="width=600">
		<link rel="stylesheet" type="text/css" href="exits.css">
		<?php include "bootstrap.conf"; ?>
		<script>
			function addArea()
			{
				var tableRef = document.getElementById('channels');
				var newRow = tableRef.insertRow(tableRef.rows.length);
				var newCell = newRow.insertCell(0);
				var newCell1 = newRow.insertCell(0); 
				var y = tableRef.rows.length;
				y--;
				var h3 = document.createElement("h3");
				h3.appendChild(document.createTextNode("Entry " + y));
				newCell.innerHTML = "<textarea name = 'info"+y+"' rows = '5' cols = '60' maxlength = '250' placeholder='place your sales channel information in this box' form='input'>";
				newCell1.appendChild(h3);
			}

			function addEffort()
                        {
                                var tableRef = document.getElementById('market');
                                var newRow = tableRef.insertRow(tableRef.rows.length);
                                var newCell = newRow.insertCell(0);
                                var newCell1 = newRow.insertCell(0);
				var newCell2 = newRow.insertCell(0);
				var newCell3 = newRow.insertCell(0);
                                var y = tableRef.rows.length;
                                y--;
                                var h3 = document.createElement("h3");
                                h3.appendChild(document.createTextNode("Entry " + y));
                                newCell.innerHTML = "<textarea name = 'what"+y+"' rows = '5' cols = '60' maxlength = '250' placeholder='place your market information in this box' form='input'></textarea>";
                                newCell3.appendChild(h3);
				newCell2.innerHTML = "<select name='quarter"+y+"' form='input'><option value='Q1' selected>Q1</option><option value='Q2'>Q2</option><option value='Q3'>Q3</option><option value='Q4'>Q4</option></select>";
				newCell1.innerHTML = "<input type='number' name='year"+y+"' min='2015' max='2025' placeholder='Year' value='2015' id='year' form='input' style='width: 75px'>"; 
                        }

			function addPartner()
                        {
                                var tableRef = document.getElementById('partners');
				var rowNum = document.getElementById('partnersRows').value;
				var rowNum1 = tableRef.rows.length;
                                var newRow = tableRef.insertRow(tableRef.rows.length);
                                var newCell = newRow.insertCell(0);
                                var newCell1 = newRow.insertCell(0);
                                var newCell2 = newRow.insertCell(0);
                                var y = tableRef.rows.length;
                                y--;
				rowNum++;
				document.getElementById('partnersRows').value = rowNum;
                                newCell2.innerHTML = "<input type='hidden' name='partner"+rowNum1+"' value='"+rowNum+"' form = 'input'><textarea name = 'name"+y+"' rows = '1' cols = '20' maxlength = '30' placeholder='partner name' form='input'></textarea>";
                                newCell1.innerHTML = "<textarea name='title"+y+"' rows = '5' cols = '60' maxlength = '250' placeholder = 'place job title' form = 'input'></textarea>";
                                newCell.innerHTML = "<textarea name='resp"+y+"' rows = '5' cols = '60' maxlength = '250' placeholder = 'partner responsibilities' form='input'></textarea>";
                        }




			function countFunc()
			{
				var tableRef = document.getElementById('channels');
				var x = tableRef.rows.length;
				var count = document.getElementById("count");
				count.value = x;

				var tableRef = document.getElementById('market');
                                var y = tableRef.rows.length;
				var count1 = document.getElementById("count1");
                                count1.value = y;

				var tableRef = document.getElementById('partners');
                                var z = tableRef.rows.length;
				var count2 = document.getElementById("count2");
                                count2.value = z;
			}
		</script>

	</head>
	<body>
		<?php include "navbar.html"; ?>
		<script>
			$(".nav > .active").removeClass('active');
			$("#chan").addClass('active');
		</script>
		<br><h1 id='titleHead'>Market Efforts</h1><br>
		<br><h2 class=tableHead>Sales Channels</h2><br>

		<div class="tableDiv">
			<?php
				$query = "SELECT id, entry, info FROM sales_channels WHERE id=$id";
				$link = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die("this is an error" .mysqli_error($link));
				$result = mysqli_query($link, $query);
				$myrow = mysqli_num_rows($result);
				$i = 1;


				echo "<table id='channels'>";
				echo"<tr><th class='left'>Entry</th><th class='right'>Info</th></tr>";
				echo "<tbody>";

				if($myrow == 0)
				{
					echo "<tr><td><h3>Entry 1</h3></td><td><textarea name = 'info1' rows ='5' cols = '60' maxlength ='256' placeholder = 'place your sales channel information in this box' form ='input'></textarea></td></tr>";
				}
				while($row = mysqli_fetch_array($result))
					{
						echo "<tr>";
						echo "<td><h3>Entry ". $row["entry"] . "</h3></td>";
						echo "<td><textarea name = 'info".$i."' rows = '5' cols = '60' maxlength = '250' placeholder = 'place your sales channel information in this box' form = 'input'>" .$row["info"] . "</textarea></td>";
						echo "</tr>";
						$i++;
					}
					echo"</tbody>";
					echo"</table>";
					$result->free();
					echo "<button onclick = 'addArea()'>New Entry</button><br>";

				echo"</div><br><br>";
				echo"<h2 class=tableHead>Market Efforts</h2><br>";
				echo"<div class='tableDiv'>";

				$query2 = "SELECT id, entry, when_quarter, when_year, what FROM market_efforts WHERE id=$id";
				$result = mysqli_query($link, $query2);
                                $myrow = mysqli_num_rows($result);
                                echo "<table id ='market'>";
				echo"<tr><th class='left'>Entry</th><th class='middle' colspan='2'>Year/Quarter</th><th class='right'>Market Effort</th></tr>";
				$i = 1;
				if($myrow == 0)
				{
					echo"<tr><td><h3>Entry 1</h3></td><td><select name='quarter1' form='input'><option value='Q1' selected>Q1</option><option value='Q2'>Q2</option><option value'Q3'>Q3</option><option value='Q4'>Q4</option></select></td>";
					echo"<td><input type='number' name='year1' min='2015' max='2025' placeholder='Year' value='2015' id='year' form = 'input'></td>";
					echo"<td><textarea name = 'what1' rows = '5' cols = '60' maxlength = '256' placeholder = 'place your market effort information in this box' form = 'input'></textarea></td></tr>";
				}
                                while($row = mysqli_fetch_array($result))
                                {


					$selected = $row['when_quarter'];

                                        echo "<tr>";
					echo "<td><h3>Entry ". $row["entry"] . "</h3></td>";
					echo "<td><select name='quarter".$i."' form = 'input'>";
					foreach(array("Q1", "Q2", "Q3", "Q4") as $selected)
					{
						if($row['when_quarter'] == $selected)
						{
							echo "<option value ='$selected' selected>$selected</option>";
						}
						else
						{
							echo "<option value='$selected'>$selected</option>";
						}
					}
					echo "</select></td>";
					echo "<td><input type='number' name='year".$i."' min='".date('Y')."' max='".(date('Y') + 10)."' placeholder='Year' value=".$row['when_year']." id='year' form = 'input' style='width: 75px'></td>";
					echo "<td><textarea name = 'what".$i."' rows = '5' cols = '60' maxlength = '256' placeholder = 'place your market effort information in this box' form = 'input'>" .$row["what"] . "</textarea></td>";
                                        echo "</tr>";
					$i++;
				}
                                echo"</table>";
				echo "<button onclick = 'addEffort()'>New Entry</button><br>";

				echo"</div><br><br>";
				$result->free();



				//$query3 = "SELECT id, name FROM partners_business";

				$query = "SELECT * FROM partners";
				$result1 = mysqli_query($link, $query);
				$myrow1 = mysqli_num_rows($result1);

				$query3 = "SELECT partners.partnersID, partners.name, partners.title, partners.info, partners_business.id FROM partners INNER JOIN partners_business ON (partners_business.partnersID = partners.partnersID) WHERE partners_business.id=$id";
                                $result = mysqli_query($link, $query3);
                                $myrow = mysqli_num_rows($result);
				echo"<h2 class=tableHead>Partners</h2><br>";
				echo"<div class='tableDiv'>";
                                echo "<table id='partners'>";
				echo"<tr><th class='left'>Name</th><th class='middle'>Title</th><th class='right'>Info</th></tr>";
				$i =1;
				if($myrow == 0)
				{
					++$myrow1;
					echo "<tr><td><input type='hidden' name='partner".$i."' value = '".$myrow1."' id='partner".$myrow1."' form = 'input'></input><textarea name = 'name1' rows = '1' cols = '20' maxlength = '30' placeholder = 'partner name' form = 'input'></textarea></td>";
					echo "<td><textarea name = 'title1' rows = '5' cols = '60' maxlength = '250' placeholder = 'place job title' form = 'input'></textarea></td>";
					echo "<td><textarea name = 'resp1' rows = '5' cols = '60' maxlength = '250' placeholder = 'partner responsibilities' form = 'input'></textarea></td></tr>";
				}
				echo"<input type='hidden' name = 'partnersRows' value='".$myrow1."' id = 'partnersRows' form = 'input'>";
                                while($row = mysqli_fetch_array($result))
                                {
                                        echo "<tr>";
					echo "<td><input type='hidden' name='partner".$i."' value='".$row["partnersID"]."' id='partner".$row["partnerID"]."' form = 'input'></input><textarea name = 'name".$i."' rows = '1' cols = '20' maxlength = '30' placeholder = 'partner name' form = 'input'>" .$row["name"] . "</textarea></td>";
					echo "<td><textarea name = 'title".$i."' rows = '5' cols = '60' maxlength = '250' placeholder = 'place job title' form = 'input'>" .$row["title"] . "</textarea></td>";
					echo "<td><textarea name = 'resp".$i."' rows = '5' cols = '60' maxlength = '250' placeholder = 'partner responsibilities' form = 'input'>" .$row["info"] . "</textarea></td>";
                                        echo "</tr>";
					$i++;
                                }
                                echo"</table>";
				echo "<button onclick = 'addPartner()'>New Entry</button><br>";
				echo"</div><br><br>";
                                mysqli_close($link);


			?>



		<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id = "input">
                        <input type="submit" name = "submit" value="Submit" onclick = 'countFunc()'>
                        <input type="hidden" name = "count" value="0" id = "count">
                        <input type="hidden" name = "count1" value="0" id = "count1">
                        <input type="hidden" name = "count2" value="0" id = "count2">
                </form>

		<br><p>IMPORTANT: Press the submit button to save your progress!</p>


	</body>
</html>

