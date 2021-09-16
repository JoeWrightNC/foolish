<?php

// Leverage TypeRocket to create post types, meta boxes, and taxonomies
add_action( 'typerocket_loaded', function() {
    
    // Create the "News Post" post type
    $recommendation = tr_post_type('Recommendation');
    $recommendation->setId('recommendation');
    $recommendation->setSlug('recommendations');
    $recommendation->setSupports(['title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions']);
    $recommendation->setIcon('dashicons-controls-volumeon');
    tr_meta_box('Company Profile')->apply($recommendation);
    function add_meta_content_company_profile() {
        $form = tr_form();
        $args = array(
            'post_type'              => 'company',
            'posts_per_page'         => -1,
            'post_status'            => 'publish',
        );
        $query = new WP_Query( $args );
        $titles = array_map( 'get_the_title', $query->posts );
        $options = array();
        foreach ($titles as $key => $title) {
            $options[$title] = $title;
        }
        echo $form->select('Company Ticker')->setOptions($options);
        wp_reset_query();
    }
});


// Set archive query args
function order_by_menu_order_recommendation( $query ){
    if ( is_post_type_archive('recommendation') && $query->is_main_query() && !is_admin() ){
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
        // get current year and previous year
        $query->set( 'posts_per_page', 10 );
    }
    return;
}
add_action( 'pre_get_posts', 'order_by_menu_order_recommendation' ); 