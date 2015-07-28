<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Genesis Sample Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.1.2' );

//* Enqueue Google Fonts
add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
function genesis_sample_google_fonts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

// JTSK edits start
// function genesis() {

// 	get_header();

// 	do_action( 'genesis_before_content_sidebar_wrap' );
// 	genesis_markup( array(
// 		'html5'   => '<div %s>',
// 		'xhtml'   => '<div id="content-sidebar-wrap">',
// 		'context' => 'content-sidebar-wrap',
// 	) );

// 		do_action( 'genesis_before_content' );
// 		genesis_markup( array(
// 			'html5'   => '<main %s>',
// 			'xhtml'   => '<div id="content" class="hfeed">',
// 			'context' => 'content',
// 		) );
// 			do_action( 'genesis_before_loop' );
// 			do_action( 'genesis_loop' );
// 			do_action( 'genesis_after_loop' );
// 		genesis_markup( array(
// 			'html5' => '</main>', //* end .content
// 			'xhtml' => '</div>', //* end #content
// 		) );
// 		do_action( 'genesis_after_content' );

// 	echo '</div>'; //* end .content-sidebar-wrap or #content-sidebar-wrap
// 	do_action( 'genesis_after_content_sidebar_wrap' );

// 	get_footer();

// }

// add_action('template_redirect', 'sp_custom_post_content');
// function sp_custom_post_content(){
//   // only work for home & blog template page
//   if( is_home() || is_front_page() || is_page_template('page_blog.php') ){ 
//     remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
//     remove_action( 'genesis_post_content', 'genesis_do_post_content' );
//     add_action( 'genesis_entry_content', 'genesis_do_custom_post_content' );
//     add_action( 'genesis_post_content', 'genesis_do_custom_post_content' );
//   }
// }

// function genesis_do_custom_post_content(){
//   global $wp_query;
//   if( $wp_query->current_post == 0 ){ 
//    // Only first post will display full content
//     the_content();
//   }else{
//     the_excerpt();
//   }
// }

add_theme_support( 'genesis-custom-header', array(
	'width' => 800, 'height' => 340)
);

// adding stylesheets
function font_awesome_styles() {
	wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri().'/custom_css/font-awesome.css', array(), "4.2.0");
}

function custom_styles() {
	wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri().'/custom_style.css', array(), "1.0.0");
}

function custom_google_fonts_styles() {
	wp_enqueue_style( 'foodie-google-fonts', '//fonts.googleapis.com/css?family=Josefin+Sans:400,700|Open+Sans:300,400|Lato:300,400', array(), CHILD_THEME_VERSION );
}

add_action( 'wp_enqueue_scripts', 'font_awesome_styles');
add_action( 'wp_enqueue_scripts', 'custom_styles');
add_action( 'wp_enqueue_scripts', 'custom_google_fonts_styles');