<?php
  session_start();
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
      <div id="filterBar">
        <input type="checkbox" id="checkFilter" hidden>
        <label for="checkFilter" id="hideFilters"><<</label>
        <h2>Filters</h2>
        <form id="filterForm" class="" action="catalog.php" method="get">
          <div id="officeSelection">
            <label for="office1">{Office 1}</label>
            <input type="radio" id="office1" form="filterForm" name="office" value="1">
            <label for="office2">{Office 2}</label>
            <input type="radio" id="office2" form="filterForm" name="office" value="2">
            <label for="office3">{Office 3}</label>
            <input type="radio" id="office3" form="filterForm" name="office" value="3">
            <label for="office4">{Office 4}</label>
            <input type="radio" id="office4" form="filterForm" name="office" value="4">
            <label for="office5">{Office 5}</label>
            <input type="radio" id="office5" form="filterForm" name="office" value="5">
          </div>
          <p>Type</p>
          <select class="" name="o">
            <option value="all" selected>all</option>
            <option value="bikes">bikes</option>
            <option value="scooters">scooters</option>
          </select>
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
          <input type="button" name="reset" value="reset">
          <input type="submit" value="Apply filters">
        </form>
      </div>
      <div id="content">
        <div id="searchbar">
          <input type="search" form="filterForm" name="b" value="" placeholder="Your search request goes here" size="50">
          <select class="" form="filterForm" name="o">
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
