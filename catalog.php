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
          <form id="filterForm" action="catalog.php" method="get">
            <p>Take-out office</p>
            <div id="officeSelection">
              <?php
                load_offices($dbc);
              ?>
            </div>
            <p>Type</p>
            <div>
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
                  <span class="inputSpan"><input type="number" id="priceMin" name="priceMin">€</span> -
                  <span class="inputSpan"><input type="number" id="priceMax" name="priceMax">€</span>
                </span>
              </div>
            </div>
            <div>
              <input type="button" id="resetButton" value="Reset">
              <input type="submit" id="submitButton" value="Apply filters">
            </div>
          </form>
        </div>
      </div>
      <div id="content">
        <div id="searchBar">
          <div id="searchBarInput">
            <input type="search" form="filterForm" id="searchBarS" name="search" placeholder="search">
            <label for="searchBarBy">sort by:</label>
            <select form="filterForm" id="searchBarBy" name="filterBy">
              <option value="popularity" selected>popularity</option>
              <option value="price ASC">price (from lowest)</option>
              <option value="price DESC">price (from highest)</option>
            </select>
          </div>
          <input type="button" id="searchBarSB" value=">">
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
