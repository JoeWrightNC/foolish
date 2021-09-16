<?php
/*
Plugin Name: Motley Fool
Description: Custom functions and utilities for Motley Fool Engineering Practical
Version: 2.0.0
Author: Joe Wright
*/

function mfool_plugin_load(){

	$optionArr = get_option('wporg_options');

	// include all files in the "post-types" folder
	foreach (glob( dirname(__FILE__) . '/post-types/*.php' ) as $filename) {
		include $filename;
	};
}


add_action('plugins_loaded','mfool_plugin_load',1);


/* Define the log function for logging to wp-content/debug.log.
It's defined in wp-environments, but that file is not loaded in production,
so we need this version in case there's a rogue _log() call in prod */
if(!function_exists('_log')){
	function _log( $message ) {
		if( WP_DEBUG === true ){
			if( is_array( $message ) || is_object( $message ) ){
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
}?>