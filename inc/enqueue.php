<?php

/**
 * Enqueue scripts and styles.
 */

//Cache Buster - only use for local files, CDN will not work.
function enqueue( $file_handle, $relpath, $type='script', $file_deps=array() ) {

	$uri = get_theme_file_uri($relpath);
	$vsn = filemtime(get_theme_file_path($relpath));

	//Dynamically 
	if($type == 'script') wp_enqueue_script($file_handle, $uri, $file_deps, $vsn, true);
	else if($type == 'style') wp_enqueue_style($file_handle, $uri, $file_deps, $vsn);  

}


function scripts() {
	wp_enqueue_script( 'jquery' );

}
add_action( 'wp_enqueue_scripts', 'scripts' );





