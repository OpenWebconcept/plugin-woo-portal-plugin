<?php
/**
 * The settings page of the plugin.
 *
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Includes/Admin
 * @since      1.0.0
 */

namespace WOO_Portal\Includes\Admin;

/**
 * The settings page of the plugin.
 *
 * Provides a site wide settings page to configure which portal post types are
 * shown in the search block (in the filter and in the results).
 *
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Includes/Admin
 * @author     Acato
 */
class Settings {

	/**
	 * The option that stores the enabled post types.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'woo_portal_enabled_post_types';

	/**
	 * The settings group / page slug.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'woo-portal-settings';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Register the settings page as a submenu of the Settings menu.
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'WOO Portal', 'woo-portal-plugin' ),
			__( 'WOO Portal', 'woo-portal-plugin' ),
			'manage_options',
			self::PAGE_SLUG,
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Register the setting and its sanitisation.
	 */
	public function register_settings() {
		register_setting(
			self::PAGE_SLUG,
			self::OPTION_NAME,
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize_post_types' ],
				'default'           => [],
			]
		);
	}

	/**
	 * Sanitize the submitted post types against the available portal post types.
	 *
	 * @param mixed $value The submitted value.
	 *
	 * @return array A list of valid post type keys.
	 */
	public function sanitize_post_types( $value ) {
		if ( ! is_array( $value ) ) {
			return [];
		}

		$available = wp_list_pluck( $this->get_available_post_types(), 'key' );

		return array_values( array_intersect( array_map( 'sanitize_key', $value ), $available ) );
	}

	/**
	 * Get the portal post types available for filtering.
	 *
	 * The authoritative list is provided by the portal REST API, so it stays in
	 * sync with whatever post types the portal actually exposes (and already
	 * excludes post types that are not registered).
	 *
	 * @return array A list of [ 'label' => string, 'key' => string ] entries.
	 */
	public function get_available_post_types() {
		$request  = new \WP_REST_Request( 'GET', '/owc/portal/v1/filters/types' );
		$response = rest_do_request( $request );

		if ( $response->is_error() ) {
			return [];
		}

		$data = $response->get_data();

		return isset( $data['Types'] ) && is_array( $data['Types'] ) ? $data['Types'] : [];
	}

	/**
	 * Render the settings page.
	 */
	public function render_settings_page() {
		$available = $this->get_available_post_types();
		$saved     = get_option( self::OPTION_NAME, false );
		// A missing option means the setting was never saved, in which case every post type is shown.
		$show_all = ( false === $saved );

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'WOO Portal', 'woo-portal-plugin' ) . '</h1>';

		if ( empty( $available ) ) {
			echo '<p>' . esc_html__( 'No portal post types were found. Make sure the portal REST API is available.', 'woo-portal-plugin' ) . '</p>';
			echo '</div>';

			return;
		}

		echo '<form method="post" action="options.php">';
		settings_fields( self::PAGE_SLUG );

		echo '<table class="form-table" role="presentation"><tbody><tr>';
		echo '<th scope="row">' . esc_html__( 'Post types in search', 'woo-portal-plugin' ) . '</th>';
		echo '<td><fieldset>';
		echo '<legend class="screen-reader-text"><span>' . esc_html__( 'Post types in search', 'woo-portal-plugin' ) . '</span></legend>';

		foreach ( $available as $post_type ) {
			if ( empty( $post_type['key'] ) ) {
				continue;
			}

			$checked = $show_all || in_array( $post_type['key'], (array) $saved, true );

			printf(
				'<label><input type="checkbox" name="%1$s[]" value="%2$s" %3$s /> %4$s</label><br />',
				esc_attr( self::OPTION_NAME ),
				esc_attr( $post_type['key'] ),
				checked( $checked, true, false ),
				esc_html( $post_type['label'] ?? $post_type['key'] )
			);
		}

		echo '<p class="description">' . esc_html__( 'Only the checked post types are shown in the search filter and included in the results.', 'woo-portal-plugin' ) . '</p>';
		echo '</fieldset></td>';
		echo '</tr></tbody></table>';

		submit_button();

		echo '</form>';
		echo '</div>';
	}
}
