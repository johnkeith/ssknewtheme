<?php
/**
 * Register Customizer settings.
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

// Continue if the Customizer_Library exists.
if ( class_exists( 'Customizer_Library' ) ) :

add_action( 'customize_register', 'foodie_pro_remove_customizer_defaults' );
/**
 * Remove unwanted default customizer sections for the Foodie Pro theme.
 *
 * @since  2.0.0
 *
 * @param  array $wp_customize
 */
function foodie_pro_remove_customizer_defaults( $wp_customize ) {
	$wp_customize->remove_section( 'colors' );
}

add_action( 'customize_register', 'foodie_pro_register_customizer_settings' );
/**
 * Register custom sections for the Foodie Pro theme.
 *
 * @since  2.0.0
 *
 * @param  array $wp_customize
 */
function foodie_pro_register_customizer_settings() {

	// Stores all the controls that will be added
	$options = array();

	// Stores all the sections to be added
	$sections = array();

	// Adds the sections to the $options array
	$options['sections'] = $sections;

	// Colors
	$section = 'foodie_pro_colors';

	$sections[] = array(
		'id'          => $section,
		'title'       => __( 'Colors', 'foodie-pro' ),
		'description' => __( 'You can customize your theme colors by changing any of the options below.', 'foodie-pro' ),
		'priority'    => '70',
	);

	$colors = foodie_pro_get_colors();
	$counter = 20;

	foreach ( $colors as $color => $setting ) {

		$options[ $color ] = array(
			'id'       => $color,
			'label'    => $setting['label'],
			'section'  => $section,
			'type'     => 'color',
			'default'  => $setting['default'],
			'priority' => $counter++,
		);
	}
	//* Allow users to disable Google Fonts Output.
	if ( ! apply_filters( 'foodie_pro_disable_google_fonts', false ) ) {
		// Typography
		$section = 'foodie_pro_typography';

		$sections[] = array(
			'id'          => $section,
			'title'       => __( 'Typography', 'foodie-pro' ),
			'description' => __( 'You can customize your fonts here. For best results, we recommend using no more than two unique font families.', 'foodie-pro' ),
			'priority'    => '75',
		);

		$fonts = foodie_pro_get_fonts();
		$counter = 20;

		foreach ( $fonts as $font => $setting ) {
			$options[ $font . '_family' ] = array(
				'id'      => $font . '_family',
				'label'   => $setting['label'] . __( ' Family', 'foodie-pro' ),
				'section' => $section,
				'type'    => 'select',
				'choices' => customizer_library_get_font_choices(),
				'default' => $setting['default_family'],
			);

			$choices = array(
				'200' => 'Light',
				'300' => 'Normal',
				'700' => 'Bold',
				'900' => 'Extra Bold',
			);

			$options[ $font . '_weight' ] = array(
				'id'      => $font . '_weight',
				'label'   => $setting['label'] . __( ' Weight', 'foodie-pro' ),
				'section' => $section,
				'type'    => 'select',
				'choices' => $choices,
				'default' => $setting['default_weight'],
			);

			if ( 'disabled' !== $setting['default_size'] ) {
				$choices = array(
					'14px' => 'Small',
					'16px' => 'Normal',
					'18px' => 'Large',
					'21px' => 'Extra Large',
				);

				$options[ $font . '_size' ] = array(
					'id'      => $font . '_size',
					'label'   => $setting['label'] . __( ' Size', 'foodie-pro' ),
					'section' => $section,
					'type'    => 'select',
					'choices' => $choices,
					'default' => $setting['default_size'],
				);
			}

			if ( 'disabled' !== $setting['default_style'] ) {
				$choices = array(
					'normal' => 'Normal',
					'italic' => 'Italic',
				);

				$options[ $font . '_style' ] = array(
					'id'      => $font . '_style',
					'label'   => $setting['label'] . __( ' Style', 'foodie-pro' ),
					'section' => $section,
					'type'    => 'select',
					'choices' => $choices,
					'default' => $setting['default_style'],
				);
			}
		}
	}

	$choices = array(
		'full'       => __( 'Full Width', 'foodie-pro' ),
		'one_half'   => __( 'One Half', 'foodie-pro' ),
		'one_third'  => __( 'One Third', 'foodie-pro' ),
		'one_fourth' => __( 'One Fourth', 'foodie-pro' ),
		'one_sixth'  => __( 'One Sixth', 'foodie-pro' ),
	);

	$options['foodie_pro_archive_grid'] = array(
		'id'      => 'foodie_pro_archive_grid',
		'label'   => __( 'Archive Grid Display:', 'foodie-pro' ),
		'section' => 'genesis_archives',
		'type'    => 'select',
		'choices' => $choices,
		'default' => 'full',
		'priority' => 0,
	);

	$options['foodie_pro_archive_show_title'] = array(
		'id'       => 'foodie_pro_archive_show_title',
		'label'    => __( 'Display The Title?', 'foodie-pro' ),
		'section'  => 'genesis_archives',
		'type'     => 'checkbox',
		'default'  => 1,
		'priority' => 5,
	);

	$options['foodie_pro_archive_show_info'] = array(
		'id'       => 'foodie_pro_archive_show_info',
		'label'    => __( 'Display The Entry Info?', 'foodie-pro' ),
		'section'  => 'genesis_archives',
		'type'     => 'checkbox',
		'default'  => 1,
		'priority' => 6,
	);

	$options['foodie_pro_archive_show_content'] = array(
		'id'       => 'foodie_pro_archive_show_content',
		'label'    => __( 'Display The Content?', 'foodie-pro' ),
		'section'  => 'genesis_archives',
		'type'     => 'checkbox',
		'default'  => 1,
		'priority' => 7,
	);

	$options['foodie_pro_archive_show_meta'] = array(
		'id'       => 'foodie_pro_archive_show_meta',
		'label'    => __( 'Display The Entry Meta?', 'foodie-pro' ),
		'section'  => 'genesis_archives',
		'type'     => 'checkbox',
		'default'  => 1,
		'priority' => 8,
	);

	$choices = array(
		'after_title'   => __( 'After Title', 'foodie-pro' ),
		'before_title'  => __( 'Before Title', 'foodie-pro' ),
		'after_content' => __( 'After Content', 'foodie-pro' ),
	);

	$options['foodie_pro_archive_image_placement'] = array(
		'id'      => 'foodie_pro_archive_image_placement',
		'label'   => __( 'Featured Image Placement:', 'foodie-pro' ),
		'section' => 'genesis_archives',
		'type'    => 'select',
		'choices' => $choices,
		'default' => 'after_title',
	);

	// Adds the sections to the $options array
	$options['sections'] = $sections;

	$customizer_library = Customizer_Library::Instance();
	$customizer_library->add_options( $options );

}

/**
 * An array of the color settings used in Foodie Pro.
 *
 * @since  2.0.3
 * @return array $colors
 */
function foodie_pro_get_colors() {
	$colors = array(
		'foodie_bg_color' => array(
			'default'  => '#ffffff',
			'label'    => __( 'Main Background Color', 'foodie-pro' ),
			'selector' => 'body',
			'rule'     => 'background',
		),
		'foodie_container_bg_color' => array(
			'default'  => '#ffffff',
			'label'    => __( 'Container Background Color', 'foodie-pro' ),
			'selector' => '.site-container',
			'rule'     => 'background',
		),
		'foodie_accent_color' => array(
			'default'  => '#f5f5f5',
			'label'    => __( 'Accent Background Color', 'foodie-pro' ),
			'selector' => '.recipes-top, .footer-widgets, .form-allowed-tags',
			'rule'     => 'background',
		),
		'foodie_action_color' => array(
			'default'  => '#f5f5f5',
			'label'    => __( 'Action Background Color', 'foodie-pro' ),
			'selector' => '.top-cta, .enews-widget',
			'rule'     => 'background',
		),
		'foodie_site_title_color' => array(
			'default'  => '#444444',
			'label'    => __( 'Site Title Color', 'foodie-pro' ),
			'selector' => '.site-title a, .site-title a:hover',
			'rule'     => 'color',
		),
		'foodie_menu_bg_color' => array(
			'default'  => '#ffffff',
			'label'    => __( 'Menu Background Color', 'foodie-pro' ),
			'selector' => '.genesis-nav-menu',
			'rule'     => 'background',
		),
		'foodie_menu_link_color' => array(
			'default'  => '#333333',
			'label'    => __( 'Menu Link Color', 'foodie-pro' ),
			'selector' => '.genesis-nav-menu > li > a',
			'rule'     => 'color',
		),
		'foodie_menu_link_hover_color' => array(
			'default'  => '#000000',
			'label'    => __( 'Menu Link Hover Color', 'foodie-pro' ),
			'selector' => '.genesis-nav-menu > li > a:hover, .genesis-nav-menu > .current-menu-item > a',
			'rule'     => 'color',
		),
		'foodie_text_color' => array(
			'default'  => '#333333',
			'label'    => __( 'Text Color', 'foodie-pro' ),
			'selector' => 'body, .site-description',
			'rule'     => 'color',
		),
		'foodie_border_color' => array(
			'default'  => '#777777',
			'label'    => __( 'Border Color', 'foodie-pro' ),
			'selector' => '.genesis-nav-menu, .genesis-nav-menu .sub-menu, .entry-footer .entry-meta, .post-meta, li.comment',
			'rule'     => 'border-color',
		),
		'foodie_entry_title_color' => array(
			'default'  => '#444444',
			'label'    => __( 'Title Color', 'foodie-pro' ),
			'selector' => 'h1.entry-title, .entry-title a, .widgettitle, .recipes-top .widgettitle, .footer-widgets .widgettitle',
			'rule'     => 'color',
		),
		'foodie_secondary_text_color' => array(
			'default'  => '#999999',
			'label'    => __( 'Secondary Text Color', 'foodie-pro' ),
			'selector' => '.entry-meta, .post-info, .post-meta, .site-footer',
			'rule'     => 'color',
		),
		'foodie_accent_text_color' => array(
			'default'  => '#333333',
			'label'    => __( 'Accent Text Color', 'foodie-pro' ),
			'selector' => '.footer-widgets, .recipes-top, .form-allowed-tags',
			'rule'     => 'color',
		),
		'foodie_action_text_color' => array(
			'default'  => '#010101',
			'label'    => __( 'Action Text Color', 'foodie-pro' ),
			'selector' => '.top-cta, .enews-widget, .top-cta .widgettitle, .enews-widget .widgettitle',
			'rule'     => 'color',
		),
		'foodie_link_color' => array(
			'default'  => '#010101',
			'label'    => __( 'Link Color', 'foodie-pro' ),
			'selector' => 'a, .entry-meta a, .post-info a, .post-meta a, .site-footer a, .entry-content a',
			'rule'     => 'color',
		),
		'foodie_link_hover_color' => array(
			'default'  => '#111111',
			'label'    => __( 'Link Hover Color', 'foodie-pro' ),
			'selector' => 'a:hover, .entry-meta a:hover, .post-info a:hover, .post-meta a:hover, .site-footer a:hover',
			'rule'     => 'color',
		),
		'foodie_button_color' => array(
			'default'  => '#010101',
			'label'    => __( 'Button Color', 'foodie-pro' ),
			'selector' => '.enews-widget input[type="submit"]',
			'rule'     => 'background',
		),
		'foodie_button_hover_color' => array(
			'default'  => '#ffffff',
			'label'    => __( 'Button Hover Color', 'foodie-pro' ),
			'selector' => '.enews-widget input[type="submit"]:hover',
			'rule'     => 'background',
		),
		'foodie_button_text_color' => array(
			'default'  => '#ffffff',
			'label'    => __( 'Button Text Color', 'foodie-pro' ),
			'selector' => '.button, button, .enews-widget input[type="submit"]',
			'rule'     => 'color',
		),
		'foodie_button_text_hover_color' => array(
			'default'  => '#010101',
			'label'    => __( 'Button Hover Text Color', 'foodie-pro' ),
			'selector' => '.button:hover, button:hover, .enews-widget input[type="submit"]:hover',
			'rule'     => 'color',
		),
	);
	return apply_filters( 'foodie_pro_get_colors', $colors );
}

/**
 * An array of the font settings used in Foodie Pro.
 *
 * @since  2.0.0
 *
 * @return array $fonts
 */
function foodie_pro_get_fonts() {
	$fonts = array(
		'foodie_body_font' => array(
			'default_family' => 'Source Sans Pro',
			'default_size'   => '16px',
			'default_style'  => 'disabled',
			'default_weight' => '300',
			'label'          => __( 'Body Font', 'foodie-pro' ),
			'selector'       => 'body',
		),
		'foodie_accent_font' => array(
			'default_family' => 'Droid Serif',
			'default_size'   => 'disabled',
			'default_style'  => 'italic',
			'default_weight' => '300',
			'label'          => __( 'Accent Font', 'foodie-pro' ),
			'selector'       => 'input, select, textarea, .wp-caption-text, .site-description, .entry-meta, .post-info, .post-meta',
		),
		'foodie_heading_font' => array(
			'default_family' => 'Source Sans Pro',
			'default_size'   => 'disabled',
			'default_style'  => 'normal',
			'default_weight' => '300',
			'label'          => __( 'Heading Font', 'foodie-pro' ),
			'selector'       => 'h1, h2, h3, h4, h5, h6, .site-title, .entry-title, .widgettitle',
		),
	);
	return apply_filters( 'foodie_pro_get_fonts', $fonts );
}

add_filter( 'customizer_library_font_variants', 'foodie_pro_font_variants', 10, 3 );
/**
 * Filters the allowed Google Font varoamts for the Foodie Pro theme.
 *
 * @since  2.0.0
 *
 * @param  array $chosen_variants
 * @param  array $font
 * @param  array $variants
 * @return array $chosen_variants
 */
function foodie_pro_font_variants( $chosen_variants, $font, $variants ) {

	// Only add "200" if it exists
	if ( in_array( '200', $variants ) ) {
		$chosen_variants[] = '200';
	}

	// Only add "300" if it exists
	if ( in_array( '300', $variants ) ) {
		$chosen_variants[] = '300';
	}

	// Only add "300italic" if it exists
	if ( in_array( '300italic', $variants ) ) {
		$chosen_variants[] = '300italic';
	}

	// Only add "900" if it exists
	if ( in_array( '900', $variants ) ) {
		$chosen_variants[] = '900';
	}

	return array_unique( $chosen_variants );
}
//* Disable standard fonts.
add_filter( 'customizer_library_all_fonts', 'customizer_library_get_google_fonts' );

add_filter( 'customizer_library_get_google_fonts', 'foodie_pro_get_google_fonts' );
/**
 * Filters the allowed Google Fonts for the Foodie Pro theme.
 *
 * @since  2.0.0
 *
 * @param  array $fonts
 * @return array $fonts
 */
function foodie_pro_get_google_fonts( $fonts ) {
	$fonts = array(
		'Source Sans Pro' => array(
			'label'    => 'Source Sans Pro',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'vietnamese',
				'latin-ext',
			),
		),
		'Abril Fatface' => array(
			'label'    => 'Abril Fatface',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
		),
		'Adamina' => array(
			'label'    => 'Adamina',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
		),
		'Droid Serif' => array(
			'label'    => 'Droid Serif',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
			),
		),
		'Lato' => array(
			'label'    => 'Lato',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
			),
		),
		'Libre Baskerville' => array(
			'label'    => 'Libre Baskerville',
			'variants' => array(
				'regular',
				'italic',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
		),
		'Oswald' => array(
			'label'    => 'Oswald',
			'variants' => array(
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
		),
		'PT Sans Narrow' => array(
			'label'    => 'PT Sans Narrow',
			'variants' => array(
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
		),
		'PT Serif' => array(
			'label'    => 'PT Serif',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
				'cyrillic-ext',
			),
		),
		'Playfair Display' => array(
			'label'    => 'Playfair Display',
			'variants' => array(
				'regular',
				'italic',
				'700',
				'700italic',
				'900',
				'900italic',
			),
			'subsets' => array(
				'latin',
				'cyrillic',
				'latin-ext',
			),
		),
		'Raleway' => array(
			'label'    => 'Raleway',
			'variants' => array(
				'100',
				'200',
				'300',
				'regular',
				'500',
				'600',
				'700',
				'800',
				'900',
			),
			'subsets' => array(
				'latin',
			),
		),
		'Roboto Slab' => array(
			'label'    => 'Roboto Slab',
			'variants' => array(
				'100',
				'300',
				'regular',
				'700',
			),
			'subsets' => array(
				'latin',
				'greek-ext',
				'cyrillic',
				'greek',
				'vietnamese',
				'latin-ext',
				'cyrillic-ext',
			),
		),
	);
	return $fonts;
}

endif;
