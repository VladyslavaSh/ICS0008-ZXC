<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/login.css">
    <title>Login - ZXC</title>
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <p>This is the login/register page</p>
      <div class="loginForm">
        <form action="login.php" method="post">
          <label for="logEmail">Email: </label>
          <input type="text" id="logEmail" name="email" value="" required>
          <br/>
          <label for="logPassword">Password: </label>
          <input type="password" id="logPassword" name="password" value="" required>
          <br/>
          <input type="submit" name="auth" value="Login">
        </form>
      </div>
      <div class="registerForm">
        <form action="login.php" method="post">
          <label for="regNameFirst">First name: </label>
          <input type="text" id="regNameFirst" name="nameFirst" value="">
          <br/>
          <label for="regNameLast">Last name: </label>
          <input type="text" id="regNameLast" name="nameLast" value="">
          <br/>
          <label for="regEmail">Email: </label>
          <input type="text" id="regEmail" name="email" value="" required>
          <br/>
          <label for="regPassword">Password: </label>
          <input type="password" id="regPassword" name="password" value="" required>
          <br/>
          <label for="regPasswordRepeat">Confirm password: </label>
          <input type="password" id="regPasswordRepeat" name="passwordRepeat" value="" required>
          <br/>
          <input type="submit" name="auth" value="Register">
        </form>
      </div>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
