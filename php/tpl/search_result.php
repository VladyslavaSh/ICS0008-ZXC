<div class="searchResult">
  <div class="searchResultName">
    <h2>{MODEL_NAME}</h2>
  </div>
  <div class="searchResultImage">
    <img src="./php/model_image.php?model={MODEL}" alt="Model {MODEL_NAME}">
  </div>
  <div class="searchResultText">
    <p class="searchResultType">Type: {TYPE}</p>
    <p class="searchResultDesc">{SHORT_DESCRIPTION}</p>
    <h3>{PRICE_HR}€ per hour</h3>
    <div class="searchResultButton">
      <form action="./rent.php" method="get">
        <input type="submit" name="" value="Rent now!" {IS_DISABLED}>
        <input type="text" name="model" value="{MODEL}" hidden>
        {OFFICE}
      </form>
      {AVAILABILITY}
    </div>
  </div>
</div>
