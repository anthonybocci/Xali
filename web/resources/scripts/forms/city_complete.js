var input = document.getElementById("maps");
var autocomplete = new google.maps.places.Autocomplete(input,{types:['(cities)']});
google.maps.event.addListener(autocomplete,'place_changed',function(){
    var place = autocomplete.getPlace();
    console.log(place);
    $("#maps").val(place.address_components[1]['short_name']);
    return;
});
