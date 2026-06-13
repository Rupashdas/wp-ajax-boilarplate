<?php
require_once get_theme_file_path( "lib/class-tgm-plugin-activation.php" );

function dublin_register_required_plugins() {

	$plugins = array(

		array(
			'name'               => 'Advanced Custom Fields PRO',
			'slug'               => 'advanced-custom-fields-pro',
			'source'             => get_theme_file_path( 'lib/plugins/advanced-custom-fields-pro.zip' ),
			'required'           => true,
			'version'            => '6.2.4',
			'force_activation'   => true, 
			'force_deactivation' => false,
			'external_url'       => '',
			'is_callable'        => '',
		),

		array(
			'name'     => 'Classic Editor',
			'slug'     => 'classic-editor',
			'required' => true,
		),

		array(
			'name'        => 'Contact Form 7',
			'slug'        => 'contact-form-7',
			'required' => true,
		),

		array(
			'name'        => 'SVG Support',
			'slug'        => 'svg-support',
			'required' => true,
		),

		array(
			'name'        => 'Disable Comments',
			'slug'        => 'disable-comments-rb',
			'required' => false,
		),

	);

	$config = array(
		'id'           => 'tgmpa',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'dublin_register_required_plugins' );