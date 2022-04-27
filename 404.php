<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/404.css">
    <title>404 - ZXC</title>
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
        <div class="content">
            <h1>Page not found</h1>
            <p class="text">404</p>
            <p>Requested page could not be found</p>
            <a href="./index.php">Home</a>
        </div>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
