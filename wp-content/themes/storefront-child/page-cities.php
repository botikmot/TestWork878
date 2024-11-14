<?php
/* Template Name: Cities Table */
get_header();
global $wpdb;

// Query to retrieve cities, countries, and temperatures
$cities = $wpdb->get_results("SELECT wp_posts.ID, wp_posts.post_title, wp_terms.name as country_name FROM wp_posts INNER JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) INNER JOIN wp_terms ON (wp_term_relationships.term_taxonomy_id = wp_terms.term_id) WHERE wp_posts.post_type = 'city'");


?>

<h1>Cities Table</h1>

<!-- Search Form -->
<input type="text" id="city-search" placeholder="Search city...">

<table>
    <thead>
        <tr>
            <th>Country</th>
            <th>City</th>
            <th>Temperature</th>
        </tr>
    </thead>
    <tbody id="cities-table-body">
            <?php foreach ($cities as $city): ?>
                <tr data-city-id="<?php echo esc_attr($city->ID); ?>">
                    <td><?php echo esc_html($city->country_name); ?></td>
                    <td><?php echo esc_html($city->post_title); ?></td>
                    <td class="temperature">Loading...</td>
                </tr>
            <?php endforeach; ?>
    </tbody>
</table>


<?php get_footer(); ?>


