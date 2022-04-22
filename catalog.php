<?php
  session_start();
  require_once("./php/lib/dbconnector.php");

  $dbc = new DBConnection();

  function load_offices(&$dbcon) {
    $cursor = $dbcon->execute("SELECT ID,name,address,post FROM zxc_offices");
    while ($row = $dbcon->fetch_one($cursor)) {
      if (!empty($_GET["office"]) && ($_GET["office"] == $row[0])) {
        echo '<div class="inputLable"><input type="radio" form="filterForm" id="office'.$row[0].'" name="office" value="'.$row[0].'" checked hidden>';
      } else {
        echo '<div class="inputLable"><input type="radio" form="filterForm" id="office'.$row[0].'" name="office" value="'.$row[0].'" hidden>';
      }
      echo '<label for="office'.$row[0].'" class="inputLable">'.$row[1].'<br><span class="inputLableST">'.$row[2].', '.$row[3].' Tallinn</span></label></div>';
    }
    $dbcon->close_cursor($cursor);
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/catalog.css">
    <title>Catalog - ZXC</title>
    <script src="./js/multiRangeSlider.js" type="text/javascript" defer></script>
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <input type="checkbox" id="checkFilter" hidden>
      <div id="filterBar">
        <input type="checkbox" id="checkFilter" hidden>
        <label for="checkFilter" id="hideFilters"></label>
        <div id="filterBarMain">
          <h2>Filters</h2>
          <form id="filterForm" class="" action="catalog.php" method="get">
            <p>Take-out office</p>
            <div id="officeSelection">
              <?php
                load_offices($dbc);
              ?>
            </div>
            <p>Type</p>
            <div class="">
              <div class="inputLable">
                <input type="radio" id="typeAll" name="type" value="all" hidden>
                <label for="typeAll">All</label>
              </div>
              <div class="inputLable">
                <input type="radio" id="typeBike" name="type" value="bike" hidden>
                <label for="typeBike">Bikes</label>
              </div>
              <div class="inputLable">
                <input type="radio" id="typeScooter" name="type" value="scooter" hidden>
                <label for="typeScooter">Scooters</label>
              </div>
            </div>
            <p>Price range</p>
            <div id="priceSlider" class="multiRangeSlider">
              <input type="range" class="inputLeft" min="0" max="100" value="0">
              <input type="range" class="inputRight" min="0" max="100" value="100">
              <div class="slider">
                <div class="track"></div>
                <div class="range"></div>
                <div class="thumbLeft"></div>
                <div class="thumbRight"></div>
              </div>
              <div class="sliderLabel">
                <span class="sliderSpan">
                  <span class="inputSpan"><input type="number" name="priceMin" value="">€</span> -
                  <span class="inputSpan"><input type="number" name="priceMax" value="">€</span>
                </span>
              </div>
            </div>
            <div class="">
              <input type="button" id="resetButton" name="reset" value="Reset">
              <input type="submit" id="submitButton" value="Apply filters">
            </div>
          </form>
        </div>
      </div>
      <div id="content">
        <div id="searchBar">
          <input type="search" form="filterForm" id="searchBarS" name="search" value="" placeholder="Your search request goes here" size="50">
          <select class="" form="filterForm" name="filterBy">
            <option value="all" selected>popularity</option>
            <option value="bikes">price (from lowest)</option>
            <option value="scooters">price (from highest)</option>
          </select>
          <input type="button" name="searchbutton" value="Search">
        </div>
        <div id="searchresults">
          <?php include "./php/tpl/search_result.php"?>
          <?php include "./php/tpl/search_result.php"?>
          <?php include "./php/tpl/search_result.php"?>
          <?php include "./php/tpl/search_result.php"?>
            <!-- There is a huge mess, so this table will be managed by js script, which will download the results as we go deeper -->
        </div>
      </div>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>

<?php
  unset($dbc);
?>
