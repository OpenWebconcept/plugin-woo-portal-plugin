<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Includes/Frontend
 * @since      1.0.0
 */

namespace WOO_Portal\Includes\Frontend;

use WOO_Portal\Includes\Customizer;
use WOO_Portal\Includes\Plugin;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines public-facing functionality of the plugin.
 *
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Includes/Frontend
 * @author     Richard Korthuis <richardkorthuis@acato.nl>
 */
class Frontend {

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( is_wp_version_compatible( '5.8' ) ) {
			add_filter( 'block_categories_all', [ $this, 'register_custom_block_category' ], 10, 1 );
		} else {
			add_filter( 'block_categories', [ $this, 'register_custom_block_category' ], 10, 1 );
		}

		$customizer = new Customizer();
		add_action( 'wp_enqueue_scripts', [ $customizer, 'enqueue_assets' ] );
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] );
		add_action( 'enqueue_block_assets', [ $this, 'dequeue_block_assets' ] );

		$woo_portal_theme = esc_attr( get_option( 'woo_portal_municipality', 'denhaag' ) );
		switch ( $woo_portal_theme ) {
			case 'denhaag':
				add_filter( 'wp_kses_allowed_html', [ $this, 'woo_portal_denhaag_extend_wp_kses_posts' ] );
				break;
		}
	}

	/**
	 * Register a custom block category.
	 *
	 * @param array $categories Array of block categories.
	 *
	 * @return array Array of block categories.
	 */
	public function register_custom_block_category( $categories ) {

		return [
			[
				'slug'  => 'woo-portal-plugin',
				'title' => __( 'WOO Portal Blocks', 'woo-portal-plugin' ),
			],
		];
	}

	/**
	 * Enqueue assets for dynamic blocks for the frontend.
	 */
	public function enqueue_block_assets() {
		if ( ! Plugin::has_resource( WOO_PORTAL_ABSPATH . WOO_PORTAL_ASSETS_DIR . 'mix-manifest.json' ) ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( 'woo-portal-plugin (mix-manifest.json) isn`t found. Forgot to run `npm run build` or `npx mix build --production` ?' );

			return false;
		}

		// Loop through all blocks and check for existing `style.css` and enqueue it.
		$woo_portal_communities = glob( WOO_PORTAL_ABSPATH . WOO_PORTAL_ASSETS_DIR . 'blocks/*/*', GLOB_ONLYDIR );
		if ( ! empty( $woo_portal_communities ) ) {
			foreach ( $woo_portal_communities as $woo_portal_community ) {

				$woo_portal_parts  = explode( '/', $woo_portal_community );
				$woo_portal_handle = implode( '-', array_slice( $woo_portal_parts, - 2 ) );

				if ( Plugin::has_resource( $woo_portal_community . '/style.css' ) ) {
					// Enqueue will be done from the `block.json`.
					wp_register_style(
						"woo-portal-$woo_portal_handle-block",
						woo_portal_mix( 'blocks/' . implode( '/', array_slice( $woo_portal_parts, - 2 ) ) . '/style.css' ),
						[],
						filemtime( $woo_portal_community . '/style.css' )
					);

				}
				if ( Plugin::has_resource( $woo_portal_community . '/client.js' ) ) {
					// Enqueue script (optional).
					// Enqueue will be done from the `block.json`.
					wp_register_script(
						"woo-portal-$woo_portal_handle-block",
						woo_portal_mix( 'blocks/' . implode( '/', array_slice( $woo_portal_parts, - 2 ) ) . '/client.js' ),
						false,
						filemtime( $woo_portal_community . '/client.js' ),
						true
					);
				}
			}
		}

		// Register shared block styles for the blocks.
		$woo_portal_shared_block_styles = glob( WOO_PORTAL_ABSPATH . WOO_PORTAL_ASSETS_DIR . 'client/blocks/*.css' );
		if ( ! empty( $woo_portal_shared_block_styles ) ) {
			foreach ( $woo_portal_shared_block_styles as $woo_portal_shared_block_style ) {

				$woo_portal_parts = explode( '/', $woo_portal_shared_block_style );
				$woo_portal_file  = implode( '-', array_slice( $woo_portal_parts, - 1 ) );

				if ( ! Plugin::has_resource( $woo_portal_shared_block_style ) ) {
					// Continue if the file is empty.
					continue;
				}

				// Enqueue will be done from the `block.json` if it's needed by a block as dependency.
				wp_register_style(
					substr( $woo_portal_file, 0, - 4 ),
					woo_portal_mix( 'client/blocks/' . $woo_portal_file ),
					[],
					filemtime( $woo_portal_shared_block_style )
				);
			}
		}
	}

	/**
	 * Dequeue assets for core blocks for the frontend when set in the settings page.
	 *
	 * @return void
	 */
	public function dequeue_block_assets() {
		$woo_portal_selected_default_block_styles = get_option( 'woo_portal_dequeue_default_block_styles', [] );
		if ( ! empty( $woo_portal_selected_default_block_styles ) ) {
			foreach ( $woo_portal_selected_default_block_styles as $woo_portal_selected_default_block_style ) {
				wp_dequeue_style( $woo_portal_selected_default_block_style );
			}
		}
	}

	/**
	 * Enhance the `wp_kses_post()` with allowed HTML for the Den Haag community.
	 *
	 * @param {array} $allowed_tags Array of all allowed tags.
	 *
	 * @return mixed
	 */
	public function woo_portal_denhaag_extend_wp_kses_posts( $allowed_tags ) {
		/*
		 * Index:
		 * 1. Buttons,
		 * 2. SVG & SVG inner elements;
		 * 3. Time
		 * 4. Table
		 */

		// 1. Buttons.
		$allowed_tags['button'] = wp_parse_args(
			[
				'aria-*'        => true,
				'aria-controls' => true, // This is excluded from `aria-*`.
			],
			array_key_exists( 'button', $allowed_tags ) ? $allowed_tags['button'] : []
		);

		// 2. SVG & SVG inner elements.
		$allowed_tags['svg'] = wp_parse_args(
			[
				'aria-hidden'     => true,
				'aria-label'      => true,
				'aria-labelledby' => true,
				'class'           => true,
				'id'              => true,
				'height'          => true,
				'fill'            => true,
				'focusable'       => true,
				'shape-rendering' => true,
				'style'           => true,
				'role'            => true,
				'viewbox'         => true, // Must be lowercase!
				'width'           => true,
				'xmlns'           => true,
			],
			array_key_exists( 'svg', $allowed_tags ) ? $allowed_tags['svg'] : []
		);

		$allowed_tags['path'] = wp_parse_args(
			[
				'aria-*'          => true,
				'shape-rendering' => true,
				'd'               => true,
				'fill'            => true,
				'focusable'       => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'style'           => true,
			],
			array_key_exists( 'path', $allowed_tags ) ? $allowed_tags['path'] : []
		);

		$allowed_tags['use'] = wp_parse_args(
			[
				'xlink:href'      => true,
				'href'            => true,
				'y'               => true,
				'x'               => true,
				'height'          => true,
				'width'           => true,

				// May overwrite `path` variables.
				'aria-*'          => true,
				'shape-rendering' => true,
				'd'               => true,
				'fill'            => true,
				'focusable'       => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'style'           => true,
			],
			array_key_exists( 'use', $allowed_tags ) ? $allowed_tags['use'] : []
		);

		// 3. Time.
		$allowed_tags['time'] = wp_parse_args(
			[
				'id'       => true,
				'datetime' => true,
				'aria-*'   => true,
			],
			array_key_exists( 'time', $allowed_tags ) ? $allowed_tags['time'] : []
		);

		// 4. Table.
		$allowed_tags['table'] = wp_parse_args(
			[
				'id'     => true,
				'class'  => true,
				'aria-*' => true,
			],
			array_key_exists( 'table', $allowed_tags ) ? $allowed_tags['table'] : []
		);
		$allowed_tags['tr']    = wp_parse_args(
			[
				'id'    => true,
				'class' => true,
			],
			array_key_exists( 'tr', $allowed_tags ) ? $allowed_tags['tr'] : []
		);
		$allowed_tags['th']    = wp_parse_args(
			[
				'id'        => true,
				'class'     => true,
				'scope'     => true,
				'aria-sort' => true,
			],
			array_key_exists( 'th', $allowed_tags ) ? $allowed_tags['th'] : []
		);
		$allowed_tags['td']    = wp_parse_args(
			[
				'id'    => true,
				'class' => true,
				'scope' => true,
			],
			array_key_exists( 'td', $allowed_tags ) ? $allowed_tags['td'] : []
		);

		return $allowed_tags;
	}
}
