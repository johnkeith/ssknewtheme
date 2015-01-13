<?php
/**
 * Foodie Pro Featured Posts
 *
 * @package     FoodiePro
 * @subpackage  Genesis
 * @copyright   Copyright (c) 2014, Shay Bocks
 * @license     GPL-2.0+
 * @since       2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Foodie_Pro_Featured_Posts' ) ) :

/**
 * Genesis Featured Post widget class.
 *
 * @since 0.1.8
 *
 * @package Genesis\Widgets
 */
class Foodie_Pro_Featured_Posts extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor. Set the default widget options and create widget.
	 *
	 * @since 2.0.0
	 */
	function __construct() {

		$this->defaults = array(
			'title'                   => '',
			'posts_cat'               => '',
			'posts_num'               => 1,
			'posts_offset'            => 0,
			'orderby'                 => '',
			'order'                   => '',
			'exclude_displayed'       => 0,
			'show_image'              => 'none',
			'image_alignment'         => '',
			'image_size'              => '',
			'show_gravatar'           => 0,
			'gravatar_alignment'      => '',
			'gravatar_size'           => '',
			'simple_grid'             => 'full',
			'show_title'              => 0,
			'show_byline'             => 0,
			'post_info'               => '[post_date] ' . __( 'By', 'foodie-pro' ) . ' [post_author_posts_link] [post_comments]',
			'show_content'            => 'excerpt',
			'content_limit'           => '',
			'more_text'               => __( '[Read More...]', 'foodie-pro' ),
			'more_from_category'      => '',
			'more_from_category_text' => __( 'More Posts from this Category', 'foodie-pro' ),
		);

		$widget_ops = array(
			'classname'   => 'featured-content featuredpost',
			'description' => __( 'Displays featured posts with thumbnails', 'foodie-pro' ),
		);

		$control_ops = array(
			'id_base' => 'featured-post',
		);

		parent::__construct( 'featured-post', __( 'Foodie Pro - Featured Posts', 'foodie-pro' ), $widget_ops, $control_ops );

	}

	/**
	 * Echo the widget content.
	 *
	 * @since 2.0.0
	 *
	 * @global WP_Query $wp_query               Query object.
	 * @global array    $_genesis_displayed_ids Array of displayed post IDs.
	 * @global $integer $more
	 *
	 * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		global $wp_query, $_genesis_displayed_ids;

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		//* Set up the author bio
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];
		}

		$query_args = array(
			'post_type' => 'post',
			'cat'       => $instance['posts_cat'],
			'showposts' => $instance['posts_num'],
			'offset'    => $instance['posts_offset'],
			'orderby'   => $instance['orderby'],
			'order'     => $instance['order'],
		);

		//* Exclude displayed IDs from this loop?
		if ( $instance['exclude_displayed'] ) {
			$query_args['post__not_in'] = (array) $_genesis_displayed_ids;
		}

		//* Make sure we don't get the wrong post class applied.
		if ( $grid = foodie_pro_archive_grid() ) {
			remove_filter( 'post_class', 'foodie_pro_grid_' . $grid );
		}

		if ( function_exists( 'foodie_pro_grid_' . $instance['simple_grid'] ) && 'full' !== $instance['simple_grid'] ) {
			add_filter( 'post_class', 'foodie_pro_grid_' . $instance['simple_grid'] );
		}

		$wp_query = new WP_Query( $query_args );

		if ( have_posts() ) : while ( have_posts() ) : the_post();

			$_genesis_displayed_ids[] = get_the_ID();

			genesis_markup( array(
				'html5'   => '<article %s>',
				'xhtml'   => sprintf( '<div class="%s">', implode( ' ', get_post_class() ) ),
				'context' => 'entry',
			) );

			$image = genesis_get_image( array(
				'format'  => 'html',
				'size'    => $instance['image_size'],
				'context' => 'featured-post-widget',
				'attr'    => genesis_parse_attr( 'entry-image-widget' ),
			) );

			if ( ( 'before_title' === $instance['show_image'] || '1' === $instance['show_image'] ) && $image ) {
				printf( '<a href="%s" title="%s" class="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), esc_attr( $instance['image_alignment'] ), $image );
			}

			if ( ! empty( $instance['show_gravatar'] ) ) {
				echo '<span class="' . esc_attr( $instance['gravatar_alignment'] ) . '">';
				echo get_avatar( get_the_author_meta( 'ID' ), $instance['gravatar_size'] );
				echo '</span>';
			}

			if ( ! empty( $instance['show_title'] ) ) {
				echo genesis_html5() ? '<header class="entry-header">' : '';
			}
				if ( ! empty( $instance['show_title'] ) ) {

					$title = get_the_title() ? get_the_title() : __( '(no title)', 'foodie-pro' );

					if ( genesis_html5() ) {
						printf( '<h2 class="entry-title"><a href="%s">%s</a></h2>', get_permalink(), esc_html( $title ) );
					}
					else {
						printf( '<h2><a href="%s">%s</a></h2>', get_permalink(), esc_html( $title ) );
					}

				}

				if ( ! empty( $instance['show_byline'] ) && ! empty( $instance['post_info'] ) ) {
					printf( genesis_html5() ? '<p class="entry-meta">%s</p>' : '<p class="byline post-info">%s</p>', do_shortcode( $instance['post_info'] ) );
				}

			if ( ! empty( $instance['show_title'] ) ) {
				echo genesis_html5() ? '</header>' : '';
			}

			if ( 'after_title' === $instance['show_image'] && $image ) {
				printf( '<a href="%s" title="%s" class="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), esc_attr( $instance['image_alignment'] ), $image );
			}

			if ( ! empty( $instance['show_content'] ) ) {

				echo genesis_html5() ? '<div class="entry-content">' : '';

				if ( 'excerpt' == $instance['show_content'] ) {
					the_excerpt();
				}
				elseif ( 'content-limit' == $instance['show_content'] ) {
					the_content_limit( (int) $instance['content_limit'], esc_html( $instance['more_text'] ) );
				}
				else {

					global $more;

					$orig_more = $more;
					$more = 0;

					the_content( esc_html( $instance['more_text'] ) );

					$more = $orig_more;

				}

				echo genesis_html5() ? '</div>' : '';

			}

			if ( 'after_content' === $instance['show_image'] && $image ) {
				printf( '<a href="%s" title="%s" class="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), esc_attr( $instance['image_alignment'] ), $image );
			}

			genesis_markup( array(
				'html5' => '</article>',
				'xhtml' => '</div>',
			) );

		endwhile; endif;

		if ( function_exists( 'foodie_pro_grid_' . $instance['simple_grid'] ) && 'full' !== $instance['simple_grid'] ) {
			remove_filter( 'post_class', 'foodie_pro_grid_' . $instance['simple_grid'] );
		}

		//* Restore original query
		wp_reset_query();

		if ( ! empty( $instance['more_from_category'] ) && ! empty( $instance['posts_cat'] ) ) {
			printf(
				'<p class="more-from-category"><a href="%1$s" title="%2$s">%3$s</a></p>',
				esc_url( get_category_link( $instance['posts_cat'] ) ),
				esc_attr( get_cat_name( $instance['posts_cat'] ) ),
				esc_html( $instance['more_from_category_text'] )
			);
		}

		echo $args['after_widget'];

	}

	/**
	 * Update a particular instance.
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @since 2.0.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {

		$new_instance['title']     = strip_tags( $new_instance['title'] );
		$new_instance['more_text'] = strip_tags( $new_instance['more_text'] );
		$new_instance['post_info'] = wp_kses_post( $new_instance['post_info'] );
		//* Update the legacy show_image value for consistancy moving forward.
		if ( '1' === $old_instance['show_image'] ) {
			$new_instance['show_image'] = 'before_title';
		}
		return $new_instance;

	}

	/**
	 * Echo the settings update form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		if ( '1' === $instance['show_image'] ) {
			$instance['show_image'] = 'before_title';
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'foodie-pro' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<div class="foodie-pro-widget-box">

			<p>
				<label for="<?php echo $this->get_field_id( 'simple_grid' ); ?>"><?php _e( 'Grid Columns', 'foodie-pro' ); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'simple_grid' ); ?>" name="<?php echo $this->get_field_name( 'simple_grid' ); ?>">
					<option value="full" <?php selected( 'full', $instance['simple_grid'] ); ?>><?php _e( 'Full Width', 'foodie-pro' ); ?></option>
					<option value="one_half" <?php selected( 'one_half', $instance['simple_grid'] ); ?>><?php _e( 'One Half', 'foodie-pro' ); ?></option>
					<option value="one_third" <?php selected( 'one_third', $instance['simple_grid'] ); ?>><?php _e( 'One Third', 'foodie-pro' ); ?></option>
					<option value="one_fourth" <?php selected( 'one_fourth', $instance['simple_grid'] ); ?>><?php _e( 'One Fourth', 'foodie-pro' ); ?></option>
					<option value="one_sixth" <?php selected( 'one_sixth', $instance['simple_grid'] ); ?>><?php _e( 'One Sixth', 'foodie-pro' ); ?></option>
				</select>
			</p>

		</div>

		<div class="foodie-pro-widget-box">

			<p>
				<label for="<?php echo $this->get_field_id( 'posts_cat' ); ?>"><?php _e( 'Category', 'foodie-pro' ); ?>:</label>
				<?php
				$categories_args = array(
					'name'            => $this->get_field_name( 'posts_cat' ),
					'selected'        => $instance['posts_cat'],
					'orderby'         => 'Name',
					'hierarchical'    => 1,
					'show_option_all' => __( 'All Categories', 'foodie-pro' ),
					'hide_empty'      => '0',
				);
				wp_dropdown_categories( $categories_args ); ?>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'posts_num' ); ?>"><?php _e( 'Number of Posts to Show', 'foodie-pro' ); ?>:</label>
				<input type="text" id="<?php echo $this->get_field_id( 'posts_num' ); ?>" name="<?php echo $this->get_field_name( 'posts_num' ); ?>" value="<?php echo esc_attr( $instance['posts_num'] ); ?>" size="2" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'posts_offset' ); ?>"><?php _e( 'Number of Posts to Offset', 'foodie-pro' ); ?>:</label>
				<input type="text" id="<?php echo $this->get_field_id( 'posts_offset' ); ?>" name="<?php echo $this->get_field_name( 'posts_offset' ); ?>" value="<?php echo esc_attr( $instance['posts_offset'] ); ?>" size="2" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order By', 'foodie-pro' ); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
					<option value="date" <?php selected( 'date', $instance['orderby'] ); ?>><?php _e( 'Date', 'foodie-pro' ); ?></option>
					<option value="title" <?php selected( 'title', $instance['orderby'] ); ?>><?php _e( 'Title', 'foodie-pro' ); ?></option>
					<option value="parent" <?php selected( 'parent', $instance['orderby'] ); ?>><?php _e( 'Parent', 'foodie-pro' ); ?></option>
					<option value="ID" <?php selected( 'ID', $instance['orderby'] ); ?>><?php _e( 'ID', 'foodie-pro' ); ?></option>
					<option value="comment_count" <?php selected( 'comment_count', $instance['orderby'] ); ?>><?php _e( 'Comment Count', 'foodie-pro' ); ?></option>
					<option value="rand" <?php selected( 'rand', $instance['orderby'] ); ?>><?php _e( 'Random', 'foodie-pro' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Sort Order', 'foodie-pro' ); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
					<option value="DESC" <?php selected( 'DESC', $instance['order'] ); ?>><?php _e( 'Descending (3, 2, 1)', 'foodie-pro' ); ?></option>
					<option value="ASC" <?php selected( 'ASC', $instance['order'] ); ?>><?php _e( 'Ascending (1, 2, 3)', 'foodie-pro' ); ?></option>
				</select>
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'exclude_displayed' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'exclude_displayed' ); ?>" value="1" <?php checked( $instance['exclude_displayed'] ); ?>/>
				<label for="<?php echo $this->get_field_id( 'exclude_displayed' ); ?>"><?php _e( 'Exclude Previously Displayed Posts?', 'foodie-pro' ); ?></label>
			</p>

		</div>

		<div class="foodie-pro-widget-box">

			<p>
				<label for="<?php echo $this->get_field_id( 'show_image' ); ?>"><?php _e( 'Show Image', 'foodie-pro' ); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'show_image' ); ?>" name="<?php echo $this->get_field_name( 'show_image' ); ?>">
					<option value="none">- <?php _e( 'Don\'t Show an Image', 'foodie-pro' ); ?> -</option>
					<option value="before_title" <?php selected( 'before_title', $instance['show_image'] ); ?>><?php _e( 'Before Title', 'foodie-pro' ); ?></option>
					<option value="after_title" <?php selected( 'after_title', $instance['show_image'] ); ?>><?php _e( 'After Title', 'foodie-pro' ); ?></option>
					<option value="after_content" <?php selected( 'after_content', $instance['show_image'] ); ?>><?php _e( 'After Content', 'foodie-pro' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image Size', 'foodie-pro' ); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'image_size' ); ?>" class="genesis-image-size-selector" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
					<option value="thumbnail">thumbnail (<?php echo get_option( 'thumbnail_size_w' ); ?>x<?php echo get_option( 'thumbnail_size_h' ); ?>)</option>
					<?php
					$sizes = genesis_get_additional_image_sizes();
					foreach( (array) $sizes as $name => $size )
						echo '<option value="'.esc_attr( $name ).'" '.selected( $name, $instance['image_size'], FALSE ).'>'.esc_html( $name ).' ( '.$size['width'].'x'.$size['height'].' )</option>';
					?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'image_alignment' ); ?>"><?php _e( 'Image Alignment', 'foodie-pro' ); ?>:</label>
				<select id="<?php echo $this->get_field_id( 'image_alignment' ); ?>" name="<?php echo $this->get_field_name( 'image_alignment' ); ?>">
					<option value="alignnone">- <?php _e( 'None', 'foodie-pro' ); ?> -</option>
					<option value="alignleft" <?php selected( 'alignleft', $instance['image_alignment'] ); ?>><?php _e( 'Left', 'foodie-pro' ); ?></option>
					<option value="alignright" <?php selected( 'alignright', $instance['image_alignment'] ); ?>><?php _e( 'Right', 'foodie-pro' ); ?></option>
					<option value="aligncenter" <?php selected( 'aligncenter', $instance['image_alignment'] ); ?>><?php _e( 'Center', 'foodie-pro' ); ?></option>
				</select>
			</p>

		</div>

		<div class="foodie-pro-widget-box">

			<p>
				<input id="<?php echo $this->get_field_id( 'show_title' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_title' ); ?>" value="1" <?php checked( $instance['show_title'] ); ?>/>
				<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Post Title', 'foodie-pro' ); ?></label>
			</p>

			<p>
				<input id="<?php echo $this->get_field_id( 'show_byline' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_byline' ); ?>" value="1" <?php checked( $instance['show_byline'] ); ?>/>
				<label for="<?php echo $this->get_field_id( 'show_byline' ); ?>"><?php _e( 'Show Post Info', 'foodie-pro' ); ?></label>
				<input type="text" id="<?php echo $this->get_field_id( 'post_info' ); ?>" name="<?php echo $this->get_field_name( 'post_info' ); ?>" value="<?php echo esc_attr( $instance['post_info'] ); ?>" class="widefat" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'show_content' ); ?>"><?php _e( 'Content Type', 'foodie-pro' ); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'show_content' ); ?>" name="<?php echo $this->get_field_name( 'show_content' ); ?>">
					<option value="content" <?php selected( 'content', $instance['show_content'] ); ?>><?php _e( 'Show Content', 'foodie-pro' ); ?></option>
					<option value="excerpt" <?php selected( 'excerpt', $instance['show_content'] ); ?>><?php _e( 'Show Excerpt', 'foodie-pro' ); ?></option>
					<option value="content-limit" <?php selected( 'content-limit', $instance['show_content'] ); ?>><?php _e( 'Show Content Limit', 'foodie-pro' ); ?></option>
					<option value="" <?php selected( '', $instance['show_content'] ); ?>><?php _e( 'No Content', 'foodie-pro' ); ?></option>
				</select>
				<br />
				<label for="<?php echo $this->get_field_id( 'content_limit' ); ?>"><?php _e( 'Limit content to', 'foodie-pro' ); ?>
					<input type="text" id="<?php echo $this->get_field_id( 'image_alignment' ); ?>" name="<?php echo $this->get_field_name( 'content_limit' ); ?>" value="<?php echo esc_attr( intval( $instance['content_limit'] ) ); ?>" size="3" />
					<?php _e( 'characters', 'foodie-pro' ); ?>
				</label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'more_text' ); ?>"><?php _e( 'More Text (if applicable)', 'foodie-pro' ); ?>:</label>
				<input type="text" id="<?php echo $this->get_field_id( 'more_text' ); ?>" name="<?php echo $this->get_field_name( 'more_text' ); ?>" value="<?php echo esc_attr( $instance['more_text'] ); ?>" />
			</p>

		</div>

		<div class="foodie-pro-widget-box">

			<p>
				<input id="<?php echo $this->get_field_id( 'show_gravatar' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_gravatar' ); ?>" value="1" <?php checked( $instance['show_gravatar'] ); ?>/>
				<label for="<?php echo $this->get_field_id( 'show_gravatar' ); ?>"><?php _e( 'Show Author Gravatar', 'foodie-pro' ); ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'gravatar_size' ); ?>"><?php _e( 'Gravatar Size', 'foodie-pro' ); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'gravatar_size' ); ?>" name="<?php echo $this->get_field_name( 'gravatar_size' ); ?>">
					<option value="45" <?php selected( 45, $instance['gravatar_size'] ); ?>><?php _e( 'Small (45px)', 'foodie-pro' ); ?></option>
					<option value="65" <?php selected( 65, $instance['gravatar_size'] ); ?>><?php _e( 'Medium (65px)', 'foodie-pro' ); ?></option>
					<option value="85" <?php selected( 85, $instance['gravatar_size'] ); ?>><?php _e( 'Large (85px)', 'foodie-pro' ); ?></option>
					<option value="125" <?php selected( 105, $instance['gravatar_size'] ); ?>><?php _e( 'Extra Large (125px)', 'foodie-pro' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'gravatar_alignment' ); ?>"><?php _e( 'Gravatar Alignment', 'foodie-pro' ); ?>:</label>
				<select id="<?php echo $this->get_field_id( 'gravatar_alignment' ); ?>" name="<?php echo $this->get_field_name( 'gravatar_alignment' ); ?>">
					<option value="alignnone">- <?php _e( 'None', 'foodie-pro' ); ?> -</option>
					<option value="alignleft" <?php selected( 'alignleft', $instance['gravatar_alignment'] ); ?>><?php _e( 'Left', 'foodie-pro' ); ?></option>
					<option value="alignright" <?php selected( 'alignright', $instance['gravatar_alignment'] ); ?>><?php _e( 'Right', 'foodie-pro' ); ?></option>
				</select>
			</p>

		</div>

		<div class="foodie-pro-widget-box">

			<p>
				<input id="<?php echo $this->get_field_id( 'more_from_category' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'more_from_category' ); ?>" value="1" <?php checked( $instance['more_from_category'] ); ?>/>
				<label for="<?php echo $this->get_field_id( 'more_from_category' ); ?>"><?php _e( 'Show Category Archive Link', 'foodie-pro' ); ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'more_from_category_text' ); ?>"><?php _e( 'Link Text', 'foodie-pro' ); ?>:</label>
				<input type="text" id="<?php echo $this->get_field_id( 'more_from_category_text' ); ?>" name="<?php echo $this->get_field_name( 'more_from_category_text' ); ?>" value="<?php echo esc_attr( $instance['more_from_category_text'] ); ?>" class="widefat" />
			</p>

		</div>

		<?php

	}

}

endif; // End class_exists check.
