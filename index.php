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
    $last = substr(end($offices), 0, -1);
    array_pop($offices);
    array_push($offices, $last);
  }
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
          <img class="fluid" src="./img/index/carousel/products/slide1.jpg" alt="promote_1">
          <div class="text">
            <h1>Jack and Jill</h1>
            <p>They are in love with our products</p>
          </div>
        </div>

        <div class="slide slide_2">
        <img class="fluid" src="./img/index/carousel/products/slide2.jpg" alt="promote_2">
          <div class="text">
            <h1>Mary</h1>
            <p>Enjoying the total comfort of our bicycles</p>
          </div>
        </div>

        <div class="slide slide_3">
        <img class="fluid" src="./img/index/carousel/products/slide3.jpg" alt="promote_3">
          <div class="text">
            <h1>Michael</h1>
            <p>Discovers the world with our scooter</p>
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
      <script>
        function initMap(){
          let map_options = {
              zoom:13,
              center:{lat:59.416687, lng:24.741994}
          }
          let map = new google.maps.Map(document.getElementById("map"), map_options);

          let markers =  [<?php for($i=0;$i<count($offices);$i++) echo $offices[$i];?>];

          function createMarker(markers) {
              for(let i = 0; i < markers.length; i++) {
                  let marker = new google.maps.Marker({
                      position:markers[i],
                      map:map,
                      icon:"./img/index/map_icon.png",
                  });
              }
          }
          createMarker(markers);
      }
      </script>
      <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOfpvJuLB7hrJM06k0xW6oXez6bwChQhE&callback=initMap"></script>

    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
