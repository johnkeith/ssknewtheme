<?php
/**
 * Widgeted areas and related functions.
 *
 * @package     FoodiePro
 * @subpackage  Genesis
 * @copyright   Copyright (c) 2014, Shay Bocks
 * @license     GPL-2.0+
 * @since       2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'genesis_before', 'foodie_pro_top_ad' );
/**
 * Load an ad section before .site-inner.
 *
 * @since   1.0.0
 *
 * @return  null if the top-cta sidebar isn't active.
 */
function foodie_pro_top_ad() {
	//* Return early if we have no ad.
	if ( ! is_active_sidebar( 'top-ad' ) ) {
		return;
	}

	echo '<div class="top-cta">';
		dynamic_sidebar( 'top-ad' );
	echo '</div>';
}

add_action( 'genesis_before_comments', 'foodie_pro_after_entry' );
/**
 * Load an after entry section before .entry-comments on single entries.
 *
 * @since   1.1.0
 *
 * @return  null if the after entry sidebar isn't active.
 */
function foodie_pro_after_entry() {
	//* Return early if we have no ad.
	if ( ! is_active_sidebar( 'after-entry' ) ) {
		return;
	}

	echo '<div class="after-entry">';
		dynamic_sidebar( 'after-entry' );
	echo '</div>';
}

//* Register widget areas.
genesis_register_sidebar( array(
	'id'			=> 'top-ad',
	'name'			=> __( 'Top Ad', 'foodie-pro' ),
	'description'	=> __( 'This is the top ad section.', 'foodie-pro' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-top',
	'name'			=> __( 'Home Top', 'foodie-pro' ),
	'description'	=> __( 'This is the home top section.', 'foodie-pro' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle',
	'name'			=> __( 'Home Middle', 'foodie-pro' ),
	'description'	=> __( 'This is the home middle section.', 'foodie-pro' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-bottom',
	'name'			=> __( 'Home Bottom', 'foodie-pro' ),
	'description'	=> __( 'This is the home bottom section.', 'foodie-pro' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'after-entry',
	'name'			=> __( 'Post Bottom', 'foodie-pro' ),
	'description'	=> __( 'This is the after entry section.', 'foodie-pro' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'recipes-top',
	'name'			=> __( 'Recipes Top', 'foodie-pro' ),
	'description'	=> __( 'This is the recipes top section.', 'foodie-pro' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'recipes-bottom',
	'name'			=> __( 'Recipes Bottom', 'foodie-pro' ),
	'description'	=> __( 'This is the recipes bottom section.', 'foodie-pro' ),
) );
