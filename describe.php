

<?php
    require "db.conf";

    $link = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die("Connect Error: " . mysqli_error($link));

    $desc = array(
        'describe business',
        'describe updates',
        'describe users',
        'describe users_business',
        'describe opportunity',
        'describe team',
        'describe team_business',
        'describe market',
        'describe intellectual_property',
        'describe competitive_analysis',
        'describe market_efforts',
        'describe sales_channels',
        'describe milestones',
        'describe financial_projections',
        'describe partners',
        'describe partners_business',
        'describe comparable_exits',
        'describe rounds_anticipated'
    );
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Describe Tables</title>
        <style>
            table, td, th {
                border: 1px solid grey;
                border-collapse: collapse;
            }
        </style>
    </head>
    <body>
<?php
    foreach ($desc as $d) {
        echo "$d<br>\n";
        $res = mysqli_query($link,$d);
        $fields = mysqli_fetch_fields($res);
        echo "<table>\n";
        echo "<tr>\n";
        foreach ($fields as $val) {
            echo "<th>$val->name</th>";
        }
        echo "\n</tr>";
        //print "Field    Type    Null    Key     Default     Extra";

        while($row = mysqli_fetch_array($res)) {
            echo "\n<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td><td>{$row['Default']}</td><td>{$row['Extra']}</td></tr>";
        }

        echo "\n</table><br><br>\n";
        mysqli_free_result($res);
    }

    mysqli_close($link);
?>
    </body>
</html>
