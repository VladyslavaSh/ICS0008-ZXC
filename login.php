<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <?php include "./php/tpl/head.php"; ?>
  <link rel="stylesheet" href="./css/login.css">
  <script src="./js/login.js" type="text/javascript"></script>
  <title>Login - ZXC</title>
</head>

<body>
  <?php include "./php/tpl/navbar.php"; ?>
  <div class="main">
    <div class="content">
      <div class="regLogBtn">
        <button class="button active"><a href="#loginForm">Log In</a></button>
        <button class="button"><a href="#registerForm">Register</a></button>
      </div>
      <div id="forms">
        <div id="loginForm">
          <form action="login.php" method="post">
            <h1>Hello again!</h1>
            <label for="logEmail">Email</label>
            <input type="text" id="logEmail" name="email" value="" required>
            <label for="logPassword">Password</label>
            <input type="password" id="logPassword" name="password" value="" required>
            <input type="submit" name="auth" class="submitBtn" value="Log In">
          </form>
        </div>

        <div id="registerForm">
          <form action="login.php" method="post">
            <h1>Sign up</h1>
            <label for="regNameFirst">First name</label>
            <input type="text" id="regNameFirst" name="nameFirst" value="">
            <label for="regNameLast">Last name</label>
            <input type="text" id="regNameLast" name="nameLast" value="">
            <label for="regEmail">Email: </label>
            <input type="text" id="regEmail" name="email" value="" required>
            <label for="regPassword">Password</label>
            <input type="password" id="regPassword" name="password" value="" required>
            <label for="regPasswordRepeat">Confirm password</label>
            <input type="password" id="regPasswordRepeat" name="passwordRepeat" value="" required>
            <input type="submit" name="auth" class="submitBtn" value="Register">
          </form>
        </div>
      </div>
    </div>


  </div>
  <?php include "./php/tpl/footer.php"; ?>
</body>

</html>


<?php



?>