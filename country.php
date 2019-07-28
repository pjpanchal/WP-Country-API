<?php
/*
Plugin Name: Country Techmaster
Plugin URI: http://my-site.com
description: Plugin managing country from API 
Version: 1.2
Author: Pankaj Panchal
License: GPL2
*/


/* Register Post type */
add_action('init', 'country_register');
function country_register() {
   $labels = array(
      'name' => _x('Countries', 'post type general name'),
      'singular_name' => _x('Country', 'post type singular name'),
      'add_new' => _x('Add New', 'review'),
      'add_new_item' => __('Add New Country'),
      'edit_item' => __('Edit Country'),
      'new_item' => __('New Country'),
      'view_item' => __('View Country'),
      'search_items' => __('Search Country'),
      'not_found' =>  __('Nothing found'),
      'not_found_in_trash' => __('Nothing found in Trash'),
      'parent_item_colon' => ''
   );
   $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'query_var' => true,
      'menu_icon' => 'dashicons-images-alt',
      'rewrite' => true,
      'capability_type' => 'post',
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('thumbnail')
     ); 
   register_post_type( 'country' , $args );
}


/* enque css and js files for Admin*/
function wpdocs_enqueue_custom_admin_files() {
   wp_enqueue_style( 'custom_wp_admin_css',  plugins_url( '/css/countryStyle.css', __FILE__ ) );
   wp_enqueue_script( 'custom_wp_admin_js_c',  plugins_url( '/js/countryScript.js', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_files' );


/* enque css  files for Front*/
function enqueue_custom_files() {
   wp_enqueue_style( 'custom_front_css',  plugins_url( '/css/countryStyleFront.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_files' );




/* Register meta boxes.  */
function pj_register_meta_boxes() {
    add_meta_box( 'pj-1', __( '<h2>Country Values </h2>', 'hcf' ), 'pj_display_callback', 'country' );
}
add_action( 'add_meta_boxes', 'pj_register_meta_boxes' );

/* Meta box display callback.  */
function pj_display_callback( $post ) {
    include plugin_dir_path( __FILE__ ) . './form.php';
}

/* Hook on save POST */
function pj_save_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
      'pj_country',
      'pj_topLevelDomain',
      'pj_alpha2Code',
      'pj_alpha3Code',
      'pj_callingCodes',
      'pj_timezones',
      'pj_currencies',
      'pj_countryflag',
      'pj_publishingtime'
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
}
add_action( 'save_post', 'pj_save_meta_box' );


/* Get Country data as AJAX call */
function getCountryData() {
 
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {

        $cid = $_REQUEST['cid'];


        $url = "https://restcountries.eu/rest/v2/alpha?codes=".$cid;
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);

        $json = array_shift(json_decode($contents, true)); // decode the JSON into an associative array


        $dt = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $json['alpha2Code']);
        $d = new DateTime("now", new DateTimeZone($dt['0']));
        $time =  $d->format(DateTime::W3C); //2010-08-14T15:22:22+00:00

        $cData = [];
        $cData['topLevelDomain'] = $json['topLevelDomain']['0'];
        $cData['alpha2Code'] = $json['alpha2Code'];
        $cData['alpha3Code'] = $json['alpha3Code'];
        $cData['callingCodes'] = array_shift($json['callingCodes']);
        $cData['timezones'] = $json['timezones']['0'];
        $cData['currencies'] = array_shift($json['currencies']['0']);
        $cData['flag'] = $json['flag'];
        $cData['pTime'] = $time;

        echo json_encode($cData,JSON_FORCE_OBJECT);
    }
     
    // Always die in functions echoing ajax content
   die();
}
add_action( 'wp_ajax_getCountryData', 'getCountryData' );
add_action( 'wp_ajax_nopriv_getCountryData', 'getCountryData' );


/* Shortcode for display POST */
add_shortcode( 'country', 'display_country_post_type' );
function display_country_post_type(){
    $args = array(
        'post_type' => 'country',
        'post_status' => 'publish'
    );
    $query = new WP_Query( $args );
    if( $query->have_posts() ){
       echo  '<div class="row">';
        while( $query->have_posts() ){
            $query->the_post();
            echo '<div class="col-md-4 ">';
            echo '<div class="countryBlock ">';
                echo '<div class="title"><h3>' . get_the_title() . '</h3></div>';
                echo '<div class="thumbImg"><img src='.esc_html( get_post_meta( get_the_ID(), 'pj_countryflag', true ) ).'></div>';
                echo '<div class="detailList"> Top Level Domain: '.esc_html( get_post_meta( get_the_ID(), 'pj_topLevelDomain', true ) ).'</div>';
                echo '<div class="detailList"> Alpha2 Code: '.esc_html( get_post_meta( get_the_ID(), 'pj_alpha2Code', true ) ).'</div>';
                echo '<div class="detailList">Alpha3 Code: '.esc_html( get_post_meta( get_the_ID(), 'pj_alpha3Code', true ) ).'</div>';
                echo '<div class="detailList">Calling Code: '.esc_html( get_post_meta( get_the_ID(), 'pj_callingCodes', true ) ).'</div>';
                echo '<div class="detailList">Timezone: '.esc_html( get_post_meta( get_the_ID(), 'pj_timezones', true ) ).'</div>';
                echo '<div class="detailList">Currency: '.esc_html( get_post_meta( get_the_ID(), 'pj_currencies', true ) ).'</div>';
                echo '<div class="detailList">Published on : <br/> '.esc_html( get_post_meta( get_the_ID(), 'pj_publishingtime', true ) ).'</div>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }else{
        echo '<h3> No Country Posts Found !</h3>';
    }
    wp_reset_postdata();
    
}

