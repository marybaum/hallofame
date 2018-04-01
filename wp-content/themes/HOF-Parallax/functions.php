<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'agency', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'parallax' ) );

//* Add Image upload to WordPress Theme Customizer
add_action( 'customize_register', 'parallax_customizer' );
function parallax_customizer(){

	require_once( get_stylesheet_directory() . '/lib/customize.php' );

}

//* Include Section Image CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME' , 'HOF-Parallax Theme' );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/parallax/' );
define( 'CHILD_THEME_VERSION', '1.0' );

//register image sizes
add_image_size( 'featured', 900, 700, true );
add_image_size( 'square', 600, 600, TRUE );
add_image_size( 'archive', 800, 500, true );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'parallax_enqueue_scripts_styles' );
function parallax_enqueue_scripts_styles() {

	wp_enqueue_script( 'parallax-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_style( 'dashicons' );

	wp_register_style( 'hof_dionisio', get_stylesheet_directory_uri() . '/fonts/Dionisio2017/dionisio-seventeen.css' , 'array()' , '2' );

	wp_enqueue_style( 'hof_dionisio');
}

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add a body class for the category
add_filter( 'body_class', 'hof_body_class_add_categories' );
function hof_body_class_add_categories( $classes ) {

	// Get the categories assigned to this post
	$categories = get_the_category();

	// Loop over each category in the $categories array
	foreach ( $categories as $current_category ) {

		// Add the current category's slug to the $body_classes array
		$classes[] = $current_category->slug;

	}

	// Return the $body_classes array
	return $classes;
}

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 1 );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_nav' );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'parallax_secondary_menu_args' );
function parallax_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 360,
	'height'          => 170,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'footer-widgets',
	'footer',
) );

//* Custom breadcrumbs arguments
add_filter('genesis_breadcrumb_args', 'rflex_breadcrumb_args');
function rflex_breadcrumb_args($args) {
	$args['sep'] = ' &raquo; ';
	$args['list_sep'] = ', ';
	// Genesis 1.5 and later
	$args['display'] = true;
	$args['labels']['prefix'] = __('', 'rflex');
	$args['labels']['author'] = __(' ', 'rflex');
	$args['labels']['category'] = __(' ', 'rflex');
	// Genesis 1.6 and later
	$args['labels']['tag'] = __(' ', 'rflex');
	$args['labels']['date'] = __(' ', 'rflex');
	$args['labels']['search'] = __('Find ', 'rflex');
	$args['labels']['tax'] = __(' ', 'rflex');
	$args['labels']['post_type'] = __(' ', 'rflex');
	$args['labels']['404'] = __('404', 'rflex');
	// Genesis 1.5 and later
	return $args;
}
/**
 * Show Featured Image above Post Titles
 * Scope: Posts page (index)
 * @author Sridhar Katakam
 * @link   http://sridharkatakam.com/display-featured-images-post-titles-posts-page-genesis/
 */
add_action('genesis_before_entry', 'rp_postimg_above_title');

function rp_postimg_above_title() {

	remove_action('genesis_entry_content', 'genesis_do_post_image', 8);

	add_action('genesis_entry_header', 'rp_postimg', 9);
}

function rp_postimg() {
	if(is_category('12')) {
		echo '<a href="' . get_permalink() . '">' . genesis_get_image(array('size' => 'square')) . '</a>';
	}
	else {
	echo '<a href="' . get_permalink() . '">' . genesis_get_image(array('size' => 'large')) . '</a>';
	}
}

// Add body class for single Posts and static Pages with Featured images...

add_filter( 'body_class', 'rp_featured_img_body_class' );
function rp_featured_img_body_class( $classes ) {

	if (  is_singular( array ('post' , 'page' ) ) && has_post_thumbnail() )  {
		$classes[] = 'has-pic';
	}
	return $classes;
}

//...and without them.

add_filter( 'body_class' , 'rp_nopic' );

function rp_nopic( $classes ) {
	if (  is_singular( array ('post' , 'page' ) ) && !has_post_thumbnail() )  {
		$classes[] = 'no-pic';
	}
	return $classes;
}

// If there's no featured image, don't just use one of the attachments. Let the post breathe.



//* Hook after-post widget after the entry content
add_action( 'genesis_after_entry', 'parallax_after_entry', 5 );
function parallax_after_entry() {

	if ( ( is_singular( 'post' ) ) || ( is_singular( 'page' ) ) ) {
		genesis_widget_area( 'after-entry', array(
			'before' => '<div class="after-entry widget-area">',
			'after'  => '</div>',
		) );
	}

}

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'parallax_author_box_gravatar' );
function parallax_author_box_gravatar( $size ) {

	return 176;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'parallax_comments_gravatar' );
function parallax_comments_gravatar( $args ) {

	$args['avatar_size'] = 120;

	return $args;

}

//* Customize the entry meta in the entry header (requires HTML5 theme support)
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
	$post_info = '[post_edit]';
	return $post_info;
}

//* Nuke the goddamn entry meta in the entry footer (requires HTML5 theme support)
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-section-1',
	'name'        => __( 'Home Section 1', 'parallax' ),
	'description' => __( 'This is the home section 1 section.', 'parallax' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-section-2',
	'name'        => __( 'Home Section 2', 'parallax' ),
	'description' => __( 'This is the home section 2 section.', 'parallax' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-section-3',
	'name'        => __( 'Home Section 3', 'parallax' ),
	'description' => __( 'This is the home section 3 section.', 'parallax' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-section-4',
	'name'        => __( 'Home Section 4', 'parallax' ),
	'description' => __( 'This is the home section 4 section.', 'parallax' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-section-5',
	'name'        => __( 'Home Section 5', 'parallax' ),
	'description' => __( 'This is the home section 5 section.', 'parallax' ),
) );
genesis_register_sidebar( array(
	'id'          => 'after-entry',
	'name'        => __( 'After Entry', 'parallax' ),
	'description' => __( 'This is the after entry widget area.', 'parallax' ),
) );
