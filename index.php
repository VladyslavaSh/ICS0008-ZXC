<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/head.php"; ?>
    <link rel="stylesheet" href="./css/index.css">
    <script src="./js/carousel.js" type="text/javascript"></script>
    <title>Home</title>
  </head>
  <body>
    <?php include "./php/navbar.php"; ?>
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
        <h1> We are working hard for your comfort!</h1>
        <img class="fluid" src="./img/index/people_on_scooters.png" alt="people_on_scooters">
      </div>
    </div>
    <?php include "./php/footer.php"; ?>
  </body>
</html>