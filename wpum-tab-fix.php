<?php
/**
 * Plugin Name: WPUM Tab Fix
 * Description: Configuration of WPUM tabs for profile and settings
 * Version:     0.01
 * Author:      The Develoopers
 * Author URI:  https://loopis.org
 * License:     GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}

// Rename profile tabs
add_filter( 'wpum_get_registered_profile_tabs', 'my_wpum_get_registered_profile_tabs' );
function my_wpum_get_registered_profile_tabs( $tabs ) {
	$tabs['coins']['name'] = esc_html( '👛' );
	$tabs['economy']['name'] = esc_html( '🧮' );
	$tabs['about']['name'] = esc_html( '⚙' );
	return $tabs; }

// Rearrange profile tabs
add_filter( 'wpum_get_registered_profile_tabs', 'my_wpum_rearrange_profile_tabs', 100 );
function my_wpum_rearrange_profile_tabs( $tabs ) {
	$tabs['coins'] ['priority'] = 1;
	$tabs['economy'] ['priority'] = 2;
	$tabs['about'] ['priority'] = 3;
	return $tabs; }

// Remove profile tabs we do not use
add_filter( 'wpum_get_registered_profile_tabs', 'my_wpum_remove_profile_tabs', 999 );
function my_wpum_remove_profile_tabs( $tabs ) {
	unset( $tabs['posts'] );
	return $tabs; }
	
// Rename setting tabs
add_filter( 'wpum_get_account_page_tabs', 'wpum_account_change_settings_tab_name', 20 );
function wpum_account_change_settings_tab_name( $tabs ) {
	$tabs['settings']['name'] = esc_html( 'Kontaktuppgifter' );
	$tabs['medlemsregister']['name'] = esc_html( 'Medlemsregister' );
	$tabs['email-subscriptions']['name'] = esc_html( 'Medlemsbrev' );
	$tabs['password']['name'] = esc_html( 'Lösenord' );
	$tabs['view']['name'] = esc_html( '← Tillbaka till profil' );
	$tabs['logout']['name'] = esc_html( ' ' );
	return $tabs; }

// Rearrange settings tabs
add_filter( 'wpum_get_account_page_tabs', 'my_wpum_rearrange_settings_tabs', 100 );
function my_wpum_rearrange_settings_tabs( $tabs ) {
	$tabs['settings'] ['priority'] = 1;
	$tabs['medlemsregister'] ['priority'] = 2;
	$tabs['password'] ['priority'] = 3;
	$tabs['email-subscriptions'] ['priority'] = 4;
	$tabs['view'] ['priority'] = 5;
	$tabs['logout'] ['priority'] = 6;
	return $tabs; } ?>