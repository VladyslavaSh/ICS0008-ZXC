<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/account.css">
    <title>Profile - ZXC</title>
</head>
<body>
<?php include "./php/tpl/navbar.php"; ?>

<?php

session_start();

$_SESSION['email'] = 'zxclord@gmail.com';

//require_once "db_connect.php";

$server = "anysql.itcollege.ee";
$user = "ICS0008_WT_2";
$pass = "6ec9e61f3528";
$db = "ICS0008_2";

$db_con = new mysqli($server, $user, $pass, $db);
$ses_email = $_SESSION['email'];

//Profile configuration
$query = "SELECT * FROM zxc_account WHERE email=?";
$stmt = $db_con -> prepare($query);
$stmt -> bind_param("s", $ses_email);
$stmt -> execute();
$result = $stmt -> get_result();
$result = $result -> fetch_assoc();

//var_dump($result);

$userID = $result['ID'];
$nameFirst = $result['first_name'];
$nameLast = $result['last_name'];
$email = $result['email'];
$age = $result['age'];
$phone = $result['phone'];
$pronouns = $result['pronouns'];


//Orders Configuration
$query = "SELECT zxc_previous_orders.ID, zxc_previous_orders.status, zxc_previous_orders.amount,zxc_previous_orders.message,zxc_previous_orders.rented_when, zxc_previous_orders.rented_until, zxc_model.name, zxc_offices.name FROM zxc_previous_orders LEFT JOIN zxc_model ON zxc_previous_orders.model_id=zxc_model.ID LEFT JOIN zxc_offices ON zxc_previous_orders.office_id=zxc_offices.ID WHERE account_id=?";
$orders_stmt = $db_con -> prepare($query);
$orders_stmt -> bind_param("i", $userID);
$orders_stmt -> execute();
$result_orders = $orders_stmt -> get_result();
$result_orders = $result_orders -> fetch_all();

//var_dump($result_orders);


//Support Configuration
$query = "SELECT * FROM zxc_support_tickets WHERE email=?";
$support_stmt = $db_con -> prepare($query);
$support_stmt -> bind_param("i", $ses_email);
$support_stmt -> execute();
$result = $support_stmt -> get_result();
$result = $result -> fetch_all();

//var_dump($result);

?>

<?php if(isset($_SESSION['email'])):?>


<div class="wrapper">
    <div id='data'>
        <h2>Your personal data</h2>
        <ul>
            <p>First name: <?=$nameFirst?></p>
            <p>Last name: <?=$nameLast?></p>
            <p>Email: <?=$email?></p>
            <p>Age: <?=$age?></p>
            <p>Phone: <?=$phone?></p>
            <p>Pronouns: <?=$pronouns?></p>
        </ul>
        <a href="account_edit.php" class="editbtn">Edit account</a>
    </div>
    <div class="orders">
        <h2>Your orders</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Model name</th>
                <th>Office name</th>
                <th>Rent start date</th>
                <th>Rent end date</th>
            </tr>
            <?php foreach($result_orders as $row):?>
                <tr>
                    <td><?=$row[0]?></td>
                    <td><?=$row[1]?></td>
                    <td><?=$row[2]?></td>
                    <td><?=$row[6]?></td>
                    <td><?=$row[7]?></td>
                    <td><?=$row[4]?></td>
                    <td><?=$row[5]?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="support">
        <h2>Your support requests</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Type</th>
                <th>Header</th>
                <th>Date of creation</th>
            </tr>
            <?php foreach($result as $row):?>
            <tr>
                <td><?=$row[0]?></td>
                <td><?=$row[3]?></td>
                <td><?=$row[4]?></td>
                <td><?=$row[5]?></td>
                <td><?=$row[7]?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php endif;?>


</body>
</html>

<?php include "./php/tpl/footer.php"; ?>