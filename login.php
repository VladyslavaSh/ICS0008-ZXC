<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <title>Login - ZXC</title>
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <p>This is the login/register page</p>
      <div class="loginForm">
        <form action="login.php" method="post">
          <input type="submit" name="auth" value="Login">
        </form>
      </div>
      <div class="registerForm">
        <form action="login.php" method="post">
          <input type="submit" name="auth" value="Register">
        </form>
      </div>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
