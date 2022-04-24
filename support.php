<?php
  session_start();

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
  } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <title>Support - ZXC</title>
    <link rel="stylesheet" href="./css/support.css">
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <p>This is the support page</p>
      <div class="form">
        <form class="" action="./support.php" method="post">
          <?php if(empty($_SESSION["email"])) {
            echo '<input type="email" name="email" value="" placeholder="you@example.com">';
          } ?>
          <br>
          <select class="" name="type">
            <option value="">Please select one</option>
            <option value="rent_mistake">I have rented a vehicle by mistake</option>
            <option value="payment">I have some difficulties with payment system</option>
            <option value="refund">I would want to have a refund</option>
            <option value="bug">I have noticed a bug on this website</option>
            <option value="service">I have some troubles with your service</option>
            <option value="other">My problem is not in this list</option>
          </select>
          <br>
          <input type="text" name="header" value="" required>
          <br>
          <textarea name="text" rows="8" cols="80"></textarea>
          <br>
          <input type="submit" name="" value="Submit">
        </form>
      </div>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
