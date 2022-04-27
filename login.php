<?php 
  session_start();
?>

<?php

  if (isset($_SESSION["email"])) {
    header("Location: index.php");
    die();
  } 

  $formLoad = 0;

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    function main(){

      require_once "db_connect.php";
      $link = mysqli_connect($server, $user, $pass, $db);
      if (!$link) {
        die("Connection failed: ".mysqli_connect_error());
      }

      $fields = ["email", "password", "passwordRepeat", "nameFirst", "nameLast"];
      $required = ["email", "password", "passwordRepeat"];
      $submitTypes = ["Log In", "Register"];

      if(!isset($_POST["auth"]) || empty($_POST["auth"])) return 1;

      $submit = $_POST["auth"];

      function submit_value_check(&$submit, &$submitTypes){
        if(!in_array($submit, $submitTypes)) return 1;
      }

      if(submit_value_check($submit, $submitTypes)) return 2;

      // DELETING UNNECESSARY FIELDS FOR THE FORM TYPE "Log In"
      if($submit == "Log In") {
        $fields = \array_diff($fields, ["passwordRepeat", "nameFirst", "nameLast"]);
        $required = \array_diff($required, ["passwordRepeat"]);
      }

      function isset_check(&$fields){
        foreach($fields as $field) {
          if(!isset($_POST[$field])) {
            return 1;
          }
        }
      }

      if(isset_check($fields)) return 3;

      function empty_check(&$required){
        foreach($required as $field) {
          if(empty(trim($_POST[$field]))) {
              return 1;
          }
        }
      }

      if(empty_check($required)) return 4;

      function html_inj(&$fields) {
        foreach($fields as $field) {
          $clear = strip_tags($_POST[$field]);
          if($clear != $_POST[$field]) {
            return 1;
          }
        }
      }

      if(html_inj($fields)) return 11;

      function email_check(&$email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 1;
        }
        if(strlen($email) > 50)
        {
            return 1;
        }
      }

      if(email_check($_POST["email"])) return 5;

      function validate_names($list){
        foreach($list as $str){
          if (preg_match('/[0-9\@\:\.\;\|\!\#\}\$\{\=\\&\*\(\)\+\_\%\" "]+/', $str) || (strlen($str) > 20))
          {
            return 1;
          }
        }
      }

      function pass_req(&$pass) {
        if (strlen($pass) < 8 || strlen($pass) > 255) return 1;
      }

      function pass_rep(&$pass, &$passRep) {
        if($pass != $passRep) {
          return 1;
        }
      }

      function registration($link){

        $email = trim($_POST["email"]);
        $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $first_name = trim($_POST["nameFirst"]);
        $last_name = trim($_POST["nameLast"]);

        // duplicate check
        $query = "SELECT email FROM zxc_account WHERE email=?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) == 1) {
          return 1;
        }
        mysqli_stmt_close($stmt);

        // actual registration
        $query = "INSERT INTO zxc_account (email, pass, first_name, last_name) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $email, $pass, $first_name, $last_name);
        mysqli_stmt_execute($stmt);
        $_SESSION["email"] = $email; // giving session ID as entered email as its unique
        mysqli_stmt_close($stmt);
      }

      if($submit == "Register") {
        if(validate_names([$_POST["nameFirst"], $_POST["nameLast"]])) return 6;
        if(pass_req($_POST["password"])) return 7;
        if(pass_rep($_POST["password"], $_POST["passwordRepeat"])) return 8;
        if(registration($link)) return 9;
        if(isset($_SESSION["redirect"])) {
          header ("Location: ".$_SESSION["redirect"]);
          die();
        }
        header("Location: index.php");
        die();
      }

      function authorization($link) {
        $query = "SELECT pass FROM zxc_account WHERE email=?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);

        $email = trim($_POST["email"]);
        $pass = $_POST["password"];

        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt); // mysqli_stmt_num_rows function requires mysqli_stmt_store_result to work

        if(mysqli_stmt_num_rows($stmt) == 1) {
          mysqli_stmt_bind_result($stmt, $hashedPass); // "pass" from sql data assigned to php $hashedPass
          mysqli_stmt_fetch($stmt); // variables from mysqli_stmt_bind_result cannot be accessed until this function is used
          if(password_verify($pass, $hashedPass)) {
            $_SESSION["email"] = $email; // giving session ID as entered email as its unique
          }
          else {
            return 1;
          }
        }
        else {
          return 1;
        }
        mysqli_stmt_close($stmt);
      }

      if($submit == "Log In") {
        if(authorization($link)) return 10;
        if(isset($_SESSION["redirect"])) {
          header ("Location: ".$_SESSION["redirect"]);
          die();
        }
        header("Location: index.php");
        die();
      }

      mysqli_close($link);
      return 0;
    }

    $result = main();

    if ($result==6||$result==7||$result==8||$result==9) {
      $formLoad = 1;
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/login.css">
    <script src="./js/login.js" type="text/javascript"></script>
    <title>Login - ZXC</title>
    <style>
      <?php if($formLoad==0) {
        echo "#Register";
      } else {
        echo "#Log-In";
      }?> {
        display:none;
      }
    </style>
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <div class="content">
        <div class="regLogBtn">
          <button type="button" for="Log-In" <?php if ($formLoad==0) echo 'class="active"'; ?> >Log In</button>
          <button type="button" for="Register" <?php if ($formLoad==1) echo 'class="active"';?>>Register</button>
        </div>
          <?php
            if($_SERVER["REQUEST_METHOD"] == "POST"){
              if ($result != 0) echo '<div id="formResult">';
              if ($result == 1){
                echo "Submit button name was changed";
              }
              elseif ($result == 2){
                echo "Value of the submit button was changed";
              }
              elseif ($result == 3){
                echo "Some of the fields were deleted";
              }
              elseif ($result == 4){
                echo "Some of the required fields were empty";
              }
              elseif ($result == 5){
                echo "Incorrect email type or more than 50 symbols";
              }
              elseif ($result == 6){
                echo "Name can contain only - and ' from special characters and at most 20 characters long";
              }
              elseif ($result == 7){
                echo "Password should be between 8 and 255 characters long";
              }
              elseif ($result == 8){
                echo "Passwords doesn't match";
              }
              elseif ($result == 9){
                echo "Email is already taken";
              }
              elseif ($result == 10){
                echo "Email or password is incorrect";
              }
              elseif ($result == 11){
                echo "No html tags allowed (even in password)";
              }
              if ($result != 0) echo "</div>";
            }
          ?>
        <div id="forms">
          <div id="Log-In">
            <form action="login.php" method="post">
              <h1>Hello again!</h1>
              <label for="logEmail">Email</label>
              <input type="email" id="logEmail" name="email" value="" maxlength="50" required>
              <label for="logPassword">Password</label>
              <input type="password" id="logPassword" name="password" value="" maxlength="50" required>
              <input type="submit" name="auth" class="submitBtn" value="Log In">
            </form>
          </div>

          <div id="Register">
            <form action="login.php" method="post">
              <h1>Sign up</h1>
              <label for="regNameFirst">First name</label>
              <input type="text" id="regNameFirst" name="nameFirst" pattern="^[A-Za-zÀ-ÿа-яА-ЯёЁ,.'-]+$" maxlength="20" value="">
              <label for="regNameLast">Last name</label>
              <input type="text" id="regNameLast" name="nameLast" pattern="^[A-Za-zÀ-ÿа-яА-ЯёЁ,.'-]+$" maxlength="20" value="">
              <label for="regEmail">Email</label>
              <input type="email" id="regEmail" name="email" value="" maxlength="50" required>
              <label for="regPassword">Password</label>
              <input type="password" id="regPassword" name="password" value="" minlength="8" maxlength="50" required>
              <label for="regPasswordRepeat">Confirm password</label>
              <input type="password" id="regPasswordRepeat" name="passwordRepeat" value="" minlength="8" maxlength="50" required>
              <input type="submit" name="auth" class="submitBtn" value="Register">
            </form>
          </div>
        </div>
      </div>


    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>