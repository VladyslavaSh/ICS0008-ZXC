var searchResults = document.getElementById("searchresults");
var page = 0;
var load_url = "./php/catalog_load.php";
var url;

function updateFilters() {
  url = load_url + "?";
  //office
  //type
  //priceMin
  //priceMax
  //search
  //filterBy
}

function loadPage() {
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.open("GET", url + "page=" + page, false); // false for synchronous request
  xmlHttp.send( null );
  searchResults.insertAdjacentHTML("beforeend",xmlHttp.responseText);
  page += 1;
}

document.getElementById("searchBarSB").addEventListener("click",updateFilters);
updateFilters();

loadPage();
loadPage();
