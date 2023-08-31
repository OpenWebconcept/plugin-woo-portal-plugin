<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Includes/Admin
 * @since      1.0.0
 */

namespace WOO_Portal\Includes\Admin;

use WOO_Portal\Includes\Customizer;
use WOO_Portal\Includes\Plugin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the admin-specific functionality of the plugin.
 *
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Includes/Admin
 * @author     Richard Korthuis <richardkorthuis@acato.nl>
 */
class Admin {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->load_blocks();

		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ], 99 );

		if ( is_wp_version_compatible( '5.8' ) ) {
			add_filter( 'allowed_block_types_all', [ $this, 'allowed_block_types' ], 5, 1 );
		} else {
			// Deprecated since WordPress 5.8, read `https://developer.wordpress.org/reference/hooks/allowed_block_types/`.
			add_filter( 'allowed_block_types', [ $this, 'allowed_block_types' ], 5, 1 );
		}

		$customizer = new Customizer();
		add_action( 'customize_register', [ $customizer, 'register_customizer_options' ] );
		add_action( 'customize_save_after', [ $customizer, 'generate_css_file' ] );
		add_action( 'wp_enqueue_scripts', [ $customizer, 'enqueue_customizer_preview_assets' ], 11 );
		add_action( 'wp_dashboard_setup', [ $customizer, 'register_dashboard_widget' ] );
	}

	/**
	 * Enqueue assets for dynamic blocks for the block editor.
	 */
	public function enqueue_block_editor_assets() {
		$script_asset_path = WOO_PORTAL_ABSPATH . WOO_PORTAL_ASSETS_DIR . 'index.asset.php';
		if ( Plugin::has_resource( $script_asset_path ) ) {
			$script_asset = require $script_asset_path;
		} else {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( 'woo-portal-plugin (index.asset.php) isn`t found. Forgot to run `npm run build`?' );

			return false;
		}

		if ( Plugin::has_resource( WOO_PORTAL_ABSPATH . WOO_PORTAL_ASSETS_DIR . 'index.js' ) ) {
			wp_enqueue_script(
				'woo-portal-plugin-editor',
				esc_url( WOO_PORTAL_ASSETS_URL ) . 'index.js',
				$script_asset['dependencies'],
				$script_asset['version'],
				false
			);

			wp_localize_script( 'woo-portal-plugin-editor', 'woo_portal_editor_variables', apply_filters( 'woo_portal_editor_variables', [], get_the_ID() ) );

			wp_set_script_translations(
				'woo-portal-plugin-editor',
				'woo-portal-plugin',
				trailingslashit( WOO_PORTAL_ABSPATH ) . 'languages/'
			);
		}

		// Loop through all blocks and check for existing `style.css` and enqueue it.
		foreach ( glob( WOO_PORTAL_ABSPATH . WOO_PORTAL_ASSETS_DIR . 'blocks/*/*', GLOB_ONLYDIR ) as $woo_portal_community ) {

			if ( ! Plugin::has_resource( $woo_portal_community . '/editor.css' ) ) {
				// Continue if the file is empty.
				continue;
			}

			$woo_portal_parts  = explode( '/', $woo_portal_community );
			$woo_portal_handle = implode( '-', array_slice( $woo_portal_parts, - 2 ) );

			wp_enqueue_style(
				"$woo_portal_handle-block-editor",
				woo_portal_mix( 'blocks/' . implode( '/', array_slice( $woo_portal_parts, - 2 ) ) . '/editor.css' ),
				[],
				filemtime( $woo_portal_community . '/editor.css' ) ?? WOO_PORTAL_VERSION
			);
		}
	}

	/**
	 * Load all blocks.
	 */
	public function load_blocks() {
		foreach ( glob( WOO_PORTAL_ABSPATH . WOO_PORTAL_BLOCKS_DIR . '*/*/class-*.php' ) as $file ) {
			include_once $file;
		}

		foreach ( glob( WOO_PORTAL_ABSPATH . WOO_PORTAL_BLOCKS_DIR . '*/*/block.php' ) as $file ) {
			include_once $file;
		}
	}

	/**
	 * Register all blocks from this plugin as allowed block types.
	 *
	 * @param bool|array $allowed_block_types A list of allowed block types, true if all blocks are allowed.
	 *
	 * @return array $allowed_block_types A list of all blocks registered by this plugin.
	 */
	public function allowed_block_types( $allowed_block_types ) {
		$woo_portal_block_list = null;
		include_once WOO_PORTAL_ABSPATH . WOO_PORTAL_BLOCKS_DIR . 'block-list.php';

		if ( is_array( $allowed_block_types ) && is_array( $woo_portal_block_list ) ) {
			$allowed_block_types = array_merge( $allowed_block_types, $woo_portal_block_list );
		}

		return $allowed_block_types;
	}
}
