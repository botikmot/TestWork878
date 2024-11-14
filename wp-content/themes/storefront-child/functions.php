<?php

// Enqueue the parent theme's stylesheet to inherit its styles
function storefront_child_enqueue_styles() {
    wp_enqueue_style('storefront-parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'storefront_child_enqueue_styles');

// add jquery for fetching data
function enqueue_jquery() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery');


// Create a custom post type called “Cities.”
function create_city_post_type() {
    register_post_type('city', [
        'labels' => [
            'name' => __('Cities'),
            'singular_name' => __('City')
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor'],
    ]);
}
add_action('init', 'create_city_post_type');


// create a meta box with custom fields “latitude” and “longitude”

function add_city_meta_boxes() {
    add_meta_box('city_location', __('City Location'), 'render_city_meta_box', 'city', 'side');
}
add_action('add_meta_boxes', 'add_city_meta_boxes');

function render_city_meta_box($post) {
    $latitude = get_post_meta($post->ID, '_city_latitude', true);
    $longitude = get_post_meta($post->ID, '_city_longitude', true);
    echo '<label>Latitude:</label>';
    echo '<input type="text" name="city_latitude" value="' . esc_attr($latitude) . '" />';
    echo '<label>Longitude:</label>';
    echo '<input type="text" name="city_longitude" value="' . esc_attr($longitude) . '" />';
}

// save city meta box data
function save_city_meta_box_data($post_id) {
    if (isset($_POST['city_latitude'])) {
        update_post_meta($post_id, '_city_latitude', sanitize_text_field($_POST['city_latitude']));
    }
    if (isset($_POST['city_longitude'])) {
        update_post_meta($post_id, '_city_longitude', sanitize_text_field($_POST['city_longitude']));
    }
}
add_action('save_post', 'save_city_meta_box_data');


//Create a custom taxonomy titled “Countries” and attach it to “Cities.”
function create_country_taxonomy() {
    register_taxonomy('country', 'city', [
        'label' => __('Countries'),
        'rewrite' => ['slug' => 'country'],
        'hierarchical' => true,
    ]);
}
add_action('init', 'create_country_taxonomy');


// Create a City Temperature Widget
class City_Temperature_Widget extends WP_Widget {
    function __construct() {
        parent::__construct('city_temperature_widget', __('City Temperature Widget'));
    }

    public function widget($args, $instance) {
        $city_id = isset($instance['city']) ? $instance['city'] : null;
        $city_name = isset($instance['city_name']) ? $instance['city_name'] : null;
        $api_key = '815a7194fe1f3773baebf684f9ca3eeb';

        // Check if a city ID is provided
        if ($city_id) {
            $latitude = get_post_meta($city_id, '_city_latitude', true);
            $longitude = get_post_meta($city_id, '_city_longitude', true);

            $api_url = "https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$api_key}&units=metric";
            $response = wp_remote_get($api_url);
            $data = json_decode(wp_remote_retrieve_body($response));

            if (!is_wp_error($data) && isset($data->main->temp)) {
                echo $args['before_widget'] . "City: " . get_the_title($city_id) . "<br>Temperature: {$data->main->temp}°C" . $args['after_widget'];
            } else {
                echo $args['before_widget'] . "City: " . get_the_title($city_id) . "<br>Temperature data not available." . $args['after_widget'];
            }

        // If no city ID, use the city name entered by the user
        } elseif ($city_name) {
            $api_url = "https://api.openweathermap.org/data/2.5/weather?q={$city_name}&appid={$api_key}&units=metric";
            $response = wp_remote_get($api_url);
            $data = json_decode(wp_remote_retrieve_body($response));

            if (!is_wp_error($data) && isset($data->main->temp)) {
                echo $args['before_widget'] . "City: " . esc_html($city_name) . "<br>Temperature: {$data->main->temp}°C" . $args['after_widget'];
            } else {
                echo $args['before_widget'] . "City: " . esc_html($city_name) . "<br>Temperature data not available." . $args['after_widget'];
            }
        } else {
            echo $args['before_widget'] . "No city selected." . $args['after_widget'];
        }
    }

    public function form($instance) {
        $city_id = !empty($instance['city']) ? $instance['city'] : '';
        $city_name = !empty($instance['city_name']) ? $instance['city_name'] : '';
        
        // Fetch cities for dropdown
        $cities = get_posts(array('post_type' => 'city', 'numberposts' => -1));

        // City ID dropdown
        echo '<p>';
        echo '<label for="' . $this->get_field_id('city') . '">Select City:</label>';
        echo '<select id="' . $this->get_field_id('city') . '" name="' . $this->get_field_name('city') . '">';
        echo '<option value="">-- Select a City --</option>';
        
        foreach ($cities as $city) {
            $selected = ($city->ID == $city_id) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr($city->ID) . '" ' . $selected . '>' . esc_html($city->post_title) . '</option>';
        }

        echo '</select>';
        echo '</p>';

        // City name text input
        echo '<p>';
        echo '<label for="' . $this->get_field_id('city_name') . '">Or Enter City Name:</label>';
        echo '<input type="text" id="' . $this->get_field_id('city_name') . '" name="' . $this->get_field_name('city_name') . '" value="' . esc_attr($city_name) . '" />';
        echo '</p>';
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['city'] = (!empty($new_instance['city'])) ? sanitize_text_field($new_instance['city']) : '';
        $instance['city_name'] = (!empty($new_instance['city_name'])) ? sanitize_text_field($new_instance['city_name']) : '';

        return $instance;
    }
}

// register widget
function register_city_temperature_widget() {
    register_widget('City_Temperature_Widget');
}
add_action('widgets_init', 'register_city_temperature_widget');


// include cities-table.js
function enqueue_cities_table_script() {
    wp_enqueue_script('cities-table', get_stylesheet_directory_uri() . '/js/cities-table.js', array('jquery'), null, true);
    // Localize the script to provide AJAX URL and nonce
    wp_localize_script('cities-table', 'citiesTableParams', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('filter_cities_nonce')  // Nonce for security
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_cities_table_script');



function filter_cities() {
    global $wpdb;
    // Sanitize and process the query
    if (isset($_POST['query'])) {
        $query = sanitize_text_field($_POST['query']);
    } else {
        wp_send_json_error('No city provided');
    }

    // Log query for debugging
    error_log("Search Query: " . $query);

    // Search for the city in the database
    $city_data = $wpdb->get_row($wpdb->prepare("
        SELECT wp_posts.ID, wp_posts.post_title AS city_name, wp_terms.name AS country_name 
        FROM wp_posts 
        INNER JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) 
        INNER JOIN wp_terms ON (wp_term_relationships.term_taxonomy_id = wp_terms.term_id) 
        WHERE wp_posts.post_type = 'city' AND wp_posts.post_title = %s
    ", $query));

    if ($city_data) {
        $latitude = get_post_meta($city_data->ID, '_city_latitude', true);
        $longitude = get_post_meta($city_data->ID, '_city_longitude', true);
        $temperature = 'Temperature data not available';

        // If coordinates are available, get the temperature from OpenWeatherMap
        if ($latitude && $longitude) {
            $api_key = '815a7194fe1f3773baebf684f9ca3eeb';
            $api_url = "https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$api_key}&units=metric";
            $response = wp_remote_get($api_url);
            $data = json_decode(wp_remote_retrieve_body($response));

            // If successful, use the temperature data from the API
            if (!is_wp_error($data) && isset($data->main->temp)) {
                $temperature = esc_html($data->main->temp) . "°C";
            }
        }

        // Format the data into a table row
        $response_data = "<tr><td>{$city_data->country_name}</td><td>{$city_data->city_name}</td><td>{$temperature}</td></tr>";
        wp_send_json_success($response_data);
    }

    // Fetch weather data from the API
    $api_url = "https://api.openweathermap.org/data/2.5/weather?q={$query}&appid=815a7194fe1f3773baebf684f9ca3eeb&units=metric";
    $response = wp_remote_get($api_url);

    // Check if the response is valid
    if (is_wp_error($response)) {
        wp_send_json_error('Error fetching data from API');
    }

    // Decode the response body (JSON)
    $data = json_decode(wp_remote_retrieve_body($response));

    // Check if the API returned valid data
    if (isset($data->main->temp) && isset($data->sys->country)) {
        // Prepare the response data (City, Country, Temperature)
        $country = esc_html($data->sys->country);
        $city = esc_html($data->name);
        $temperature = esc_html($data->main->temp) . "°C";

        // Format the data into a table row
        $response_data = "<tr><td>{$country}</td><td>{$city}</td><td>{$temperature}</td></tr>";

        // Return the data
        wp_send_json_success($response_data); // Send success response with the data
    } else {
        wp_send_json_error('No weather data found for the city');
    }
}

add_action('wp_ajax_filter_cities', 'filter_cities');
add_action('wp_ajax_nopriv_filter_cities', 'filter_cities');


// get the temperature from API
function fetch_city_temperature() {
    if (!isset($_POST['city_id'])) {
        wp_send_json_error('City ID not provided');
    }

    $city_id = intval($_POST['city_id']);
    $latitude = get_post_meta($city_id, '_city_latitude', true);
    $longitude = get_post_meta($city_id, '_city_longitude', true);
    $api_key = '815a7194fe1f3773baebf684f9ca3eeb';
    
    // OpenWeatherMap API request
    $api_url = "https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$api_key}&units=metric";
    $response = wp_remote_get($api_url);
    $data = json_decode(wp_remote_retrieve_body($response));

    if (!is_wp_error($data) && isset($data->main->temp)) {
        wp_send_json_success(array('temperature' => $data->main->temp . '°C'));
    } else {
        wp_send_json_error('Temperature data not available');
    }
}
add_action('wp_ajax_fetch_city_temperature', 'fetch_city_temperature');
add_action('wp_ajax_nopriv_fetch_city_temperature', 'fetch_city_temperature');