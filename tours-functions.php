<?php
/**
 * Plugin Name: Tours
 * Plugin URI: test/
 * Description: Travel tours
 * Version: 1.0
 * Author: Smopy
 */

/**
 *
 * Custom Post Type function
 *
 **/
function create_posttype() {

    register_post_type( 'tours',
        array(
            'labels' => array(
                'name' => __( 'Tours' ),
                'add_new'            => __( 'New Tour' ),
                'edit_item'          => __( 'Edit Tour' ),
                'new_item'           => __( 'New Tour' ),
                'view_item'          => __( 'Watch Tour' ),
                'search_items'       => __( 'Found Tour' ),
                'not_found'          => __( 'Tours not found' ),
                'menu_name'          => __( 'Tours' ),
                'all_items'          => __( 'All Tours' ),

            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'supports'           => array('title','editor','thumbnail'),
    ));


    /**
     *
     * Create custom columns
     *
     */
    add_filter('manage_edit-tours_columns', 'add_new_tours_columns');
    function add_new_tours_columns($tours_columns) {
        $new_columns['cb'] = '<input type="checkbox" />';
        $new_columns['title'] = _x('Title', 'column name');
        $new_columns['country'] = _x('Country', 'column name');
        $new_columns['dates'] = _x('Dates', 'column name');
        $new_columns['cost'] = _x('Cost', 'column name');

        return $new_columns;
    }

    add_action('manage_tours_posts_custom_column', 'manage_tours_columns', 10, 2);

    function manage_tours_columns($column_name, $id) {
        switch ($column_name) {
            case 'country':
                $country = get_post_meta($id, 'tour_county', true);
                echo $country;
                break;

            case 'dates':
                $tour_departure = date_create(get_post_meta($id, 'tour_departure', true));
                $tour_arrival = date_create(get_post_meta($id, 'tour_arrival', true));

                echo 'Departure: '.date_format($tour_departure, "d/m/Y").' - Arrival: '. date_format($tour_arrival, "d/m/Y");
                break;

            case 'cost':
                $cost = get_post_meta($id, 'tour_cost', true);
                echo $cost;
                break;
            default:
                break;
        } // end switch
    }
}
add_action( 'init', 'create_posttype' );

/**
 *
 * Create Metaboxes for Tours
 *
 */
// ## Countries Meta Box
add_action( 'add_meta_boxes', 'tours_meta_county_add' );
function tours_meta_county_add(){
    add_meta_box( 'tours-meta-county', 'Country', 'county_meta_box', 'tours' );
}
function county_meta_box($post){
    $selected = get_post_meta($post->ID, 'tour_county', true);
//    Array of All Counties
    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

    ?>
    <p>
        <label for="tour_county">Choose Country: </label>
        <select name="tour_county" id="tour_county">
            <?php foreach ($countries as $country){
                ?>
                <option value="<?php echo $country?>" <?php selected( $selected, $country ); ?>> <?php echo $country?> </option>
            <?php } ?>
        </select>
    </p>
    <?php
}

// ## Dates Meta Box
add_action( 'add_meta_boxes', 'tours_meta_dates_add' );
function tours_meta_dates_add(){
    add_meta_box( 'tours-meta-dates', 'Tour Dates', 'dates_meta_box', 'tours' );
}
function dates_meta_box($post){
    $departure = get_post_meta($post->ID, 'tour_departure', true);
    $arrival = get_post_meta($post->ID, 'tour_arrival', true);

    ?>
    <table width="100%">
        <tr>
            <td>
                <label for="tour_departure">Departure: </label>
                <input type="date" name="tour_departure" id="tour_departure" value="<?php echo $departure; ?>" />
            </td>
            <td>
                <label for="tour_arrival">Arrival: </label>
                <input type="date" name="tour_arrival" id="tour_arrival" value="<?php echo $arrival; ?>" />
            </td>
        </tr>


    </table>
    <?php
}

// ## Cost Meta Box
add_action( 'add_meta_boxes', 'tours_meta_cost_add' );
function tours_meta_cost_add(){
    add_meta_box( 'tours-meta-cost', 'Tour Cost', 'cost_meta_box', 'tours' );
}
function cost_meta_box($post){
    $tour_cost = get_post_meta($post->ID, 'tour_cost', true);
    ?>
    <p>
        <label for="tour_cost">Price: </label>
        <input type="number" name="tour_cost" id="tour_cost" value="<?php echo $tour_cost; ?>" />

    </p>

    <?php
}

/**
 * Save Metaboxes
 */
add_action('save_post', function ($post_id) {
    if (isset($_POST['tour_county'])){
        update_post_meta($post_id, 'tour_county', $_POST['tour_county']);
        update_post_meta($post_id, 'tour_departure', $_POST['tour_departure']);
        update_post_meta($post_id, 'tour_arrival', $_POST['tour_arrival']);
        update_post_meta($post_id, 'tour_cost', $_POST['tour_cost']);
    }
});




