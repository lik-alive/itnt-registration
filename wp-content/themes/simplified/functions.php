<?php
require_once get_template_directory() . '/scripts/scriptlinker.php';

if (!function_exists('simplified_setup')) :
	function simplified_setup()
	{
		add_theme_support(
			'custom-logo',
			array(
				'flex-width'  => true
			)
		);
	}
endif;
add_action('after_setup_theme', 'simplified_setup');

/**
 * Enqueue scripts and styles.
 */
function simplified_scripts()
{
	// Theme stylesheet.
	wp_enqueue_style('simplified-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'simplified_scripts');

/**
 * Set expiration for the session
 */
add_filter('auth_cookie_expiration', 'my_expiration_filter', 99, 3);
function my_expiration_filter($seconds, $user_id, $remember)
{
	//if "remember me" is checked;
	if ($remember) {
		//WP defaults to 2 weeks;
		$expiration = 30 * 24 * 60 * 60;
	} else {
		//WP defaults to 48 hrs/2 days;
		$expiration = 1 * 12 * 60 * 60;
	}

	//http://en.wikipedia.org/wiki/Year_2038_problem
	if (PHP_INT_MAX - time() < $expiration) {
		//Fix to a little bit earlier!
		$expiration = PHP_INT_MAX - time() - 5;
	}
	return $expiration;
}

// Hide admin bar for all users except admin
function remove_admin_bar()
{
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'remove_admin_bar');

//Block users access to admin dashboard
function block_dashboard()
{
	$file = basename($_SERVER['PHP_SELF']);
	if (is_user_logged_in() && is_admin() && !is_super_admin() && $file != 'admin-ajax.php') {
		wp_redirect(home_url());
		exit();
	}
}
add_action('init', 'block_dashboard');

//Deregister plugins' styles
function deregister_styles()
{
	wp_deregister_style('pp-flat-ui');
}
add_action('wp_print_styles', 'deregister_styles', 100);

// Block display of the version number
function wpbeginner_remove_version()
{
	return '';
}
add_filter('the_generator', 'wpbeginner_remove_version');

// Rewrite permalinks for ID_Participant
add_action('init', 'do_rewrite');
function do_rewrite(){
	add_rewrite_rule( '^([A-Za-z0-9]{8})/?$', 'index.php?plink=$matches[1]', 'top' );
	add_rewrite_rule( '^restore/?$', 'index.php?restore=true', 'top' );
	
	add_filter( 'query_vars', function( $vars ){
		$vars[] = 'plink';
		$vars[] = 'restore';
		return $vars;
	} );
}
