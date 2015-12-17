

<?php
    require "db.conf";

    $link = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die("Connect Error: " . mysqli_error($link));

    $desc = array(
        'select * from business',
        'select * from updates',
        'select * from users',
        'select * from users_business',
        'select * from opportunity',
        'select * from team',
        'select * from team_business',
        'select * from market',
        'select * from intellectual_property',
        'select * from competitive_analysis',
        'select * from market_efforts',
        'select * from sales_channels',
        'select * from milestones',
        'select * from financial_projections',
        'select * from partners',
        'select * from partners_business',
        'select * from comparable_exits',
        'select * from rounds_anticipated'
    );
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Select * From Tables</title>
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
        $field_cnt = 0;
        foreach ($fields as $val) {
            echo "<th>$val->name</th>";
            $field_cnt++;
        }
        echo "\n</tr>";
        //print "Field    Type    Null    Key     Default     Extra";

            while($row = mysqli_fetch_array($res)) {
        ?>
        <tr>
            <?php
                for($i=0;$i<$field_cnt;$i++){
                    echo "<td>".$row[$i]."</td>";
                }
            ?>
        </tr>
        <?php
        }

        echo "\n</table><br><br>\n";
        mysqli_free_result($res);
    }

    mysqli_close($link);
?>
    </body>
</html>
