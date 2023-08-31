<?php
/**
 * Get the ID from the mix-manifest file.
 *
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Helpers
 * @since      1.0.0
 */

if ( ! function_exists( 'woo_portal_mix' ) ) {

	/**
	 * Just a little helper to get filenames from the mix-manifest file.
	 *
	 * @param {string} $path to file.
	 *
	 * @return string
	 */
	function woo_portal_mix( $path ): string {
		$manifest = WOO_PORTAL_ABSPATH . WOO_PORTAL_ASSETS_DIR . 'mix-manifest.json';

		if ( ! file_exists( $manifest ) ) {
			return WOO_PORTAL_ASSETS_URL . $path;
		}

		$manifest = json_decode(
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			file_get_contents( $manifest )
		);

		// We need to set the `/` in front of the `$path` due to how the mix-manifest.json file is saved.
		if ( ! str_starts_with( $path, '/' ) ) {
			$path = '/' . $path;
		}

		$manifest = get_object_vars( $manifest );

		if ( ! array_search( $path, $manifest, true ) ) {
			return WOO_PORTAL_ASSETS_URL . $path;
		}

		return untrailingslashit( WOO_PORTAL_ASSETS_URL ) . $manifest[ $path ];
	}
}
