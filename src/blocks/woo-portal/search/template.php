<?php
/**
 * Template for the Search Block.
 * Build the HTML for the Search Block.
 *
 * @since      1.0.0
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Src/Blocks/Woo_Portal/Search
 */

/**
 * Variables are passed on from the block-renderer.
 *
 * @global string $content    The Block content.
 * @global array  $attributes The Block attributes.
 */
$woo_portal_rest_uri = $attributes['rest_uri'];

if ( empty( $woo_portal_rest_uri ) || ! wp_http_validate_url( $woo_portal_rest_uri ) ) {
	$woo_portal_rest_uri = get_rest_url();
}

$woo_portal_rest_path = 'wp-json/owc/portal/v1/';

if ( preg_match( '/\/wp-json\/$/', $woo_portal_rest_uri ) ) {
	$woo_portal_rest_uri = preg_replace( '/\/wp-json\/$/', '/wp-json/owc/portal/v1/', $woo_portal_rest_uri );
}

// For when only a base url is provided.
if ( ! preg_match( '/wp-json\//', $woo_portal_rest_uri ) ) {
	$woo_portal_rest_uri = trailingslashit( $woo_portal_rest_uri ) . $woo_portal_rest_path;
}

?>
<div
<?php
echo woo_portal_to_dom_attributes(
	[
		'id'               => 'woo-portal-search',
		'class'            => 'woo-portal-search',
		'data-endpoint'    => esc_url( $woo_portal_rest_uri ),
		'data-title'       => esc_attr( $attributes['title'] ?? '' ),
		'data-excerpt'     => esc_attr( $attributes['excerpt'] ?? '' ),
		'data-placeholder' => esc_attr( $attributes['placeholder'] ?? '' ),
		'data-image'       => esc_attr( $attributes['image'] ?? 0 ),
		'data-per_page'    => esc_attr( $attributes['per_page'] ?? 10 ),
	]
);
?>
></div>
