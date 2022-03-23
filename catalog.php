<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/catalog.css">
    <title>Catalog - ZXC</title>
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <div id="filterBar">
        <h2>filters</h2>
        <form id="filterForm" class="" action="catalog.php" method="get">
          <p>type</p>
          <select class="" name="o">
            <option value="all" selected>all</option>
            <option value="bikes">bikes</option>
            <option value="scooters">scooters</option>
          </select>
          <p>price</p>
          <input type="range" name="" value="" min="0" max="10"></br>
          <p>test range 1</p>
          <input type="range" name="" value="" min="0" max="10"></br>
          <p>test range 2</p>
          <input type="range" name="" value="" min="0" max="10"></br>
          <p>time input 1</p>
          <input type="time" name="" value="">
          <input type="submit" name="submitFilters" value="Apply filters">
        </form>
        <input type="button" name="subtract" value="<">
      </div>
      <div id="content">
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
          <table>
            <tr>
              <td><?php include "./php/tpl/search_result.php"?></td>
              <td><?php include "./php/tpl/search_result.php"?></td>
            </tr>
            <tr>
              <td><?php include "./php/tpl/search_result.php"?></td>
              <td><?php include "./php/tpl/search_result.php"?></td>
            </tr>
            <!-- There is a huge mess, so this table will be managed by js script, which will download the results as we go deeper -->
          </table>
        </div>
      </div>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
