<?php

// Leverage TypeRocket to create post types, meta boxes, and taxonomies
add_action( 'typerocket_loaded', function() {
	
	// Create the "News Post" post type
	$company = tr_post_type('Company','Companies');
	$company->setId('company');
	$company->setSlug('companies');
	$company->setSupports(['title', 'author', 'thumbnail', 'revisions']);
	$company->setIcon('dashicons-store');

	tr_meta_box('Company Details')->apply($company);
	function add_meta_content_company_details() {
		$form = tr_form();
		echo $form->textarea('Company Description');
	}
});


// Set archive query args
function order_by_menu_order_company( $query ){
	if ( is_post_type_archive('company') && $query->is_main_query() && !is_admin() ){
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'DESC' );
		// get current year and previous year
		$query->set( 'posts_per_page', 10 );
	}
	return;
}
add_action( 'pre_get_posts', 'order_by_menu_order_company' );