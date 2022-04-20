<?php 
  session_start();

  require_once "db_connect.php";
  $link = mysqli_connect($server, $user, $pass, $db);
  if (!$link) {
    die("Connection failed: ".mysqli_connect_error());
  }

  $offices = [];
  $query = "SELECT coordinateX, coordinateY FROM zxc_offices";
  $stmt = mysqli_prepare($link, $query);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  if(mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_bind_result($stmt, $lat, $lng);
    while(mysqli_stmt_fetch($stmt)) {
      $result = "{lat:$lat, lng:$lng},";
      array_push($offices, $result);
    }
  }
  //print_r($offices);
  mysqli_stmt_close($stmt);
  mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/index.css">
    <script src="./js/carousel.js" type="text/javascript"></script>
    <title>Home</title>
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <div class="carousel">
        <div class="slide slide_1">
          <div class="text">
            <h1>Placeholder h1 text slide 1</h1>
            <p>Placeholder p text slide 1</p>
          </div>
        </div>

        <div class="slide slide_2">
          <div class="text">
            <h1>Placeholder h1 text slide 2</h1>
            <p>Placeholder p text slide 2</p>
          </div>
        </div>

        <div class="slide slide_3">
          <div class="text">
            <h1>Placeholder h1 text slide 3</h1>
            <p>Placeholder p text slide 3</p>
          </div>
        </div>

        <div class="pointer pointer_left">
          <img src="./img/index/carousel/pointer_left.png" alt="left_pointer">
        </div>

        <div class="pointer pointer_right">
          <img src="./img/index/carousel/pointer_right.png" alt="right_pointer">
        </div>

      </div>

      <div class="benefits">
        <h2 id="absolute_h2"> We are working hard for your comfort!<p id="absolute_p">We are the EU No.1 Scooter Brand, <br> stocking a large range of scooters,<br> accesed by a user friendly rent system. <br> We are the right choice, your choice!</p></h2>
        <img class="fluid" src="./img/index/scooters.png" alt="people_on_scooters">
      </div>

      <div id="map"></div>
      <script src="./js/map.js" type="text/javascript">
        <?php
        echo 'let mk = $offices;';
        ?>
      </script>
      <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOfpvJuLB7hrJM06k0xW6oXez6bwChQhE&callback=initMap"></script>

    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
