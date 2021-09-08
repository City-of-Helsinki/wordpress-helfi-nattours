<?php

/**
 * WP-nav-menus
 *
 * @package Nattours
 */

/**
 * Main menu
 */
function nattours_main_menu() {
	wp_nav_menu( [
		'theme_location'  => 'top_nav',
		'container'       => false,
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => '',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '%3$s',
		'depth'           => 4,
		'walker'          => new Nord\WP_navwalker
	] );
}

/**
 * Footer nav
 */
function nattours_footer_menu() {
	wp_nav_menu( [
		'theme_location'  => 'footer_nav',
		'container'       => false,
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => '',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '%3$s',
		'depth'           => 1,
	] );
}

/**
 * Footer nav right
 */
function nattours_footer_menu_right() {
	wp_nav_menu( [
		'theme_location'  => 'footer_nav_right',
		'container'       => false,
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => '',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '%3$s',
		'depth'           => 1,
	] );
}

/**
 * Footer nav bottom
 */
function nattours_footer_menu_bottom() {
	wp_nav_menu( [
		'theme_location'  => 'footer_nav_bottom',
		'container'       => false,
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => '',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '%3$s',
		'depth'           => 1,
	] );
}

/**
 * Register the menu
 */
register_nav_menus( [
	'top_nav'           => __( 'Main menu', TEXT_DOMAIN ),
	'footer_nav'        => __( 'Footer menu', TEXT_DOMAIN ),
	'footer_nav_right'  => __( 'Footer menu right', TEXT_DOMAIN ),
	'footer_nav_bottom' => __( 'Footer menu bottom', TEXT_DOMAIN ),
] );
