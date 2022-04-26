<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/edit_account.css">
    <title>Account Edit - ZXC</title>
</head>
<body>


<?php

$_SESSION['email'] = 'zxclord@gmail.com';

$server = "anysql.itcollege.ee";
$user = "ICS0008_WT_2";
$pass = "6ec9e61f3528";
$db = "ICS0008_2";

$db_con = new mysqli($server, $user, $pass, $db);
$ses_email = $_SESSION['email'];

$error = false;

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $query = "SELECT pass FROM zxc_account WHERE email=?";
    $stmt = $db_con->prepare($query);
    $stmt->bind_param("s", $ses_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc();

    if (password_verify($_REQUEST['cur_pass'], $result['pass'])) {

        if($_REQUEST['new_pass'] == $_REQUEST['conf_pass']) {

            $query = "UPDATE zxc_account SET pass=? WHERE email=?";
            $stmt2 = $db_con->prepare($query);
            $pwd = password_hash($_REQUEST['new_pass'], PASSWORD_DEFAULT);
            $stmt2->bind_param("ss", $pwd, $ses_email);
            $stmt2->execute();
        }
        else $error = true;
    }
}

?>

<?php include "./php/tpl/navbar.php"; ?>

<h2>Edit your password</h2>

<?php if($error == true):?>
    <h3>Input error. Please enter the data in the correct format.</h3>
<?php endif;?>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <label>Current password</label>
    <input type="password" name="cur_pass" >
    <label>New password</label>
    <input type="password" name="new_pass" >
    <label>Confirm password</label>
    <input type="password" name="conf_pass">

    <input type="submit" value="Save Changes">

</form>

<?php include "./php/tpl/footer.php"; ?>
</body>
</html>