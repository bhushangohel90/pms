<?php

//Load styles in admin to make the blocks look sensible
function gutenCSS() {
    add_theme_support( 'editor-styles' );
}
add_action( 'after_setup_theme', 'gutenCSS' );

//Then register our blocks...
function register_acf_block_types() {
	
	//About Block
    acf_register_block_type(array(
        'name'              => 'about',
        'title'             => __('About'),
        'description'       => __('About'),
        'render_template'   => 'template-parts/blocks/about/about.php',
		//'enqueue_style' 	=> get_template_directory_uri() . '/template-parts/blocks/about/about.css',
		//'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/about/about.js',
        'icon'              => 'editor-help',
        'keywords'          => array( 'about', 'blocks' ),
        'mode'              => 'edit',
        'supports'          => array( 'mode' => false ),
        // To get the block preview:
        'example'         => array(
            'attributes' => array(
                'mode' => 'preview',
                'data' => [],
            ),  
        ),
    ));
	
	//Banner Block
    acf_register_block_type(array(
        'name'              => 'banner',
        'title'             => __('Banner'),
        'description'       => __('Banner'),
        'render_template'   => 'template-parts/blocks/banner/banner.php',
		'enqueue_style' 	=> get_template_directory_uri() . '/template-parts/blocks/banner/banner.css',
		'enqueue_script' 	=> get_template_directory_uri() . '/template-parts/blocks/banner/banner.js',
        'icon'              => 'format-image',
        'keywords'          => array( 'banner', 'blocks' ),
        'mode'              => 'edit',
        'supports'          => array( 'mode' => false ),
        // To get the block preview:
        'example'         => array(
            'attributes' => array(
                'mode' => 'preview',
                'data' => [],
            ),  
        ),
    ));
	
}

// Check if function exists and hook into setup.
if( function_exists('acf_register_block_type') ) { 
    add_action('acf/init', 'register_acf_block_types');
}