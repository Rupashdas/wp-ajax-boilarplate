<?php
require_once get_theme_file_path( "inc/tgm.php" );

if ( ! defined( 'VERSION' ) ) {
	
	// define( 'VERSION', wp_get_theme()->get( 'Version' ) );
	define( 'VERSION', time() );

}

function dublin_setup() {

	load_theme_textdomain( 'dublin', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary', 'dublin' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );
}
add_action( 'after_setup_theme', 'dublin_setup' );

function dublin_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dublin_content_width', 640 );
}
add_action( 'after_setup_theme', 'dublin_content_width', 0 );

function dublin_scripts() {

	wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css', array(), '5.3.8' );
	wp_enqueue_style( 'dublin-style', get_stylesheet_uri(), array(), VERSION );

	wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js', array(), '5.3.8', true );
	wp_enqueue_script( 'main-js', get_theme_file_uri( 'assets/js/main.js' ), array( 'jquery' ), VERSION, true );
	$dublin_data = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'abcd' ),
	);
	wp_localize_script( 'main-js', 'dublin_data', $dublin_data );
}
add_action( 'wp_enqueue_scripts', 'dublin_scripts' );

function dublin_remove_max_srcset_image_width( $max_width ) {
	return false; 
} 
add_filter( 'max_srcset_image_width', 'dublin_remove_max_srcset_image_width' ); 

function dublin_disable_srcset( $sources ) {
	return false; 
} 
add_filter( 'wp_calculate_image_srcset', 'dublin_disable_srcset' );

function dublin_remove_width_attribute( $html ) {
  $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
  return $html;
}
add_filter( 'wp_get_attachment_image', 'dublin_remove_width_attribute', 10 );
add_filter( 'post_thumbnail_html', 'dublin_remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'dublin_remove_width_attribute', 10 );

function dublin_disable_image_compression() {
   return 100;
}
add_filter( 'wp_editor_set_quality', 'dublin_disable_image_compression');
add_filter( 'jpeg_quality', 'dublin_disable_image_compression' );

if (function_exists('wpcf7')) {
	add_filter('wpcf7_autop_or_not', '__return_false');
}


function dublin_link_check($link){
	return is_array($link) && $link['url'] != "" && $link['title'] != "";
}


function dublin_login_logo() {
	if(class_exists('ACF')) {
		wp_enqueue_style('dublin-login', get_theme_file_uri('assets/css/dublin-login.css'), array(), VERSION, 'all');
		$dublin_login_bg = get_field('login_background_image', 'option');
		$dublin_login_logo = get_field('login_logo', 'option');
		echo '<style type="text/css">
			body.login div#login h1 a {
				background-image: url("' . $dublin_login_logo . '") !important;
			}
			body.login {
				background-image: url("' . esc_url($dublin_login_bg) . '");
			}
		</style>';
	}
	
}
add_action('login_enqueue_scripts', 'dublin_login_logo');

function dublin_change_login_logo_url($url) {
	return home_url();
}
add_filter('login_headerurl', 'dublin_change_login_logo_url');

if(class_exists('ACF')) {
	if (get_field("hide_acf_settings", "option")) {
		add_filter('acf/settings/show_admin', '__return_false');
	}
}
function dublin_display_current_year() {
    return date('Y');
}
add_shortcode('year', 'dublin_display_current_year');

function dublin_load_global_posts(){

	$nonce = $_POST['nonce'] ? sanitize_text_field($_POST['nonce']) : '';

	if ( ! wp_verify_nonce( $nonce, 'abcd' ) ) {
		wp_send_json_error( 'Invalid nonce' );
		die();
	}

	$page_number = isset($_POST['from_page']) ? intval($_POST['from_page']) : 1;
	$search_value = isset($_POST['search_value']) ? sanitize_text_field( $_POST['search_value'] ) : "";
	$categories = isset($_POST['categories']) && is_array($_POST['categories']) && count($_POST['categories']) > 0 ? $_POST['categories'] : array();

	$args = array(
		"post_type" => "post",
		"posts_per_page" => 6,
		"post_status" => "publish",
		"paged" => $page_number,
	);

	if($search_value != ""){
		$args["s"] = $search_value;
	}

	if(!empty($categories)){
		$args['category__in'] = $categories;
	}

	$post_query = new WP_Query($args);

	if($post_query->have_posts()){
		ob_start();
		while($post_query->have_posts()){
			$post_query->the_post();
			get_template_part("template-parts/post", "card", array("post_id" => get_the_ID()) );
		}
		$html = ob_get_clean();
	}
	$response = array(
		"html" => $html,
		"query" => $args,
	);
	wp_send_json_success( $response );

	die();
}

add_action( 'wp_ajax_load_global_posts', 'dublin_load_global_posts' );
add_action( 'wp_ajax_nopriv_load_global_posts', 'dublin_load_global_posts' );

