<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/head.php"; ?>
    <link rel="stylesheet" href="./css/catalog.css">
    <title>Catalog</title>
  </head>
  <body>
    <?php include "./php/navbar.php"; ?>
    <div id="filterbar">
      <h2>filters</h2>
      <form class="" action="index.php" method="get">
        <p>price</p>
        <input type="range" name="" value="" min="0" max="10"></br>
        <p>test range 1</p>
        <input type="range" name="" value="" min="0" max="10"></br>
        <p>test range 2</p>
        <input type="range" name="" value="" min="0" max="10"></br>
        <p>time input 1</p>
        <input type="time" name="" value="">
      </form>
    </div>
    <div class="main">
      <div id="content">
        <div id="searchbar">
          <form class="" action="index.php" method="get">
            <input type="text" name="" value="" placeholder="Your search request goes here" size="50">
            <input type="button" name="searchbutton" value="Search">
          </form>
        </div>
        <div id="searchresults">
          <p id = "searchresults-outcome">Results: %int% (seconds: %float%)</p>
          <table>
            <tr>
              <td><p>Test 1</p></td>
              <td><p>Test 2</p></td>
              <td><p>Test 3</p></td>
              <td><p>Test 4</p></td>
            </tr>
            <tr>
              <td><p>Test 5</p></td>
              <td><p>Test 6</p></td>
              <td><p>Test 7</p></td>
              <td><p>Test 8</p></td>
            </tr>
            <tr>
              <td><p>Test 9</p></td>
              <td><p>Test 10</p></td>
              <td><p>Test 11</p></td>
              <td><p>Test 12</p></td>
            </tr>
            <tr>
              <td><p>Test 13</p></td>
              <td><p>Test 14</p></td>
              <td><p>Test 15</p></td>
              <td><p>Test 16</p></td>
            </tr>
            <tr>
              <td><p>Test 17</p></td>
              <td><p>Test 18</p></td>
              <td><p>Test 19</p></td>
              <td><p>Test 20</p></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <?php include "./php/footer.php"; ?>
  </body>
</html>
