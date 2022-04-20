function initMap(){
    let map_options = {
        zoom:13,
        center:{lat:59.416687, lng:24.741994}
    }
    let map = new google.maps.Map(document.getElementById("map"), map_options);

    let markers = mk;

    function createMarker(markers) {
        for(let i = 0; i < markers.length; i++) {
            let marker = new google.maps.Marker({
                position:markers[i],
                map:map,
                icon:"./img/index/map_icon.png",
            });
        }
    }
    createMarker(markers);
}