<?php

    session_start();

    if(!isset($_SESSION['user'])) {
        header("Location: /kander/login/index.php");
    }

    require "db.conf";

    $link = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

    if($_SESSION['type']==0) {
        $user = $_SESSION['user'];
        $sql = "select id, name from business where id in (select id from users_business where username='$user')";
    } elseif ($_SESSION['type']==1) {
        $sql = "select id, name from business";
    }
    $feedback = '';
    if(isset($_POST['submit'])) {
        $_SESSION['id'] = $_POST['business'];
        $id = $_SESSION['id'];
        $query = "select name from business where id = $id";
        $res = mysqli_query($link,$query);
        $row = mysqli_fetch_array($res);
        $_SESSION['bname'] = $row[0];
        mysqli_free_result($res);
        mysqli_close($link);
        header("Location: opp_team.php");
    }
    if(isset($_POST['create'])) {
        $insert = "insert into business (name,info) values (?,?)";
        $busname = htmlspecialchars($_POST['bus_name']);
        $description = htmlspecialchars($_POST['description']);
        $stmt = mysqli_prepare($link,$insert);
        mysqli_stmt_bind_param($stmt,"ss",$busname,$description);
        mysqli_stmt_execute($stmt);
        $newid = mysqli_insert_id($link);
        $rel = "insert into users_business values ($newid,'$user')";
        mysqli_query($link,$rel);
        mysqli_stmt_close($stmt);
        $user = $_SESSION['user'];
        $info = "Created $busname Business Plan";
        $updatesql = "insert into updates (user,info) values ('$user','$info')";
        mysqli_query($link,$updatesql);
        $feedback = "New Business Successfully Created";
    }

    if(isset($_POST['users'])) {
        $username = $_POST['user'];
        $det = "select * from users where username = '$username'";
        $userresult = mysqli_query($link,$det);
        $userrow = mysqli_fetch_array($userresult);
        $name = $userrow[3];
        $age = $userrow[4];
        $exp = $userrow[5];
        $sex = $userrow[6];
        $zip = $userrow[7];
        $userdetails = "$username - Name: $name, Age: $age, Experience: $exp, Sex: $sex, Zipcode: $zip";
        mysqli_free_result($userresult);
    }
    if(isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "delete from updates where id=$id";
        mysqli_query($link,$sql);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>User Page</title>
        <style>
            body {
                text-align: center;
            }
            select {
                width: 200px;
            }
            textarea {
                width: 200px;
                height: 100px;
                resize: none;
            }
            input[type="text"] {
                width: 200px;
            }
            h2 {
                width: 400px;
                background-color: #43C6DB;
                border-radius: 50px;
                margin: auto;
                padding: 5px;
                color: white;
            }
        </style>
        <script>
            function clicked(e) {
                if(!confirm('Confirm Deletion?'))e.preventDefault();
            }
        </script>
        <?php include "bootstrap.conf"; ?>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
              <div class="container">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                  <ul class="nav navbar-nav navbar-right">
                      <li class='active'><a href="user.php"><?php print $_SESSION['user'] ?> Home</a></li>
                      <li><a href="/kander/login/logout.php">Logout</a></li>
                  </ul>
                </div><!--/.nav-collapse -->
              </div>
            </nav>
            <br><br><br>
        <h2>Select Business Plan</h2>
        <form action="user.php" method="POST">
            <select id="business" name="business">
                <?php
                    $result = mysqli_query($link,$sql);
                    while($row = mysqli_fetch_row($result)){
                        print "<option value='$row[0]'>$row[1]</option>";
                    }
                    mysqli_free_result($result);
                ?>
            </select>
            <input type="submit" name="submit" value="Go"></input>
        </form>
        <?php if($_SESSION['type'] == 0) { ?>
        <h2>Create A New Business Plan</h2>
        <form id="new" action="user.php" method="POST">
            <label for="bus_name">Business Name</label><br>
            <input type="text" id="bus_name" name="bus_name" maxlength="32"></input><br>
            <label for="description">Short Description</label><br>
            <textarea form="new" id="description" name="description" maxlength="256"></textarea><br>
            <input type="submit" name="create" value="Create"></input>
        </form>

        <?php
                print "<p>$feedback</p>";
            } else if ($_SESSION['type'] == 1) {
        ?>
        <h2>View User Details</h2>
        <form action="user.php" method="POST">
            <select id="user" name="user">
                <?php

                    $usersql = "select username from users";
                    $users = mysqli_query($link,$usersql);
                    while($row = mysqli_fetch_row($users)) {
                        print "<option value='$row[0]'>$row[0]</option>";
                    }
                    mysqli_free_result($users);

                ?>
            </select>
            <input type="submit" name="users" Value="Go"></input>
        </form>

        <?php
                if(isset($_POST['users'])) {
                    print "<p>$userdetails</p>";
                }

                $updatesql = "select * from updates";
                $updates = mysqli_query($link,$updatesql);
                print "<h2>Updates</h2>";
                if(mysqli_num_rows($updates) > 0) {
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th><th>ID</th><th>User</th><th>Updated</th><th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
            <?php
    			while($row = mysqli_fetch_array($updates)) {
    		?>
    		<tr id=<?php print "'$row[0]'"?>>
                <?php print "<td class='buttontd'>
                        <form action='user.php' method='POST' class='buttonForm'>
                            <input type='hidden' class='hbtn' name='id' value=\"".$row[0]."\">
                            <input type='submit' name='delete' value='Delete' class='btn' onclick='clicked(event)'></form>";
                    print "<td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>";
                ?>
    		</tr>
    		<?php
    			}
                mysqli_free_result($updates);
    		?>
            </tbody>
        </table>
        <?php
                } else {
                    print "No Updates";
                }
            }
        ?>
    </body>
</html>
<?php mysqli_close($link); ?>
