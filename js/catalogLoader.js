var searchResults = document.getElementById("searchresults");
var page = 0;
var load_url = "./php/catalog_load.php";
var url;
var is_ready = false;

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
  if (document.getElementsByClassName("searchResultEnd").length == 0) {
    is_ready = true;
  }
}

function loader(event) {
  if (is_ready) {
    if ((window.scrollY + window.innerHeight) >= (document.body.offsetHeight - 50)) {
      is_ready = false;
      loadPage();
    }
  }
  console.log(document.body.offsetHeight);
  console.log(window.scrollY + window.innerHeight);
}

document.getElementById("searchBarSB").addEventListener("click",updateFilters);
window.addEventListener("scroll",loader);
updateFilters();
loadPage();
