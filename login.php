<?php
  $result = 0;
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    function main(){
      $fields = ["email", "password", "passwordRepeat", "nameFirst", "nameLast"];
      $required = ["email", "password", "passwordRepeat"];
      $submitTypes = ["Log In", "Register"];

      if(!isset($_POST["auth"]) || empty($_POST["auth"])) return 1;

      $submit = $_POST["auth"];

      function submit_value_check(&$submit, &$submitTypes){
        foreach($submitTypes as $type) {
          if ($type == $submit) {
            return 0;
          }
        }
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

      function pass_check(&$pass, &$passRep) {
        if($pass != $passRep) {
          return 1;
        }
      }

      function temp_csv_write() {
        $result_csv = [$_POST["nameFirst"], $_POST["nameLast"], $_POST["email"] , $_POST["password"]];
        $f = fopen("csv_file.csv", "a");
        fputcsv($f, $result_csv, ";");
        fclose($f);
      }

      if($submit == "Register") {
        if(validate_names([$_POST["nameFirst"], $_POST["nameLast"]])) return 6;
        if(pass_check($_POST["password"], $_POST["passwordRepeat"])) return 7;
        temp_csv_write();
        header("Location: index.php");
      }

      function temp_csv_read() {
        $handle = fopen("csv_file.csv", "r");
        while (($data = fgetcsv($handle)) !== FALSE) {
          $data = explode(";", $data[0]);
          if($data[2] == $_POST["email"] && $data[3] == $_POST["password"]) {
            return 1;
          }
        }
      }

      if($submit == "Log In") {
        //authorization($login, $pass);
        if(temp_csv_read()) {
          header("Location: index.php");
        }
        else {
          return 8;
        }
      }

      /*
      function authorization(&$login, &$pass) {
        $request = "select * from loginform where user='".$login."' AND pass='".$pass."' limit 1";
        $reqResult = mysql_query($request);
        if(mysql_num_rows($reqResult) != 1) {
          return 1;
        }
      }
      */
    }
    $result = main();

    $formLoad = 0;
    if ($result==6||$result==7) {
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
        <div id="formResult">
          <?php
            if($_SERVER["REQUEST_METHOD"] == "POST"){
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
                echo "Name can contain only - and ' from special characters and only 20 characters long";
              }
              elseif ($result == 7){
                echo "Passwords doesn't match";
              }
              elseif ($result == 8){
                echo "Email or password is incorrect";
              }
            }
          ?>
        </div>
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
              <input type="text" id="regNameFirst" name="nameFirst" pattern="^[A-Za-zÀ-ÿа-яА-ЯёЁ ,.-']+$" maxlength="20" value="">
              <label for="regNameLast">Last name</label>
              <input type="text" id="regNameLast" name="nameLast" pattern="^[A-Za-zÀ-ÿа-яА-ЯёЁ ,.-']+$" maxlength="20" value="">
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
