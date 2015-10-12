<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Gusto' );
define( 'CHILD_THEME_URL', 'http://www.gustorequired.com/' );
define( 'CHILD_THEME_VERSION', '1.0.2' );

//* Enqueue Google Fonts
// add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
// function genesis_sample_google_fonts() {
// 	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );
// }

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* JTSK edits start
//* Add support for custom header
add_theme_support( 'custom-header', array(
  'flex-height'     => true,
  'width'           => 400,
  'height'          => 170,
  'header-selector' => '.site-title a',
  'header-text'     => false,
) );

//* adding stylesheets
function font_awesome_styles() {
	wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri().'/vendor/fonts/font-awesome.css', array(), "4.2.0");
}

function pure_grid_styles() {
  wp_enqueue_style( 'pure-grid', get_stylesheet_directory_uri().'/vendor/stylesheets/pure-grid.css', array(), "0.6.0");
}

function custom_styles() {
	wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri().'/custom_style.css', array(), "1.0.0");
}

function custom_google_fonts_styles() {
	wp_enqueue_style( 'foodie-google-fonts', '//fonts.googleapis.com/css?family=Oswald:300|Open+Sans:300,400|Lato:300,400', array(), CHILD_THEME_VERSION );
}

add_action( 'wp_enqueue_scripts', 'font_awesome_styles' );
add_action( 'wp_enqueue_scripts', 'pure_grid_styles' );
add_action( 'wp_enqueue_scripts', 'custom_styles' );
add_action( 'wp_enqueue_scripts', 'custom_google_fonts_styles' );

add_filter( 'genesis_post_meta', 'sp_post_meta_filter' );
function sp_post_meta_filter($post_meta) {
	$post_meta = '[post_categories]';
	return $post_meta;
}

//* add utility bar above header logo and menu
genesis_register_sidebar( array(
  'id' => 'utility-bar-right',
  'name' => __('Utility Bar Right', 'theme-prefix'),
  'description' => __('This is the right utility bar above header.', 'theme-prefix')
) );

genesis_register_sidebar( array(
  'id' => 'utility-bar-left',
  'name' => __('Utility Bar Left', 'theme-prefix'),
  'description' => __('This is the right utility bar above header.', 'theme-prefix')
) );

add_action( 'genesis_before_header', 'utility_bar' );
function utility_bar() {
  echo '<div class="utility-bar"><div class="wrap">';
 
  genesis_widget_area( 'utility-bar-left', array(
    'before' => '<div class="utility-bar-left">',
    'after' => '</div>',
  ));
 
  genesis_widget_area( 'utility-bar-right', array(
    'before' => '<div class="utility-bar-right">',
    'after' => '</div>',
  ));

  echo '</div></div>';
}
//* END utility bar section

add_image_size( 'excerpt-thumbnail', 192, 230, true );


//* move around the pagination so it is outside the main section
remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

function custom_genesis_posts_nav(){
  echo '<div class="wrap">';
    genesis_posts_nav();
  echo '</div>';
}

add_action( 'genesis_before_footer', 'custom_genesis_posts_nav');
add_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

//* customize entry header, entry meta
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
if ( !is_page() ) {
  $post_info = '[post_date] [post_comments] [post_edit]';
  return $post_info;
}}

//* customize read more link
add_filter('excerpt_more', 'sp_read_more_link');
add_filter('the_content_more_link', 'sp_read_more_link');
function sp_read_more_link() {
  return '...<a class="more-link" href="' . get_permalink() . '">Read More &rarr;</a>';
}

add_action('template_redirect', 'sp_custom_post_content');
function sp_custom_post_content(){
  remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
  remove_action( 'genesis_post_content', 'genesis_do_post_content' );
  add_action( 'genesis_entry_content', 'genesis_do_custom_post_content' );
  add_action( 'genesis_post_content', 'genesis_do_custom_post_content' );
}

function genesis_do_custom_post_content() {
  global $wp_query;
  if(is_category() || is_tag() || is_date() || is_search()) {
    // show no excerpt or content if it is cat or tax
    return;
  } else if($wp_query -> current_post == 0 && !is_paged()){ 
    // Only first post on home blog will display full content
    the_content();
  } else {
    // subsequent posts on home blog
    the_excerpt();
  }
}

//* Move Post Title and Post Info from inside Entry Header to Entry Content on Posts page
add_action( 'genesis_before_entry', 'reconfigure_post_content' );
function reconfigure_post_content() {
  global $wp_query;
  if (is_home() || is_archive() || is_tag() || is_search()) {
    remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
    remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
    remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
    remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

    if((is_category() || is_tag() || is_date()) || is_search() || ($wp_query -> current_post != 0 && !is_paged()) || is_paged()){
      add_action( 'genesis_entry_content', 'jk_add_excerpt_thumb', 9);
    }

    add_action( 'genesis_entry_content', 'genesis_do_post_title', 9 );

    if(!is_category() && !is_tag() && !is_date() && !is_search()){
      add_action( 'genesis_entry_content', 'genesis_post_info', 9 );
      add_action( 'genesis_entry_footer', 'genesis_post_meta' );
    }

    if(is_category() || is_tag() || is_date() || is_search()){
      add_filter( 'genesis_attr_entry', 'jk_add_archive_entry_pure_classes' );
    }
  }
}

function jk_add_excerpt_thumb() {
  the_post_thumbnail('excerpt-thumbnail');    
}

add_filter( 'post_thumbnail_html', 'my_post_image_html', 10, 3 );
function my_post_image_html( $html, $post_id, $post_image_id ) {
  $html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $html . '</a>';
  return $html;
}

function jk_add_archive_entry_pure_classes($attributes) {
  $attributes['class'] .= ' pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4';
  return $attributes;
}

add_filter('pre_get_posts', 'jk_number_of_posts_on_archive');
function jk_number_of_posts_on_archive($query){
  if ($query->is_archive){
    $query->set('posts_per_page', 28);
  }
  return $query;
} 

add_filter( 'genesis_term_meta_headline', 'be_default_category_title', 10, 2 );
function be_default_category_title( $headline, $term ) {
  if( ( is_category() || is_tag() || is_tax() || is_date() ) && empty( $headline ) )
    $headline = $term->name;
    
  return $headline;
}

// correct viewport tag for mobile devices 
add_action( 'genesis_meta', 'theme_viewport_meta_tag' );
function theme_viewport_meta_tag() {
  echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

// change footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
  $creds = 'Copyright [footer_copyright] &middot; <a href="http://www.gustorequired.com">Gusto Theme</a> by <a href="http://johnkeith.us">John Keith</a> &middot; Built on the <a href="http://www.studiopress.com/themes/genesis" title="Genesis Framework">Genesis Framework</a>. Powered by Wordpress.';
  return $creds;
}