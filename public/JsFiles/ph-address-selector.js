var my_handlers = {
    // fill province
    fill_provinces: function() {
        var region_code = $(this).val();
        var region_text = $(this).find("option:selected").text();
        $('#region-text').val(region_text);

        $('#province-text').val('');
        $('#city-text').val('');
        $('#barangay-text').val('');

        let dropdown = $('#province');
        dropdown.empty().append('<option selected disabled>Choose State/Province</option>');

        let city = $('#city');
        city.empty().append('<option selected disabled></option>');

        let barangay = $('#barangay');
        barangay.empty().append('<option selected disabled></option>');

        $.getJSON('/json/province.json', function(data) {
            var result = data.filter(v => v.region_code == region_code);
            result.sort((a, b) => a.province_name.localeCompare(b.province_name));
            $.each(result, function(_, entry) {
                dropdown.append(`<option value="${entry.province_code}">${entry.province_name}</option>`);
            });
        });
    },

    // fill city
    fill_cities: function() {
        var province_code = $(this).val();
        var province_text = $(this).find("option:selected").text();
        $('#province-text').val(province_text);

        $('#city-text').val('');
        $('#barangay-text').val('');

        let dropdown = $('#city');
        dropdown.empty().append('<option selected disabled>Choose city/municipality</option>');

        let barangay = $('#barangay');
        barangay.empty().append('<option selected disabled></option>');

        $.getJSON('/json/city.json', function(data) {
            var result = data.filter(v => v.province_code == province_code);
            result.sort((a, b) => a.city_name.localeCompare(b.city_name));
            $.each(result, function(_, entry) {
                dropdown.append(`<option value="${entry.city_code}">${entry.city_name}</option>`);
            });
        });
    },

    
    fill_barangays: function() {
        var city_code = $(this).val();
        var city_text = $(this).find("option:selected").text();
        $('#city-text').val(city_text);

        $('#barangay-text').val('');

        let dropdown = $('#barangay');
        dropdown.empty().append('<option selected disabled>Choose barangay</option>');

        $.getJSON('/json/barangay.json', function(data) {
            var result = data.filter(v => v.city_code == city_code);
            result.sort((a, b) => a.brgy_name.localeCompare(b.brgy_name));
            $.each(result, function(_, entry) {
                dropdown.append(`<option value="${entry.brgy_code}">${entry.brgy_name}</option>`);
            });
        });
    },

    onchange_barangay: function() {
        var barangay_text = $(this).find("option:selected").text();
        $('#barangay-text').val(barangay_text);
    }
};

$(function() {
    $('#region').on('change', my_handlers.fill_provinces);
    $('#province').on('change', my_handlers.fill_cities);
    $('#city').on('change', my_handlers.fill_barangays);
    $('#barangay').on('change', my_handlers.onchange_barangay);

   
    let dropdown = $('#region');
    dropdown.empty().append('<option selected disabled>Choose Region</option>');
    $.getJSON('/json/region.json', function(data) {
        $.each(data, function(_, entry) {
            dropdown.append(`<option value="${entry.region_code}">${entry.region_name}</option>`);
        });
    });
});
