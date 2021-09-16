<?php
function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

/**
* Function get_posts_by_tax_terms
* Returns array of posts for post type / taxonomy / terms combination
*
* @param array $args requires the following:
* 		'post_type' => (string) slug,
*		'taxonomy' => (string) slug,
*		'terms' => can be any of the following:
*			- a numeric term id
*			- a single term slug
*			- an array of ids
*			- an array of slugs,
* @return array of posts
*/

function get_posts_by_tax_terms( $args ){
	extract($args);

	if(
		!isset($post_type) || 
		!isset($taxonomy) || 
		!isset($terms) 
	){
		return false;
	}

	$field = false; // initialize

	if(is_numeric($terms)){
		$field = 'id';
	} else if( is_string($terms) ){
		$field = 'slug';
	} else if ( is_array($terms) ){
		$term_values = array_values($terms); 
		$first_term = $term_values[0];
		if(is_numeric($first_term)){
			$field = 'id';
		} else if ( is_string($first_term) ){
			$field = 'slug';
		}
	}

	if(!$field)
		return;

	$posts = get_posts( array(
		'post_type' => $post_type,
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy,
				'field' => $field,
				'terms' => $terms,
			),
		),
	) );

	if(!empty($posts)){
		return $posts;
	}
	return false;
}


// find out the page template configured in the page editor

function get_template_for_page_editor(){
	$post_id = (isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : false ) );
	if($post_id){
		return get_post_meta($post_id,'_wp_page_template',true);
	}
	return false;	
}

function warlock_clean_between_tags( $content ){
	// remove spaces, tabs, line breaks, etc. in between brackets
	$content = preg_replace('/\].*?\[/', '][', $content);
	$content = preg_replace('/\].[\r\n]+\[/', '][', $content);
	$content = str_replace('] [', '][', $content);
	$content = preg_replace('/\]\s+\[/', '][', $content);

	// remove additional whitespace and line breaks
	$content = preg_replace('/\s*$^\s*/m', "\n", $content);
	$content = preg_replace('/[ \t]+/', ' ', $content);
	$content = preg_replace( "/\r|\n/", "", $content );
	$content = str_replace("\r", "", $content);
	$content = str_replace("\n", "", $content);

	// remove html comments
	$content = preg_replace('/<!--(.*)-->/Uis', '', $content);

	// remove spaces between html tags
	$content = preg_replace('~>\\s+<~m', '><', $content);

	// add ids to tabs if they don't exist
	if(strpos($content, '[vc_tab ') !== false && strpos($content, 'tab_id=') === false){
		$tab_parts = explode('[vc_tab ', $content);
		$content = '';
		$tab_counter = 1;
		foreach($tab_parts as $tab_part){
			$content .= $tab_part;
			if($tab_counter != count($tab_parts)){
				$content .= '[vc_tab tab_id="' . $tab_counter . '" ';
			}
			$tab_counter++;
		}
	}

	$content = trim($content);
	return $content;
}

// credit: http://webcheatsheet.com/php/get_current_page_url.php
if(!function_exists('curPageURL')){
	function curPageURL( $args=false ) {

		$defaultArgs = array(
			'reportPort' => false,
		);

		if(!empty($args)){
			$args = array_merge($defaultArgs,$args);
		} else {
			$args = $defaultArgs;
		}

		extract($args);

		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80" && $reportPort) {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
}

function remove_empty_paragraphs( $string ){
	
	// accommodate paragraph tags having one or two spaces in them
	$string = str_replace('<p>&nbsp;</p>', '<p></p>', $string);
	$string = str_replace('<p> </p>', '<p></p>', $string);
	$string = str_replace('<p>&nbsp;&nbsp;</p>', '<p></p>', $string);
	$string = str_replace('<p>  </p>', '<p></p>', $string);

	$pattern = "/<p[^>]*><\\/p[^>]*>/";
	return preg_replace($pattern, '', $string);
}

function set_featured_image_from_url( $image_url, $post_id ){

	$upload_dir = wp_upload_dir(); // Set upload folder
	$image_data = file_get_contents($image_url); // Get image data
	$filename   = basename($image_url); // Create image file name

	// Exit if we already have a featured image with the same data
	$posthaste = new _PH($post_id);
	$current_featured_img_data = $posthaste->get_featured_image_data();
	if(!empty($current_featured_img_data) && isset($current_featured_img_data['src'])){
		$current_featured_img_contents = file_get_contents($current_featured_img_data['src']);
		if($current_featured_img_contents == $image_data){
			// already have featured image that matches the one we're trying to set
			return;
		} else {
			// creating new featured image
		}
	}

	// Check folder permission and define file location
	if( wp_mkdir_p( $upload_dir['path'] ) ) {
	    $file = $upload_dir['path'] . '/' . $filename;
	} else {
	    $file = $upload_dir['basedir'] . '/' . $filename;
	}

	// Create the image file on the server
	file_put_contents( $file, $image_data );

	// Check image file type
	$wp_filetype = wp_check_filetype( $filename, null );

	// Set attachment data
	$attachment = array(
	    'post_mime_type' => $wp_filetype['type'],
	    'post_title'     => sanitize_file_name( $filename ),
	    'post_content'   => '',
	    'post_status'    => 'inherit'
	);

	// Create the attachment
	$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

	// Include image.php
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	// Define attachment metadata
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

	// Assign metadata to attachment
	wp_update_attachment_metadata( $attach_id, $attach_data );

	// And finally assign featured image to post
	set_post_thumbnail( $post_id, $attach_id );
}

function get_url_without_query_string( $url ){
    $url_parts = parse_url($url);
    $constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['port']) && $url_parts['port'] != '80' ? ':'.$url_parts['port']:'') . (isset($url_parts['path'])?$url_parts['path'] : '');
    return $constructed_url;
}

function append_path_to_url_keeping_query_string( $url, $path, $skip_if_path_exists_at_end_of_url=true ){
	
	// strip slashes off the beginning and end of the path
	$path = rtrim( ltrim( $path, '/' ), '/');
	
	// strip trailing slash off of url for comparison
	$url = rtrim( $url, '/' );
	$url_without_query_string = rtrim( get_url_without_query_string( $url ), '/');

	// check to see if it's already there
	if($skip_if_path_exists_at_end_of_url){
		if(strpos($url, $path) === (strlen($url_without_query_string)-strlen($path))){
			// Path already exists at the end, just return the url
			return $url;
		}
	}
	// perform the replacement
	$url_with_path = $url_without_query_string.'/'.$path.'/';
	$url_with_path_and_query_string = str_replace($url_without_query_string, $url_with_path, $url);
	$url_with_path_and_query_string = str_replace($url_with_path.'/', $url_with_path, $url_with_path_and_query_string);
	return $url_with_path_and_query_string;
}

function add_query_param_to_url( $url, $param, $value ){

	// first drop the query param if it already exists
	$url = drop_query_param_from_url( $url, $param );

	$url_parts = parse_url($url);
	if(!empty($url_parts['query'])){
		return $url . '&' . $param . '=' . $value;
	} else {
		return rtrim($url,'/') . '/?' . $param . '=' . $value;
	}
}

function drop_query_param_from_url( $url, $param ){

	$url_parts = parse_url($url);

	if(!empty($url_parts['query'])){
		$query_params = explode('&', $url_parts['query']);
		$parsed_params = array();

		foreach($query_params as $index => $_param){
			$param_parts = explode('=', $_param);
			if(count($param_parts) > 1){
				$parsed_params[$param_parts[0]] = $param_parts[1];
			}
		}

		if(array_key_exists($param, $parsed_params)){
			unset($parsed_params[$param]);
			
			$new_query = '';
			if(!empty($parsed_params)){
				foreach($parsed_params as $parsed_param_key => $parsed_param_value){
					$new_query .= $parsed_param_key . '=' . $parsed_param_value . '&';
				}
				$new_query = rtrim($new_query,'&');
			}

			$url_without_params = get_url_without_query_string($url);
			$new_url = rtrim($url_without_params,'/');
			if(!empty($new_query)){
				$new_url .= '?' . $new_query;
			}
			return $new_url;
		}
	}
	return $url;
}

function get_the_slug( $post_id=null ){
	$p = null; // post we'll retrieve slug from
	if(!$post_id || is_null($post_id)){
		global $post;
		if(isset($post)){
			$p = $post;
		} else {
			// didn't pass post ID, no global $post, return
			return false;
		}
	}
	if(is_null($p)){
		$p = get_post($post_id);
	}
	return $p->post_name;
}


function get_post_by_slug( $slug, $post_type=null ){
	$args = array(
		'posts_per_page' => 1,
		'name' => $slug,
		'post_status' => 'publish',
	);
	if(!is_null($post_type)){
		$args['post_type'] = $post_type;
	}
	$posts = get_posts($args);
	if($posts){
		return array_shift($posts);
	}
	return false;
}


function get_term_posts( $term, $post_type=null ){

	// If an ID is passed, get the term object
	if(is_numeric($term)){
		$term = get_term($term);
	}

	// Make sure we have a valid term before continuing
	if(!$term || !is_object($term))
		return false;

	$taxonomy = get_taxonomy($term->taxonomy);

	// If no post type is passed, return the first post type associated with the term tax
	if(is_null($post_type)){
		$post_types = property_exists($taxonomy,'object_type') ? $taxonomy->object_type : null;
		$post_type = is_array($post_types) ? array_shift($post_types) : $post_types;
	}
	
	// Make sure we have a valid post type before continuing
	if(!$post_type)
		return false;

	return get_posts([
		'post_type' => $post_type,
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => $term->taxonomy,
				'field' => 'slug',
				'terms' => $term->slug
			)
		)
	]);
}

/**
 * Registers an additional, custom directory for WordPress to look for page templates.
 * Adds options to the Page Attributes "Template" drop-down.
 * 
 * @param  string $directory - should be a folder path relative to the theme root.
 */
function register_page_template_directory( $directory ){

	add_action( 'theme_page_templates', function($templates) use ($directory){

		// Add trailing slash
		$directory = trailingslashit( $directory );

		// Build out full directory path from the relative theme path
		$directory_full_path = trailingslashit( trailingslashit( get_stylesheet_directory() ) . $directory );

		foreach (glob( $directory_full_path . '*.php' ) as $filepath) {
			if( preg_match( '|Template Name:(.*)$|mi', file_get_contents( $filepath ), $header ) ){
				$filepath_parts = explode('/', $filepath);
				if(!empty($filepath_parts)){
					$filename = $filepath_parts[count($filepath_parts)-1];
				}
				$templates[$directory.$filename] = _cleanup_header_comment( $header[1] );
			}
		}
		return $templates;

	} );
}


/**
 * Checks to see if admin is editing a post of post type, and optionally also check the page template.
 *
 * Disclaimer: this is based on URL params, and could change 
 * if WP core does major overhaul in admin routing.
 * 
 * @param  string  $post_type
 * @param  string $template - optional (filename with or without extension)
 * @return post ID, or false if not a match
 */
function is_admin_editing( $post_type, $template=null ){
	if( is_admin() && isset($_GET['post']) && is_numeric($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit' ){
		if(function_exists('get_post')){
			$editing_post = get_post($_GET['post']);
			if(!empty($editing_post) && is_object($editing_post) && $post_type == $editing_post->post_type){
				if(!is_null($template) && $post_type == 'page'){
					$template = str_replace('.php', '', $template);
					$page_template = get_page_template_slug( $_GET['post'] );
					$page_template_pathinfo = pathinfo($page_template);
					if(!empty($page_template_pathinfo['filename'])){
						if( $template == $page_template_pathinfo['filename'] ){
							return $_GET['post'];
						} else {
							return false;
						}
					}
				}
				return $_GET['post'];
			}
		}
	}
	return false;
}


if(!function_exists('pre_r')){
	function pre_r( $content ){
		echo '<pre>';
		print_r($content);
		echo '</pre>';
	}
}

