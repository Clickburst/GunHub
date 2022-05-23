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



// filter before sending emails in formidable form 
function send_email_to_listitem_author($recipients, $values, $form_id, $args){
	
	//if form is "Contact Listing Owner"
	if ( $form_id == 1 ) {
		$recipients = array();	
		$idx = 0;
		// find the additional hidden field with the page author's email.
		foreach ( $values as $value ) {
			 if ( $value->field_key == 'author_email' ){
				$recipients[] = $value->meta_value;
				break;
			 }
			 $idx++;
		}
		array_splice($values, $idx, 1);
	}
	

    return $recipients;
}
add_filter('frm_to_email', 'send_email_to_listitem_author', 10, 4);