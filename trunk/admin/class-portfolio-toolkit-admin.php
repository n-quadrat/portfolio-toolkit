<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://dmitrymayorov.com/
 * @since      1.0.0
 *
 * @package    Portfolio_Toolkit
 * @subpackage Portfolio_Toolkit/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Portfolio_Toolkit
 * @subpackage Portfolio_Toolkit/admin
 * @author     Dmitry Mayorov <iamdmitrymayorov@gmail.com>
 */
class Portfolio_Toolkit_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Gets featured image.
	 *
	 * @since 1.0.0
	 */
	public function post_type_get_featured_image( $post_ID ) {
		$featured_image_id = get_post_thumbnail_id($post_ID);

		if ( $featured_image_id ) {
			$post_thumbnail_img = wp_get_attachment_image_src( $featured_image_id, 'thumbnail' );
			return $post_thumbnail_img[ 0 ];
		}
	}

	/**
	 * Adds image column to Portfolio post listing screen.
	 *
	 * @since 1.0.0
	 */
	public function post_type_admin_columns( $columns ) {
		// change 'Title' to 'Project' Jetpack Style.
		$columns['title'] = __( 'Project', 'portfolio-toolkit' );
		
		// Add Image column if current theme supports thumbnails.
		if ( current_theme_supports( 'post-thumbnails' ) ) {
			$columns = array_slice( $columns, 0, 1, true ) +
		               array( 'thumbnail' => __( 'Image', 'portfolio-toolkit' ) ) +
		               array_slice( $columns, 1, NULL, true );
		}
		
		return $columns;
	}

	/**
	 * Displays Featured image or a placeholder.
	 *
	 * @since 1.0.0
	 */
	public function post_type_admin_columns_content( $column_name, $post_ID ) {
		// Quick return if current theme does not support thumbnails.
		if ( ! current_theme_supports( 'post-thumbnails' ) ) {
			return;
		}

		if ( $column_name == 'thumbnail' ) {

			// Try to get featured image ID.
			$post_featured_image = $this->post_type_get_featured_image( $post_ID );
			
			// Build an 'edit post' link.
			printf( '<a href="%s">', get_edit_post_link( $post_ID ) );

				// Display image or placeholder.
				if ( $post_featured_image ) {
					printf( '<img src="%s">', $post_featured_image );
				} else {
					echo '<span class="no-image dashicons dashicons-format-image">';
				}

			echo '</a>';

		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			$this->plugin_name . '-css',
			plugin_dir_url( __FILE__ ) . 'css/portfolio-toolkit-admin.css',
			array(), $this->version,
			'all'
		);

	}

}