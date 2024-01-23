<?php
/**
 * The Customizer functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Includes/Customizer
 */

namespace WOO_Portal\Includes;

use WP_Customize_Color_Control;

/**
 * The Customizer functionality of the plugin.
 */
class Customizer {

	/**
	 * Keyed array for the colors.
	 *
	 * @var array[]
	 */
	private array $fields = [];


	/**
	 * Sections to register.
	 *
	 * @var array[]
	 */
	private array $sections = [];

	/**
	 * The WordPress upload directory.
	 *
	 * @var array
	 */
	private array $upload_dir = [];

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		$this->fields = [
			'color' => apply_filters(
				'woo_portal_customizer_colors_filter',
				[
					'base'                     => [
						'label' => esc_attr_x( 'Text color', 'Customizer color label', 'woo-portal-plugin' ),
						'value' => '#000000',
					],
					'search-button'            => [
						'label' => esc_attr_x( 'Search button, Filters and Pagination color', 'Customizer color label', 'woo-portal-plugin' ),
						'value' => '#0062AC',
					],
					'search-button-text-color' => [
						'label' => esc_attr_x( 'Search button text color', 'Customizer color label', 'woo-portal-plugin' ),
						'value' => '#FFFFFF',
					],
				],
				10,
				1
			),
		];

		$this->sections = apply_filters(
			'woo_portal_customizer_section_filter',
			[
				'color' => [
					'title'       => esc_attr_x( 'WOO Portal Colors', 'Customizer section title', 'woo-portal-plugin' ),
					'description' => esc_attr_x( 'Define the colors used within the WOO Portal & Theme.', 'Customizer section description', 'woo-portal-plugin' ),
					'priority'    => 30,
				],
			],
			10,
			1
		);

		$this->upload_dir = wp_upload_dir();
	}

	/**
	 * Adding Panels, sections and options to the WordPress Customizer.
	 * Fires once WordPress has loaded, allowing scripts and styles to be initialized.
	 *
	 * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager instance.
	 *
	 * @return void
	 */
	public function register_customizer_options( $wp_customize ): void {
		if ( ! empty( $this->sections ) ) {
			foreach ( $this->sections as $type => $options ) {
				$wp_customize->add_section( esc_attr( 'owp_' . $type . '_section' ), $options );
			}
		}

		if ( ! empty( $this->fields ) ) {
			$default_settings = [
				'default'           => null,
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
				'capability'        => apply_filters( 'woo_portal_customizer_capability', 'edit_theme_options' ),
			];

			foreach ( $this->fields as $type => $fields ) {
				if ( empty( $fields ) ) {
					continue;
				}

				if ( 'color' === $type ) {
					$default_settings = wp_parse_args(
						[
							'sanitize_callback' => 'sanitize_hex_color',
						],
						$default_settings
					);
				}

				foreach ( $fields as $field => $options ) {

					// Add option settings.
					$wp_customize->add_setting(
						"owp_css[$type][$field]",
						wp_parse_args(
							[
								'default' => $options['value'],
							],
							$default_settings
						)
					);

					$field_settings = [
						'label'    => $options['label'],
						'section'  => esc_attr( 'owp_' . $type . '_section' ),
						'settings' => "owp_css[$type][$field]",
					];

					switch ( $type ) {
						case 'color':
							// Add color control.
							$wp_customize->add_control(
								new \WP_Customize_Color_Control( $wp_customize, "owp_css[$type][$field]", $field_settings )
							);
							break;
						default:
							// Add the default controller.
							$wp_customize->add_control(
								"owp_css[$type][$field]",
								wp_parse_args(
									[
										'type' => 'text',
									],
									$field_settings
								)
							);
							break;
					}
				}
			}
		}
	}

	/**
	 * Generate CSS output.
	 *
	 * @return string
	 */
	private function generate_css(): string {
		$modified_options = get_theme_mod( 'owp_css' );

		if ( empty( $this->fields ) ) {
			return '<!-- The `CSS` theme_mods is empty. -->';
		}

		$css = ':root{';
		foreach ( $this->fields as $type => $fields ) {
			foreach ( $fields as $field => $options ) {
				if ( empty( $options['value'] ) || empty( $modified_options[ $type ] ) ) {
					// Set fallback variable for when both are empty.
					$css .= self::css_variable( $type, $field, 'inherit' );
					if ( self::allow_color_tint( $type, $field ) ) {
						$css .= self::css_variable( $type, $field . '-tint', 'inherit' );
					}
					continue;
				}

				if ( ! empty( $modified_options[ $type ][ $field ] ) ) {
					// Set updated variable as css variable.
					$css .= self::css_variable( $type, $field, $modified_options[ $type ][ $field ] );
					if ( self::allow_color_tint( $type, $field ) ) {
						$css .= self::css_variable( $type, $field . '-tint', self::generate_tint( $modified_options[ $type ][ $field ] ) );
					}
					continue;
				}

				// Set default variable as fallback.
				$css .= self::css_variable( $type, $field, $options['value'] );
				if ( self::allow_color_tint( $type, $field ) ) {
					$css .= self::css_variable( $type, $field . '-tint', self::generate_tint( $options['value'] ) );
				}
			}
		}

		$css .= '}';

		return $css;
	}

	/**
	 * Returns boolean based on the property and if it may have a tint.
	 *
	 * @param string $type  The type of field.
	 * @param string $field The field name.
	 *
	 * @return bool
	 */
	private static function allow_color_tint( $type = 'color', $field = 'base' ): bool {
		return in_array(
			$type,
			apply_filters(
				'woo_portal_customizer_allowed_color_tint_type_filter',
				[
					'color',
				]
			),
			true
		)
			&& in_array(
				$field,
				apply_filters(
					'woo_portal_customizer_allowed_color_tint_filter',
					[
						'search-button',
						'primary',
						'secondary',
					]
				),
				true
			);
	}

	/**
	 * Generate a CSS file with the saved variables.
	 *
	 * @return void
	 */
	public function generate_css_file(): void {
		if ( ! function_exists( '\\WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( WP_Filesystem() ) {
			global $wp_filesystem;
			$wp_filesystem->put_contents( trailingslashit( $this->upload_dir['basedir'] ) . 'owp-styles.css', self::generate_css(), FS_CHMOD_FILE );
		} else {
			// The Customizer is showing the error: `Looks like something’s gone wrong. Wait a couple seconds, and then try again.`.
			wp_die( 'WP_Filesystem() returned false, we could not generate a CSS file.' );
		}
	}

	/**
	 * Returns a string with the variable name.
	 *
	 * @param string $property The CSS property, equal to `color` or `font-size`.
	 * @param string $variant  The variant of the property, equal to `primary`, `hover`.
	 * @param string $value    The provided CSS variable.
	 *
	 * @return string
	 */
	private static function css_variable( $property, $variant, $value ): string {
		if ( empty( $value ) ) {
			return '';
		}

		if ( empty( $property ) ) {
			return "--owp-undefined:$value;";
		}

		if ( 'base' === $variant ) {
			return "--owp-$property:$value!important;";
		}

		return "--owp-$property-$variant:$value!important;";
	}


	/**
	 * Enqueue generated stylesheet.
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		if ( ! file_exists( trailingslashit( $this->upload_dir['basedir'] ) . 'owp-styles.css' ) ) {
			return;
		}

		wp_enqueue_style(
			'woo-portal',
			trailingslashit( $this->upload_dir['baseurl'] ) . 'owp-styles.css',
			[],
			filemtime( trailingslashit( $this->upload_dir['basedir'] ) . 'owp-styles.css' )
		);
	}

	/**
	 * Enqueue customizer preview CSS variables.
	 *
	 * @return void
	 */
	public function enqueue_customizer_preview_assets(): void {
		if ( is_customize_preview() ) {
			if ( ! file_exists( trailingslashit( $this->upload_dir['basedir'] ) . 'owp-styles.css' ) ) {
				// Register and enqueue source without a source, so we can just see the inline styles (when no existing file is enqueued).

				// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
				wp_register_style( 'woo-portal', false );
				wp_enqueue_style( 'woo-portal' );
			}

			wp_add_inline_style( 'woo-portal', self::generate_css() );
		}
	}

	/**
	 * Generate color tint.
	 *
	 * @param string $hex The hex color.
	 *
	 * @return string
	 */
	private static function generate_tint( $hex ): string {
		// Remove the '#' symbol if present.
		$hex = str_replace( '#', '', $hex );

		// Handle 3-digit color codes.
		if ( 3 === strlen( $hex ) ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		// Convert the hex color to RGB components.
		$red   = hexdec( substr( $hex, 0, 2 ) );
		$green = hexdec( substr( $hex, 2, 2 ) );
		$blue  = hexdec( substr( $hex, 4, 2 ) );

		$amount = apply_filters( 'woo_portal_customizer_color_tint_filter', 0.20 );

		// Convert the tint back to a hex color.
		return sprintf(
			'#%02X%02X%02X',
			esc_attr( $red + ( 255 - $red ) * $amount ),
			esc_attr( $green + ( 255 - $green ) * $amount ),
			esc_attr( $blue + ( 255 - $blue ) * $amount )
		);
	}

	/**
	 * Add a dashboard widget, where you can generate the CSS file from.
	 *
	 * @return void
	 */
	public function register_dashboard_widget() {
		if ( file_exists( trailingslashit( $this->upload_dir['basedir'] ) . 'owp-styles.css' ) ) {
			return;
		}

		wp_add_dashboard_widget(
			'owp-dashboard-widget',
			WOO_PORTAL_PLUGIN_NAME,
			[ $this, 'render_dashboard_widget' ]
		);
	}

	/**
	 * WordPress Dashboard widget, shown when no CSS file is found.
	 *
	 * @return void
	 */
	public function render_dashboard_widget() {

		if ( isset( $_POST['save_customizer_nonce'] ) && wp_verify_nonce( $_POST['save_customizer_nonce'], 'save_customizer_action' ) ) {
			// CSS File is generated, show success message.
			self::generate_css_file();
			printf( '<div><p>%s</p></div>', esc_attr_x( 'A CSS file has been successfully generated.', 'Dashboard message', 'woo-portal-plugin' ) );
		} elseif ( ! current_user_can( apply_filters( 'woo_portal_customizer_capability', 'edit_theme_options' ) ) ) {
			// Current user doesn't have the rights.

			$allowed_roles = self::get_roles_by_capability( apply_filters( 'woo_portal_customizer_capability', 'edit_theme_options' ) );
			if ( count( $allowed_roles ) > 1 ) {
				$last_item = array_pop( $allowed_roles );
				$imploded  = implode( ', ', $allowed_roles );
				/* translators: %1$s The imploded allowed_roles, %2$s the last of the allowed_roles. */
				$imploded_roles = sprintf( esc_attr_x( '%1$s or %2$s', 'Dashboard role separator', 'woo-portal-plugin' ), esc_attr( $imploded ), esc_attr( $last_item ) );
			} else {
				$imploded_roles = implode( ', ', $allowed_roles );
			}

			printf(
				'<div><p>%s</p></div>',
				sprintf(
				/* translators: %1%s The plugin name, %2$s Imploded user roles. */
					esc_attr_x( 'No CSS file has been generated yet, so the styling of the %1$s may not be displayed correctly. Contact a user with one of the following roles: %2$s', 'Dashboard message', 'woo-portal-plugin' ),
					esc_attr( WOO_PORTAL_PLUGIN_NAME ),
					esc_attr( $imploded_roles )
				)
			);
		} else {
			$nonce = wp_create_nonce( 'save_customizer_nonce' );

			/* translators: %1$s: The description, %2%s The WordPress admin url, %3$s Button label, %4$s Nonce field. */
			printf(
				'<div><p>%1$s</p>
				<form method="post" action="%2$s">
					<input type="hidden" name="action" value="save_customizer">
					%4$s
					<button type="submit" class="button button-primary">%3$s</button>
				</form>
				</div>',
				sprintf(
					// phpcs:disable
				// We disable the "phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped" due to HTML in the translation string and the translators message.
				/* translators: %1$s The plugin name, %2$s The Customizer URL. */
					_x( 'No CSS file has been generated yet, so the styling of the %1$s may not be displayed correctly. Go to the <a href="%2$s">Customizer</a> or generate the CSS file using the button below.', 'Dashboard message', 'woo-portal-plugin' ),
					// phpcs:enable
					esc_attr( WOO_PORTAL_PLUGIN_NAME ),
					esc_url( wp_customize_url() )
				),
				esc_url( admin_url() ),
				esc_attr_x( 'Generate CSS-file', 'Dashboard message', 'woo-portal-plugin' ),
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				wp_nonce_field( 'save_customizer_action', 'save_customizer_nonce', true, false )
			);
		}
	}

	/**
	 * Get Roles by capability.
	 *
	 * @param string $capability The capability to check on.
	 *
	 * @return array
	 */
	private static function get_roles_by_capability( $capability ) {
		$roles_with_capability = [];
		$roles                 = wp_roles()->roles;

		foreach ( $roles as $role_name => $role_info ) {
			if ( ! isset( $role_info['capabilities'][ $capability ] ) ) {
				continue;
			}

			// Check if the role has the desired capability.
			if ( true === $role_info['capabilities'][ $capability ] ) {
				$roles_with_capability[] = $role_info['name'];
			}
		}

		return $roles_with_capability;
	}
}
