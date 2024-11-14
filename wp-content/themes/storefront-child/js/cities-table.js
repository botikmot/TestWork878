jQuery(document).ready(function($) {


    // Display Temperature
    $('#cities-table-body tr').each(function() {
        var row = $(this);
        var cityId = row.data('city-id');
        console.log('City ID:', cityId);
        $.ajax({
            url: citiesTableParams.ajax_url, ///wp-admin/admin-ajax.php
            method: 'POST',
            data: {
                action: 'fetch_city_temperature',
                city_id: cityId
            },
            success: function(response) {
                if (response.success) {
                    row.find('.temperature').text(response.data.temperature);
                } else {
                    row.find('.temperature').text('N/A');
                }
            },
            error: function() {
                row.find('.temperature').text('Error');
            }
        });

    });


    //Search City
    $('#city-search').on('keydown', function(event) {
        if (event.keyCode === 13) { // Enter key
            var searchQuery = $(this).val();
            
            console.log("Search Query: " + searchQuery);
            console.log("AJAX URL: " + citiesTableParams.ajax_url);
            console.log("Nonce: " + citiesTableParams.nonce);

            // AJAX request to get filtered cities
            $.ajax({
                url: citiesTableParams.ajax_url, // Use localized AJAX URL
                type: "POST",
                data: {
                    action: "filter_cities", // The action to call the PHP function
                    query: searchQuery,
                },
                success: function(response) {
                    console.log("Response from server: " + response.data);
                    $('#cities-table-body').html(response.data); // Insert the filtered cities
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error); // Log AJAX error for debugging
                    alert("An error occurred while fetching the data.");
                }
            });
        }
    });
});
