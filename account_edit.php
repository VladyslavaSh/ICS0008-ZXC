<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/edit_account.css">
    <title>Account Edit - ZXC</title>
</head>
<body>
<?php include "./php/tpl/navbar.php"; ?>

<?php

$patterns = [
    'name' => "\b^[a-zA-Z-'^\s]*$",
    'email' => '^[\w]+@[\w]+\.[\w]+$',
    'phone' => "^$|^[[(]?[0-9]{2}[)]?[-\s\.]?[0-9]{1}[-\s\.]?[0-9]{1,9}]*$"
];

//$_SESSION['email'] = 'zxclord@gmail.com';

$server = "anysql.itcollege.ee";
$user = "ICS0008_WT_2";
$pass = "6ec9e61f3528";
$db = "ICS0008_2";

$db_con = new mysqli($server, $user, $pass, $db);
$ses_email = $_SESSION['email'];

$error = false;

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    [
        'nameFirst' => $nameFirst,
        'nameLast' => $nameLast,
        'age' => $age,
        'email' => $email,
        'tel' => $phone,
        'pron' => $pronounce,
    ] = array_map('trim', $_POST);


    if (preg_match("/{$patterns["name"]}/", $nameFirst)
        && preg_match("/{$patterns["name"]}/", $nameLast)
        && filter_var($email, FILTER_VALIDATE_EMAIL)
        && $age >= 16
        && preg_match("/{$patterns["phone"]}/", $phone)
        && in_array($pronounce, ['He', 'She','They', 'he', 'she','they'])) {

        $query = "UPDATE zxc_account SET first_name=?, last_name=?, email=?, age=?, phone=?, pronouns=? WHERE email=?";
        $stmt2 = $db_con -> prepare($query);
        $stmt2 -> bind_param("sssisss", $_REQUEST['nameFirst'], $_REQUEST['nameLast'], $_REQUEST['email'], $_REQUEST['age'], $_REQUEST['tel'], $_REQUEST['pron'], $ses_email);
        $stmt2 -> execute();
    }
    else $error = true;

}

$query = "SELECT * FROM zxc_account WHERE email=?";
$stmt = $db_con->prepare($query);
$stmt->bind_param("s", $ses_email);
$stmt->execute();
$result = $stmt->get_result();
$result = $result->fetch_assoc();


?>



<?php if(isset($_SESSION['email'])):?>


<h2>Edit your account</h2>

    <?php if($error == true):?>
        <h3>Input error. Please enter the data in the correct format.</h3>
    <?php endif;?>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <label>First Name</label>
    <input type="text" name="nameFirst" value="<?=$result['first_name']?>" minlength="2" maxlength="30" pattern="<?= $patterns["name"] ?>" >
    <label>Last Name</label>
    <input type="text" name="nameLast" value="<?=$result['last_name']?>" minlength="2" maxlength="30" pattern="<?= $patterns["name"] ?>">
    <label>Email</label>
    <input type="email" name="email" value="<?=$result['email']?>" pattern="<?= $patterns['email'] ?>">
    <label>Age</label>
    <input type="number" name="age" value="<?=$result['age']?>" min="16" max="100">
    <label>Phone number</label>
    <input type="tel" name="tel" value="<?=$result['phone']?>" pattern="<?= $patterns['phone'] ?>">
    <label>Pronouns</label>
    <input type="text" name="pron" value="<?=$result['pronouns']?>">

    <a href="password_edit.php" class="editpass">Edit password</a>

    <input type="submit" value="Save Changes">

</form>
<?php endif;?>


</body>
</html>

<?php include "./php/tpl/footer.php"; ?>