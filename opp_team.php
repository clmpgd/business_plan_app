<?php
    session_start();

    if(!isset($_SESSION['user'])) {
        header("Location: /kander/login/index.php");
    }
    if(!isset($_SESSION['id'])) {
		header("Location: /kander/login/index.php");
	}

    $prob = '';
    $prob_val = '';
    $sol = '';
    $sol_val = '';
    $sol_status = '';
    $feedback = '';

    require "db.conf";

    $link = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
    $id = $_SESSION['id'];

    $sql = "select * from opportunity where id = $id";
    $sql4 = "select * from team where name in (select name from team_business where id = $id)";

    $result = mysqli_query($link,$sql);
    if(mysqli_num_rows($result) > 0) {
        $sql = "update opportunity set problem=?,problem_valid=?,solution=?,solution_valid=?,solution_status=? where id=?";
        $data = mysqli_fetch_array($result);
        $prob = $data[1];
        $prob_val = $data[2];
        $sol = $data[3];
        $sol_val = $data[4];
        $sol_status = $data[5];
    } else {
        $sql = "insert into opportunity (problem,problem_valid,solution,solution_valid,solution_status,id) values (?,?,?,?,?,?)";
    }
    mysqli_free_result($result);


    $sql2 = "insert into team values (?,?,?) on duplicate key update title=?, info=?";
    $name = '';
    $title = '';
    $info = '';

    if(isset($_POST['submit'])) {
        $stmt = mysqli_prepare($link,$sql);

        $prob = htmlspecialchars($_POST['problem']);
        $prob_val = htmlspecialchars($_POST['prob_val']);
        $sol = htmlspecialchars($_POST['solution']);
        $sol_val = htmlspecialchars($_POST['sol_val']);
        $sol_status = htmlspecialchars($_POST['sol_status']);
        print_r($_POST);

        mysqli_stmt_bind_param($stmt,"sssssd",$prob,$prob_val,$sol,$sol_val,$sol_status,$id) or die("bind fail");
        if(mysqli_stmt_execute($stmt)) {
            $feedback = "Changes Saved";
            mysqli_stmt_close($stmt);
            if($_POST['name'] != '') {
                $stmt = mysqli_prepare($link,$sql2);
                $name = htmlspecialchars($_POST['name']);
                $title = htmlspecialchars($_POST['title']);
                $info = htmlspecialchars($_POST['info']);
                mysqli_stmt_bind_param($stmt,"sssss",$name,$title,$info,$title,$info);
                if(mysqli_stmt_execute($stmt)) {
                    $sql3 = "insert into team_business values ($id,'$name')";
                    if(mysqli_query($link,$sql3)) {
                        $feedback = "Changes Saved";
                    }
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $feedback = "Unable to Save Changes";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Opportunity</title>
        <link rel="stylesheet" href="numbers.css">
        <style>
            input.opp {
                width: 400px;
            }
        </style>
        <?php include "bootstrap.conf"; ?>
    </head>
    <body>
        <?php include "navbar.html"; ?>
        <script>
            $(".nav > .active").removeClass('active');
            $("#opp").addClass('active');
        </script>
        <h2>Opportunity</h2><br>
        <form action="opp_team.php" method="POST" id="opp">
            <label for="problem">Problem:</label><br>
            <input class="opp" type="text" name="problem" id="problem" maxlength="255" value="<?php print $prob;?>"></input><br>
            <!--<textarea name="problem" form="opp" id="problem" maxlength="255"><?php //print $prob;?></textarea><br>-->
            <label for="prob_val">How Problem Validated:</label><br>
            <input class="opp" type="text" name="prob_val" id="prob_val" maxlength="255" value="<?php print $prob_val;?>"></input><br>
            <!--<textarea name="prob_val" form="opp" id="prob_val" maxlength="255"><?php //print $prob_val;?></textarea><br>-->
            <label for="solution">Solution:</label><br>
            <input class="opp" type="text" name="solution" id="solution" maxlength="255" value="<?php print $sol;?>"></input><br>
            <!--<textarea name="solution" form="opp" id="solution" maxlength="255"><?php //print $sol;?></textarea><br>-->
            <label for="sol_val">How Solution Validated:</label><br>
            <input class="opp" type="text" name="sol_val" id="sol_val" maxlength="255" value="<?php print $sol_val;?>"></input><br>
            <!--<textarea name="sol_val" form="opp" id="sol_val" maxlength="255"><?php //print $sol_val;?></textarea><br>-->
            <label for="sol_status">Current Status of Solution</label><br>
            <input class="opp" type="text" name="sol_status" id="sol_status" maxlength="255" value="<?php print $sol_status;?>"></input><br>
            <!--<textarea name="sol_status" form="opp" id="sol_status" maxlength="255"><?php //print $sol_status;?></textarea><br>--><br>
            <h2>Team</h2><br>
            <table>
                <tr>
                    <th>Name</th><th>Title</th><th>Description</th>
                </tr>
                <?php
                    $result = mysqli_query($link,$sql4);
                    while($row = mysqli_fetch_row($result)) {
                ?>
                    <tr>
                        <?php print "<td>$row[0]</td><td>$row[1]</td><td>$row[2]</td>"; ?>
                    </tr>
                <?php
                    }
                    mysqli_free_result($result);
                ?>
                <tr>
                    <td><input type="text" name="name"></input></td><td><input type="text" name="title"></input></td><td><input type="text" name="info"></input></td>
                </tr>
            </table>
            <p>To update existing team entry, enter their name and updated title/description in the inputs</p>
            <input type="submit" name="submit" value="Submit"></input>
        </form>
        <br>
        <p>IMPORTANT: Press the submit button to save your progress!</p>
        <p><?php print $feedback;?></p>
    </body>
</html>
<?php
    mysqli_close($link);
?>
