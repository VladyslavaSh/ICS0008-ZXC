<div class="searchResult">
  <div class="searchResultName">
    <h2>{MODEL_NAME}</h2>
  </div>
  <div class="searchResultImage">
    <img src="./img/logo.png" alt="">
  </div>
  <div class="searchResultText">
    <p class="searchResultType">Type: {TYPE}</p>
    <p class="searchResultDesc">{SHORT_DESCRIPTION}</p>
    <h3>{PRICE_HR}€ per hour</h3>
    <div class="searchResultButton">
      <form class="" action="./rent.php" method="get">
        <input type="submit" name="" value="Rent now!" disabled="disabled">
        <input type="text" name="id" value="3" hidden>
        <input type="office" name="office" value="" hidden>
      </form>
      <p class="smallPortion">There is {num} left!</p>
    </div>
  </div>
</div>
