function initialize() {
   var options = {
  types: ['(cities)'],
  componentRestrictions: {country: "us"}
 };
      var input = document.getElementById('maps');
      var autocomplete = new google.maps.places.Autocomplete(input);
   }
   google.maps.event.addDomListener(window, 'load', initialize);