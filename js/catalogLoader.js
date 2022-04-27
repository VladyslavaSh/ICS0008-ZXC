var searchResults = document.getElementById("searchresults");
var page = 0;
var load_url = "./php/catalog_load.php";
var url;
var is_ready = false;

function updateFilters() {
  is_ready = false;
  url = load_url + "?";
  for (office of document.getElementsByClassName("officeButton")) {
    if (office.checked) {
      url += "office=" + encodeURIComponent(office.value) + "&";
    }
  }
  for (tType of document.getElementsByClassName("typeButton")) {
    if (tType.checked) {
      url += "type=" + encodeURIComponent(tType.value) + "&";
    }
  }
  let priceMin = document.getElementsByClassName("priceMin")[0],
  priceMax = document.getElementsByClassName("priceMax")[0],
  searchS = document.getElementById("searchBarS"),
  filterBy = document.getElementById("searchBarBy");
  if (!isNaN(priceMin.value)) {
    url += "priceMin=" + encodeURIComponent(priceMin.value) + "&";
  }
  if (!isNaN(priceMax.value)) {
    url += "priceMax=" + encodeURIComponent(priceMax.value) + "&";
  }
  if (searchS.value != "") {
    url += "search=" + encodeURIComponent(searchS.value) + "&";
  }
  url += "filterBy=" + encodeURIComponent(filterBy.options[filterBy.selectedIndex].value) + "&";
  page = 0;
  searchResults.innerHTML = "";
  loadPage();
}

function resetFilters() {
  let search = document.getElementById("searchBarS");
  if (search.value == "") {
    location.replace("./catalog.php");
  } else {
    location.replace("./catalog.php?search=" + encodeURIComponent(search.value));
  }
}

function loadPage() {
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.open("GET", url + "page=" + page, false); // false for synchronous request
  console.log(url + "page=" + page);
  xmlHttp.send( null );
  searchResults.insertAdjacentHTML("beforeend",xmlHttp.responseText);
  page += 1;
  if (document.getElementsByClassName("searchResultEnd").length == 0) {
    is_ready = true;
  }
}

function loader(event) {
  if (is_ready) {
    if ((window.scrollY + window.innerHeight) >= (document.body.offsetHeight - 100)) {
      is_ready = false;
      loadPage();
    }
  }
}

document.getElementById("searchBarSB").addEventListener("click",updateFilters);
document.getElementById("resetButton").addEventListener("click",resetFilters);
window.addEventListener("scroll",loader);
updateFilters();
