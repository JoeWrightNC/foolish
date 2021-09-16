<?php

// Leverage TypeRocket to create post types, meta boxes, and taxonomies
add_action( 'typerocket_loaded', function() {
	
	// Create the "News Post" post type
	$news = tr_post_type('News');
	$news->setId('news');
	$news->setSlug('news');
	$news->setSupports(['title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions']);
	$news->setIcon('dashicons-media-text');

	tr_meta_box('Additional Details')->apply($news);
	function add_meta_content_additional_details() {
		$form = tr_form();
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
        echo $form->select('Ticker Symbol')->setOptions($options);
	}
});


// Set archive query args
function order_by_menu_order_news( $query ){
	if ( is_post_type_archive('news') && $query->is_main_query() && !is_admin() ){
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'DESC' );
		// get current year and previous year
		$query->set( 'posts_per_page', 10 );
	}
	return;
}
add_action( 'pre_get_posts', 'order_by_menu_order_news' );