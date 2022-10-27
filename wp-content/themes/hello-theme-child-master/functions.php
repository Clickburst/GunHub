<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

define('GUNHUB_THEME_VERSION', '1.0.0');

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
        GUNHUB_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts' );
