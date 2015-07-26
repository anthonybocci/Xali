$(function() {
        /*
         * On submit, get the city and the country to put them in hidden inputs
         */
        $("#form_add_camp").submit(function() {
            var city = $('#xali_bundle_campbundle_camp_city');
            var country = $('#xali_bundle_campbundle_camp_country');
            var mapsVal = $('#maps').val();
            var cityAndCountry = mapsVal.split(',');
            var nbComma = cityAndCountry.length;
            //If there is only city or country
            if (mapsVal.indexOf(",") === -1) {
                return false;
            }
            city.val(cityAndCountry[0].trim());
            country.val(cityAndCountry[nbComma-1].trim());
            return true;
        });
})



